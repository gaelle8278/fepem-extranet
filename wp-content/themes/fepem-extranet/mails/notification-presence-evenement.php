<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta name="viewport" content="width=device-width" />

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Extranet - Confirmation présence à un événement</title>
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

        <!-- MAIN CONTENT -->
        <table style="width:100%;" bgcolor="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td></td>
                <td style="display:block;max-width:600px;margin:0 auto;clear:both;" align="" bgcolor="#FFFFFF">
                    <!-- content -->
                    <div style="padding:15px;max-width:600px;margin:0 auto;display:block;">
                        <p style="margin-top:0;margin-bottom: 10px;font-weight: normal;line-height:1.6;font-size:14px;">
                            Bonjour,<br>
                                <br>
                            Le membre <?php echo $user->first_name." ".$user->last_name; ?> a confirmé sa présence à l'événement "<?php echo $event->post_title; ?>".
                        </p>
                        <?php
                        if(!empty($message)) {
                            ?>
                            <p style="margin-top:0;margin-bottom: 10px;font-weight: normal;line-height:1.6;font-size:14px;">
                                Voici le message transmis  par le membre :<br><br>
                                <?php echo $message; ?>
                            </p>
                            <?php
                        }
                        ?>
                        <p style="margin-top:0;margin-bottom: 10px;font-weight: normal;line-height:1.6;font-size:14px;">
                            Cordialement
                        </p>
                    </div><!-- /content -->
                </td>
                <td></td>
            </tr>
        </table><!-- /BODY -->

        <!-- FOOTER -->
    </body>
</html>


