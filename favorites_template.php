<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お気に入り店舗一覧</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 1em; }
        .map-link { margin-left: 1em; }
    </style>
</head>
<body>
    <h1>お気に入り店舗一覧</h1>
    <ul>
    <?php foreach ($favorites as $store): ?>
        <li>
            <strong><?php echo htmlspecialchars($store['name']); ?></strong><br>
            <?php echo htmlspecialchars($store['address']); ?>
            <a class="map-link" href="https://www.google.com/maps/search/?api=1&query=<?php echo $store['lat']; ?>,<?php echo $store['lng']; ?>" target="_blank">地図で見る</a>
        </li>
    <?php endforeach; ?>
    </ul>
    <a href="index.php">検索に戻る</a>
</body>
</html>
