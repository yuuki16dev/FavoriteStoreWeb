// =============================
// 共通リンク生成用ユーティリティ
// =============================

/**
 * 店舗名リンク生成（公式HPがあればリンク、なければspan）
 * @param {string} name 店舗名
 * @param {string} url 公式HPのURL
 * @returns {string} HTML文字列
 */
function createStoreNameLink(name, url) {
    // URLがhttp/httpsで始まらない場合は自動でhttps://を付与
    if (url && url !== '') {
        let safeUrl = url;
        if (!/^https?:\/\//i.test(url)) {
            safeUrl = 'https://' + url;
        }
        return `<a href="${safeUrl}" class="store-name" target="_blank" rel="noopener noreferrer">${name}</a>`;
    } else {
        return `<span class="store-name">${name}</span>`;
    }
}

/**
 * GoogleマップURLリンク生成（緑色クラス付与）
 * @param {string|number} lat 緯度
 * @param {string|number} lng 経度
 * @returns {string} HTML文字列
 */
function createMapUrlLink(lat, lng) {
    if (lat && lng) {
        // Googleマップの「地図で見る」テキストでリンク（?q=lat,lng&hl=ja形式）
        const mapUrl = `https://www.google.com/maps?q=${lat},${lng}&hl=ja`;
        return `<a class="map-link green-map-url" href="${mapUrl}" target="_blank">地図で見る</a>`;
    } else {
        return '';
    }
}
