<?php
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset></urlset>');
$xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

$routes = yaml_parse_file("routes.yml");
$action = "";

function array_search_inner($array, $attr): void
{
    global $xml;
    global $action;
    if (is_array($array)) {
        foreach ($array as $key => $inner) {
            if (is_array($inner) && array_key_exists("action", $inner)) {
                $action = strtolower(trim($inner["action"]));
            }
            if ($key == 'access') {
                foreach ($inner as $permission) {
                    if (strtoupper(trim($permission)) == 'ALL') {
                        if (!empty($action)) {
                            $url = "URL_A_CHANGER/" . $action;
                            $urlElement = $xml->addChild('url');
                            $urlElement->addChild('loc', $url);
                        }
                    }
                }
            } else if (is_array($inner)) {
                array_search_inner($inner, $attr);
            }
        }
    }
}
array_search_inner($routes, 'access');

$xml->asXML('sitemap.xml');