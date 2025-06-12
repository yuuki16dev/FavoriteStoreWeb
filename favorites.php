<?php
session_start();
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : [];
$map_search_url = '';
if ($search_query !== '') {
    $map_search_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($search_query);
}
include 'favorites_view.html';
?>
