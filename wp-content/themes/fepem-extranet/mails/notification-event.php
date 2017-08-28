<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta name="viewport" content="width=device-width" />

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Notification nouvel événement déposé</title>
        <style type="text/css">
            .ExternalClass {
              width:100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
              line-height: 100%;
            }

            * {
              margin:0;
              padding:0;
              font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }
            body {
              width: 100%!important;
              height: 100%;
              -webkit-font-smoothing:antialiased;
              -webkit-text-size-adjust:none;
              -ms-text-size-adjust:none;
            }
            img {
              max-width: 100%;
            }
            table td {
              border-collapse:collapse;
            }
            p {
              margin:0;
              padding:0;
              margin-bottom:0;
            }

            h1, h2, h3, h4, h5, h6 {
              color: black;
              line-height: 100%;
            }
            a, a:link {
              color:#2A5DB0;
              text-decoration: underline;
            }
            a:visited {
              color: #3c96e2;
              text-decoration: none
            }
            a:focus {
              color: #3c96e2;
              text-decoration: underline
            }
            a:hover {
              color: #3c96e2;
              text-decoration: underline
            }
            @media only screen and (max-width: 600px) {
              a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
            }

        </style>
    </head>

    <body style="background:#fff; height: 100%; width:100%; color:#000; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:12px"
    bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
    <!-- HEADER -->
        <table style="width:100%;background-color:#EE7F01" bgcolor="#EE7F01" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td></td>
                <td style="display:block;max-width:600px;margin:0 auto;clear:both;" align="">
                    <div style="padding:15px;max-width:600px;margin:0 auto;display:block;" >
                        <table style="width:100%;" bgcolor="#EE7F01" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <img src="http://placehold.it/200x50/" alt="" title="" width="200" height="50" style="display:block"  border="0"/>
                                </td>
                                <td align="right">
                                    <h6 style="font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;line-height: 1.1; margin-top:0;margin-bottom:0; padding: 0;">
                                    Notification
                                    </h6>
                                </td>
                            </tr>
                        </table>
                    </div><!-- /content -->
                </td>
                <td></td>
            </tr>
        </table><!-- /HEADER -->

        <!-- MAIN CONTENT -->
        <table style="width:100%;" bgcolor="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td></td>
                <td style="display:block;max-width:600px;margin:0 auto;clear:both;" align="" bgcolor="#FFFFFF">
                    <!-- content -->
                    <div style="padding:15px;max-width:600px;margin:0 auto;display:block;">
                        <table style="width:100%;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <h4 style="font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight: 500; font-size: 23px; color:#000; line-height: 1.1; margin-top:0; margin-bottom:15px;">
                                        <?php echo $post->post_title; ?>
                                        
                                    </h4>
                                    <p style="margin-top:0;margin-bottom: 10px;font-weight: normal;line-height:1.6;font-size:14px;">
                                        <?php echo $post->post_content; ?>
                                    </p>
                                    <a class="btn" href="<?php echo get_permalink( $post->ID ); ?>"
                                            style="text-decoration:none;color: #FFF;background-color: #666;padding:10px 16px;font-weight:bold;margin-right:10px;text-align:center;cursor:pointer;display: inline-block;">
                                        Lire la suite &raquo;
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div><!-- /content -->
                </td>
                <td></td>
            </tr>
        </table><!-- /BODY -->

        <!-- FOOTER -->
        <table style="width:100%;clear:both;" bgcolor="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td></td>
                <td style="display:block;max-width:600px;margin:0 auto;clear:both;">
                    <!-- content -->
                    <div style="max-width:600px;margin:0 auto;display:block;">
                        <table style="width:100%;" bgcolor="" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center">
                                    <p style="margin-top:0;margin-bottom: 10px;padding-top:15px; font-weight: normal;line-height:1.6; font-size:10px;border-top: 1px solid rgb(215,215,215); ">
                                    text
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div><!-- /content -->
                </td>
                <td></td>
            </tr>
        </table><!-- /FOOTER -->

    </body>
</html>
