<?php

global $base_website_address;
global $website_title;
global $rss_description;

global $links_page_description;
global $faq_page_description;
global $faq_answer_page_description;
global $glossary_page_description;
global $glossary_answer_page_description;
global $content_dir;
global $activate_google_analitics;
global $spartiti_dir;
global $cfg_dir;

$base_website_address = $_SERVER['SERVER_NAME'] == 'localhost' ? "/TGW" : '';
$website_title = "The Guitar Wizard";
$contents_dir = __DIR__."/../contents";
$cfg_dir = __DIR__."/cfg";
$spartiti_dir = "discografia-canzoni";
$rss_description = "The Guitar Wizard";
$activate_google_analitics = false; // $_SERVER['SERVER_NAME'] != 'localhost';
$enable_adsense = true;

$links_page_description = "Only the best websites from internet";
$faq_page_description = "A quick answer to all your questions";
$faq_answer_page_description = "Frequently Asked Questions";
$glossary_page_description = "Because knowledge is power!";
$glossary_answer_page_description = "Frequently Asked Questions";
$sitemap_page_description = "Shows briefly the content of the website";
