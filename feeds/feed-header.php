<?php
$slug = "";
require_once(mailplatform__PLUGIN_DIR . '_inc/feed-functions.php');

$total = isset($_GET['amount']) ? esc_html(sanitize_text_field($_GET['amount'])) : 10;

$func = new mailplatform_feed_functions();

$rsslanguage = get_option('rss_language');

if (isset($category) && !is_null($category)) {
    $term = get_term_by('id', $category, ($type == 'woocommerce' ? 'product_cat' : 'category'));

    $title = __('Category','mailplatform') . ' - ' . $term->name;
    $siteurl = get_term_link($term);

    $slug = $term->slug;
} else {
    $siteurl = get_option('home');
}

$rss_title = __("MailPlatform XMLFeed", 'mailplatform') . ' | ' . $title;

//generate feed
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'>
<channel>
<title>$rss_title</title>
<link>$siteurl</link>";

do_action('rss2_head');