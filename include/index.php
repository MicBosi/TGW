<?php

require("config.php");
require("core.php");

global $base_website_address;
global $content_file;

$content_file = isset($_GET['data']) ? $_GET['data'] : "index";

$content_file = strtolower($content_file);

$content_path = "contents/".$content_file.".php";

// contents/discografia-canzoni/spartiti-chitarra-testi-accordi-timoria.php

// Check existence
if (file_exists($content_path) == false) {
    header("HTTP/1.0 404 Not Found");
    echo "$content_path<br>";
    $content_path = "errors/404.php";
}

$header = extract_header_info($content_path);
$template_directory = "template/".(isset($header['template'])?$header['template']:"default");
