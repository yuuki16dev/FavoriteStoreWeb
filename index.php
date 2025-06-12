<?php
session_start();
$apiKey = 'YOUR_GOOGLE_API_KEY'; // ここにGoogle APIキーを設定
$query = isset($_GET['query']) ? $_GET['query'] : '';
$stores = [];
if ($query !== '') {
    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode($query) . "&language=ja&key=" . $apiKey;
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    if (isset($data['results'])) {
        foreach ($data['results'] as $result) {
            $stores[] = [
                'id' => $result['place_id'],
                'name' => $result['name'],
                'address' => $result['formatted_address'],
                'lat' => $result['geometry']['location']['lat'],
                'lng' => $result['geometry']['location']['lng']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>店舗検索</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 1em; }
        .map-link { margin-left: 1em; }
    </style>
</head>
<body>
    <h1>店舗検索（Googleマップ連携）</h1>
    <form method="get" action="index.php">
        <input type="text" name="query" placeholder="店舗名や地名で検索" value="<?php echo htmlspecialchars($query); ?>">
        <button type="submit">検索</button>
    </form>
    <ul>
    <?php foreach ($stores as $store): ?>
        <li>
            <strong><?php echo htmlspecialchars($store['name']); ?></strong><br>
            <?php echo htmlspecialchars($store['address']); ?>
            <a class="map-link" href="https://www.google.com/maps/search/?api=1&query=<?php echo $store['lat']; ?>,<?php echo $store['lng']; ?>" target="_blank">地図で見る</a>
            <form method="post" action="favorite.php" style="display:inline;">
                <input type="hidden" name="store_id" value="<?php echo $store['id']; ?>">
                <input type="hidden" name="store_name" value="<?php echo htmlspecialchars($store['name']); ?>">
                <input type="hidden" name="store_address" value="<?php echo htmlspecialchars($store['address']); ?>">
                <input type="hidden" name="store_lat" value="<?php echo $store['lat']; ?>">
                <input type="hidden" name="store_lng" value="<?php echo $store['lng']; ?>">
                <button type="submit">お気に入り登録</button>
            </form>
        </li>
    <?php endforeach; ?>
    </ul>
    <a href="favorites.php">お気に入り一覧を見る</a>
</body>
</html>
