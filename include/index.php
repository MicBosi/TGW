<?php
////////////////////////////////////////////////////////////////////////////////
// metti i contenuti in una directory a parte inaccessibile a Google and co.
////////////////////////////////////////////////////////////////////////////////

require("core.php");

global $base_website_address;
global $content_file;

$content_file = isset($_GET['data']) ? $_GET['data'] : "index";

// Needed?
$content_file = strtolower($content_file);

// Redirect
if (strpos($content_file, "spartiti-testi-canzoni/spartiti-testi-discografia") === 0)
{
  $content_file = str_replace("spartiti-testi-canzoni/spartiti-testi-discografia", "discografia-canzoni/spartiti-chitarra-testi-accordi", $content_file);
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: $base_website_address/$content_file.html");
  return;
}
else
if (strpos($content_file, "spartiti-testi-canzoni/index") === 0)
{
  // echo "$base_website_address/discografia-canzoni/index.html";
  // $content_file = "discografia-canzoni/index";
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: $base_website_address/discografia-canzoni/index.html");
  return;
}

$content_path = "contents/".$content_file.".php";

// contents/discografia-canzoni/spartiti-chitarra-testi-accordi-timoria.php

// Check existence
if ( file_exists($content_path) == false )
{
  header("HTTP/1.0 404 Not Found");
  echo "$content_path<br>";
  $content_path = "errors/404.php";
}


$header = extract_header_info($content_path);
$template_directory = "template/".(isset($header['template'])?$header['template']:"default");

require("$template_directory/index.php");

?>

  <div style="padding: 5px; margin: 4em 0 1em 0;">
    <small>
      <a href="https://michelebosi.com" style="font-family: Verdana;">&copy; Copyright 2000-<?php echo date("Y") ?> Michele Bosi</a>
    </small>
  </div>
  
  <?php if ($activate_google_analitics) { ?>

  <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
  </script>
  <script type="text/javascript">
  _uacct = "UA-1058203-4";
  urchinTracker();
  </script>

  <?php } ?>

  </body>
</html>
