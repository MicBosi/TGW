<?php require("include/index.php"); 
/*
	TODO:
	- 0) Intelligent JS tab renderer
	- 1) Disqus comments
	- 2) Ripensa tutto il contenuto
		- how to tune guitar
		- how to change strings
		- video
		- foto
		- esempi: canzoni, progressioni, strumming, ritmi
		- tools:
			- song chords printer (create your songbook)
			- song transposer
			- chord progression editor/player
	- 3) Live chord/hand-position photos
	- 2) Fix lame copy
	- 4) Responsive optimized theme
	- progressions
	- canzoni famose
	- aggiustare struttura menu/quick-menu
	- megatabs
	* Accordi (photo, schemi, tabs, audio, video).
	* Strumming (video, tabs).
	* Chord transposer.
	* Scale (box, tabs, video, audio) - Again using JS.
	* Chord progression builder, interactive.
		* Play/save/share songs.
	- Divisione teoria/pratica.
	- Video progressioni/accordi.
	- FB comments.
	- Copy cleanup.
*/
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<title><?php echo $header['title']; ?></title>
		<meta name="description" content="<?php echo $header['description']; ?>" >
		<meta name="keywords" content="<?php echo $header['keywords']; ?>" />
		<meta name="author" content="<?php echo $header['author']; ?>" />
		<meta name="copyright" content="&copy; 2000-2016 Michele Bosi">
		<meta name="robots" content="index, follow" />
		<link rel="shortcut icon" href="<?php echo "$base_website_address/favicon.ico" ?>" />
		<link href="<?php echo "$base_website_address/css/style.css" ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo "$base_website_address/$template_directory/stylesheet.css" ?>" rel="stylesheet" type="text/css" />
		<meta name="google-site-verification" content="kdEdtblSQTOF5ty2Dhm7aBe7cus0NBMrxn9ky1d-OX4" />
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 5px">
            <tbody>
                <tr>
                    <td>
                        <center>
                            <h1 id="tgw-title"> <a href="<?php echo "$base_website_address"; ?>/" title="Lezioni di chitarra, guide di teoria e tecnica, spartiti, testi e accordi di canzoni">The Guitar Wizard</a></h1>
                            <h2>Scopri La Magia Di Suonare Chitarra</h2>
                        </center>
                    </td>
                </tr>
            </tbody>
        </table>
		
		<hr width="50%">

<!-- content -->
		<?php require("$template_directory/index.php"); ?>

<!-- footer -->
		<div style="padding: 5px; margin: 8em 0 1em 0; font-family: 'IM Fell English SC', serif; font-size: 16px; text-align: center;">
			<a href="<?=$base_website_address;?>/propositi.html">&copy; Copyright 2000-<?php echo date("Y") ?> Michele Bosi</a> <br>
			Some Rights Reserved.
		</div>

		<script src="js/jquery-1.12.0.min.js"></script>
		<script src="js/tgw.js"></script>

		<?php if ($activate_google_analitics) { ?>
			<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', 'UA-1058203-4', 'auto');
			  ga('send', 'pageview');

			</script>
		<?php } ?>

	</body>
</html>
