<?php 
$urls = yaml_parse_file("routes.yml");

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset></urlset>');
$xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

foreach ($urls as $url) {
    $url = "URL_A_CHANGER/" . strtolower(trim($url["action"]));
    $urlElement = $xml->addChild('url');
    $urlElement->addChild('loc', $url);
}

$xml->asXML('sitemap.xml');
?>