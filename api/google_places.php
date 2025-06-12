<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

$apiKey = 'AIzaSyDjcIxdZOBupvg8-9XIxBLp9tt7freW60I'; // ★ここにGoogle Cloud Consoleで取得したAPIキーを設定
$query = isset($_GET['query']) ? $_GET['query'] : '';
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '';
$radius = isset($_GET['dist']) ? floatval($_GET['dist']) * 1000 : 20000; // デフォルト20km

if (!$query) {
    echo json_encode(['error' => 'No query']);
    exit;
}

// Google Places API Text Search endpoint
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=' . urlencode($query) .
    ($lat && $lon ? ('&location=' . urlencode($lat . ',' . $lon) . '&radius=' . intval($radius)) : '') .
    '&language=ja&key=' . urlencode($apiKey);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode !== 200 || !$response) {
    echo json_encode(['error' => 'API通信エラー', 'status' => $httpcode, 'url' => $url]);
    exit;
}

$data = json_decode($response, true);
if (!$data || !isset($data['results'])) {
    echo json_encode(['error' => 'APIレスポンスエラー', 'raw' => $response]);
    exit;
}

// Google Places APIの生レスポンスをそのまま返す
// ページネーション対応（最大10件までwebsite取得）
if (isset($data['results']) && is_array($data['results'])) {
    $max = min(count($data['results']), 50); // 最大50件
    for ($i = 0; $i < $max; $i++) {
        $place = $data['results'][$i];
        if (!empty($place['place_id'])) {
            $detailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . urlencode($place['place_id']) . '&fields=website&language=ja&key=' . urlencode($apiKey);
            $ch2 = curl_init($detailsUrl);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
            $detailsRes = curl_exec($ch2);
            curl_close($ch2);
            $details = json_decode($detailsRes, true);
            if (isset($details['result']['website'])) {
                $data['results'][$i]['website'] = $details['result']['website'];
            }
        }
    }
}
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
