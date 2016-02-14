<?php 
	require("config.php"); 
	require("include/index.php"); 
?>
<!-- header -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<title><?php echo $header['title']; ?></title>
		<meta name="description" content="<?php echo $header['description']; ?>" >
		<meta name="keywords" content="<?php echo $header['keywords']; ?>" />
		<meta name="author" content="<?php echo $header['author']; ?>" />
		<meta name="copyright" content="&copy; 2000-2016 Michele Bosi">
		<meta name="robots" content="index, follow" />
		<link rel="shortcut icon" href="<?php echo "$base_website_address/favicon.ico" ?>" />
		<link href="<?php echo "$base_website_address/css/style.css" ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo "$base_website_address/$template_directory/stylesheet.css" ?>" rel="stylesheet" type="text/css" />
    </head>
    <body bgcolor="#000000" text="#ffffff">
        <table background="<?php echo "$base_website_address/images/save1.jpg"; ?>" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #333333; margin-bottom: 5px">
            <tbody>
                <tr>
                    <td>
                        <center>
                            <h1 id="tgw-title">
                                <a href="<?php echo "$base_website_address"; ?>" title="Lezioni di chitarra, guide di teoria e tecnica, spartiti, testi e accordi di canzoni"> The Guitar Wizard</a>
                            </h1>
                        </center>
                    </td>
                    <td align=right width=1%>
                        <a href="<?php echo $base_website_address; ?>/index_eng.html">
                        <img border=0 style="border: 1px solid black" src="<?php echo $base_website_address; ?>/images/en.gif"/>
                        </a>
                        <a href="<?php echo $base_website_address; ?>/index.html">
                        <img border=0 style="border: 1px solid black" src="<?php echo $base_website_address; ?>/images/it.gif"/>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <center>
<?php 
	require("$template_directory/index.php");
	/*
	TODO:
	- unificazione headers and basic CSS
	- unificazione font styles
	- update title font
	- shrink "pre" tablature text
	- refactor this index.php
	- update GA code
	- update AdSense code
	*/
?>
		<!-- footer -->
		<div style="padding: 5px; margin: 4em 0 1em 0; font-family: 'IM Fell English SC', serif; font-size: 16px;">
			<a href="https://michelebosi.com">&copy; Copyright 2000-<?php echo date("Y") ?> Michele Bosi</a>
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
