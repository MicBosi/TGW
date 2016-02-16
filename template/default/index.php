<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td valign=top class="content_td">
                <div id="default-content">
                    <?php require($content_path); ?>
                    
                    <div style="margin-top: 2em; text-align: center;">
                        <div class="fb-like" data-href="<?php echo $canonical_url; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                    </div>

                    <div style="text-align: center; margin-top: 2em;">
                        <div style="display: inline-block; border: 1px solid gold; padding: 0em 1em;">
                            <h2 class="back-link" style="margin: 0; padding: 0;"><a href="<?php echo $base_website_address; ?>" target="_top">Torna al menù principale</a></h2>
                        </div>
                    </div>
                </div>
            </td>
            <td valign=top width=200 style="border: 1px solid gray; padding: 4px">
                <?php require("contents/quickindex.php"); ?>
            </td>
        </tr>
    </tbody>
</table>