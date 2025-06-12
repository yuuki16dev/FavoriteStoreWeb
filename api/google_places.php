<?php
// Google Places API経由で店舗検索・詳細取得API
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

// .envファイルからAPIキーを取得する関数
function getEnvVar($key) {
    $envPath = __DIR__ . '/../.env';
    if (!file_exists($envPath)) return null;
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($k, $v) = explode('=', $line, 2);
            if (trim($k) === $key) return trim($v);
        }
    }
    return null;
}

$apiKey = getEnvVar('GOOGLE_MAPS_API_KEY'); // .envから取得
if (!$apiKey) {
    echo json_encode(['error' => 'APIキーが設定されていません']);
    exit;
}

$query = isset($_GET['query']) ? $_GET['query'] : '';
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '';
$radius = isset($_GET['dist']) ? floatval($_GET['dist']) * 1000 : 20000; // デフォルト20km

// 検索クエリが未指定の場合はエラー返却
if (!$query) {
    echo json_encode(['error' => 'No query']);
    exit;
}

// Google Places API Text Searchエンドポイント生成
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . urlencode($query) .
    ($lat && $lon ? ('&location=' . urlencode($lat . ',' . $lon) . '&radius=' . intval($radius)) : '') .
    '&language=ja&key=' . urlencode($apiKey);

// Text Search APIリクエスト
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode !== 200 || !$response) {
    // API通信エラー時
    echo json_encode(['error' => 'API通信エラー', 'status' => $httpcode, 'url' => $url]);
    exit;
}

$data = json_decode($response, true);
if (!$data || !isset($data['results'])) {
    // レスポンスパースエラー時
    echo json_encode(['error' => 'APIレスポンスエラー', 'raw' => $response]);
    exit;
}

// 最大20件までの店舗情報に対し、Place Details APIでwebsiteを取得
if (isset($data['results']) && is_array($data['results'])) {
    $max = min(count($data['results']), 20); // 最大20件まで
    for ($i = 0; $i < $max; $i++) {
        $place = $data['results'][$i];
        if (!empty($place['place_id'])) {
            $detailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . urlencode($place['place_id']) . '&fields=website&language=ja&key=' . urlencode($apiKey);
            $ch2 = curl_init($detailsUrl);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
            $detailsRes = curl_exec($ch2);
            $httpcode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
            curl_close($ch2);
            if ($httpcode2 === 200 && $detailsRes) {
                $details = json_decode($detailsRes, true);
                if (isset($details['result']['website'])) {
                    $data['results'][$i]['website'] = $details['result']['website'];
                }
            } else {
                // エラー時は空文字セット
                $data['results'][$i]['website'] = '';
            }
        }
    }
}
// 最終レスポンス返却（JSON整形）
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
