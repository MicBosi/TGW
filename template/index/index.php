<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta name="verify-v1" content="q26IULzsKu98yAKKGFniGXltHdKFbrVqc87El7T318Q=" />
  <?php require("header.php"); ?>
  </head>
  <body bgcolor="#000000" text="#ffffff">
  <table background="<?php echo "http://$base_website_address/images/save1.jpg"; ?>" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #333333; margin-bottom: 5px">
    <tbody>
      <tr>
        <td>
          <center>
            <H1 style="padding: 0px; margin: 0px; font-weight: normal; font-size: 16px">
            <font face="Georgia, Bedford" size="7" color="#fff080">
              <a href="<?php echo "http://$base_website_address"; ?>" title="Lezioni di chitarra, guide di teoria e tecnica, spartiti, testi e accordi di canzoni"> The Guitar Wizard </a>
            </font>
            </H1>
          </center>
        </td>
        <td align=right width=1%>
          <a href="http://<?php echo $base_website_address; ?>/index_eng.html">
          <img border=0 style="border: 1px solid black" src="http://<?php echo $base_website_address; ?>/images/en.gif"/>
          </a>
          <a href="http://<?php echo $base_website_address; ?>/index.html">
          <img border=0 style="border: 1px solid black" src="http://<?php echo $base_website_address; ?>/images/it.gif"/>
          </a>
        </td>
      </tr>
    </tbody>
  </table>
  
  <center>
  <?php require($content_path); ?>
  
  <div style="padding: 5px"><span style="font-family: Verdana; font-size:10px">&copy; Copyright 2000-2007 Michele Bosi. Tutti i diritti sono riservati.</span></div>

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
