<?php require("include/index.php"); 
/*
	TODO:
	- 1) White Theme
	- 2) JS ascii to high-quality picture converter (chords & scales & tabs)
		--> http://www.wikihow.com/Play-Guitar-Chords
	- 3) Fix lame copy
	- 4) Live chord/hand-position photos
	- 5) Responsive optimized theme
	- progressions
	- canzoni famose
	- comments
	- aggiustare struttura menu/quick-menu
	- megatabs
	- Update graphics:
		==|> JS library to render chords and scales.

		* Chords and scales generated via JS/Canvas/SVG (ex. http://chordography.blogspot.co.uk, http://einaregilsson.com/chord-image-generator)
			* Traverse all canvas objects with 'chord' class and read "data-" fields for parameters.
			* Library of preset chords.
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
    <body bgcolor="#000000" text="#ffffff">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #333333; margin-bottom: 5px">
            <tbody>
                <tr>
                    <td>
                        <center>
                            <h1 id="tgw-title">
                                <a href="<?php echo "$base_website_address"; ?>/" title="Lezioni di chitarra, guide di teoria e tecnica, spartiti, testi e accordi di canzoni">The Guitar Wizard</a>
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
		
<!-- content -->
		<?php require("$template_directory/index.php"); ?>

<!-- footer -->
		<div style="padding: 5px; margin: 4em 0 1em 0; font-family: 'IM Fell English SC', serif; font-size: 16px; text-align: center;">
			<a href="<?=$base_website_address;?>/propositi.html">&copy; Copyright 2000-<?php echo date("Y") ?> Michele Bosi</a> <br>
			Some Rights Are Reserved.
		</div>

		<script src="js/jquery-1.12.0.min.js"></script>
		<script type="text/javascript">
			var chord_view = $(".chord-view")[0];
			var ascii = $(chord_view).text();
			console.log(ascii);
			var canvas = document.createElement('canvas');
			var padding_w = 80;
			var padding_h = 40;
			var key_w = 80;
			var key_h = 40;

			var canvas_w = key_w * 5 + padding_w * 2;;
			var canvas_h = key_h * 5 + padding_h * 2;

			var neck_w = key_w * 5 + padding_w;
			var neck_h = key_h * 5;

			canvas.width=canvas_w;
			canvas.height=canvas_h;
			// canvas.style.border="1px solid gray";
			$(chord_view).html('');
			chord_view.appendChild(canvas);

			var ctx = canvas.getContext("2d");

			// draw strings
			ctx.strokeStyle="#000000";
			ctx.lineWidth="1";

			// draw background
			ctx.fillStyle = "#EEEEEE";
			ctx.fillRect(0, 0, canvas_w, canvas_h);
			ctx.strokeRect(0.5, 0.5, canvas_w-1, canvas_h-1);
			
			// draw guitar neck
			ctx.fillStyle = "#EEEEAA";
			ctx.fillRect(padding_w, padding_h, neck_w, neck_h);
			ctx.strokeRect(padding_w+0.5, padding_h+0.5, neck_w-1, neck_h-1);
			
			// draw strings
			var y = padding_h + 0.5;
			for(var i=0; i<5; ++i) {
				ctx.beginPath();
				ctx.moveTo(padding_w, y);
				ctx.lineTo(canvas_w, y);
				ctx.stroke();
				y += neck_h/5;
				console.log(y);
			}

			ctx.strokeStyle="#000000";
			ctx.lineWidth="1";
			var x = padding_w + 0.5;
			for(var i=0; i<5; ++i) {
				ctx.beginPath();
				ctx.moveTo(x, padding_h);
				ctx.lineTo(x, padding_h + neck_h);
				ctx.stroke();
				x += neck_w/5;
				console.log(x);
			}

			// draw nut
			ctx.strokeStyle="#000000";
			ctx.lineWidth="8";
			ctx.beginPath();
			ctx.lineCap="round";
			ctx.moveTo(padding_w, padding_h);
			ctx.lineTo(padding_w, padding_h + neck_h);
			ctx.stroke();
		</script>

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
