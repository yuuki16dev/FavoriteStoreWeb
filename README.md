# FavoriteStoreApp

## 概要
FavoriteStoreAppは、Google Places APIを利用した店舗検索・お気に入り登録・お気に入り店舗一覧表示ができるWebアプリケーションです。ユーザーは地名や店舗名で検索し、気になる店舗をお気に入り登録することで、後から簡単に店舗情報や公式ホームページ、地図を確認できます。

## 主な機能
- Google Places API経由で店舗検索
- 検索結果から店舗をお気に入り登録
- お気に入り店舗はlocalStorageで管理
- お気に入り店舗一覧画面で、
  - 店舗名から公式ホームページに遷移
  - 「地図で見る」からGoogleマップ検索ページに遷移
  - お気に入り削除
- UIはGoogle風デザイン

## ファイル構成
- `favorites_search.html` : 店舗検索・お気に入り登録画面
- `favorites_list.html`   : お気に入り店舗一覧画面
- `linkUtils.js`          : 店舗名・地図リンク生成の共通JS
- `favorites.css`         : Google風デザインのCSS
- `api/google_places.php` : Google Places API中継用PHP

## 使い方
1. `favorites_search.html`で店舗を検索
2. 気になる店舗を「お気に入り登録」
3. `favorites_list.html`でお気に入り店舗を一覧表示
   - 店舗名クリックで公式HPへ
   - 「地図で見る」クリックでGoogleマップへ
   - 削除ボタンでお気に入り解除

## 注意事項
- Google Places APIキーが必要です（`api/google_places.php`に設定）
- お気に入り情報はブラウザのlocalStorageに保存されます
- 公式HPが取得できない店舗は店舗名が黒文字で表示されます

---

このアプリは、日常の店舗検索やお気に入り管理をより便利にするためのサンプル実装です。
