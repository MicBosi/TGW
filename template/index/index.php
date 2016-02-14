<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <?php require("header.php"); ?>
  </head>
  <body bgcolor="#000000" text="#ffffff">
  <table background="<?php echo "$base_website_address/images/save1.jpg"; ?>" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #333333; margin-bottom: 5px">
    <tbody>
      <tr>
        <td>
          <center>
            <H1 style="padding: 0px; margin: 0px; font-weight: normal; font-size: 16px">
            <font face="Georgia, Bedford" size="7" color="#fff080">
              <a href="<?php echo "$base_website_address"; ?>" title="Lezioni di chitarra, guide di teoria e tecnica, spartiti, testi e accordi di canzoni"> The Guitar Wizard </a>
            </font>
            </H1>
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
  <?php require($content_path); ?>
