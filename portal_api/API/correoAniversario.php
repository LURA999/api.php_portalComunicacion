<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__.'/PHPMailer/src/Exception.php';
require __DIR__.'/PHPMailer/src/PHPMailer.php';
require __DIR__.'/PHPMailer/src/SMTP.php';

$servername = "localhost";
$dbname = "comunica_portalComunicacion";
$username = "comunica_prueba";
$password = "@o)K!+#[AT]#";


$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8"); //para visualizar acentos
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT usuario,nombres ,TIMESTAMPDIFF(Year, fechaIngreso, CURRENT_DATE()) años_transcurridos,fechaIngreso,apellidoPaterno,apellidoMaterno,correo FROM usuario 
where MONTH(fechaIngreso) = MONTH(CURRENT_DATE()) and DAY(fechaIngreso) = DAY(CURRENT_DATE()) having años_transcurridos > 0 ";

/*
$sql = "SELECT usuario,nombres ,TIMESTAMPDIFF(Year, fechaIngreso, CURRENT_DATE()) años_transcurridos,fechaIngreso,apellidoPaterno,apellidoMaterno,correo FROM usuario 
where usuario = 4083 ";
*/

$result = $conn->query($sql);

while ($fila = $result->fetch_assoc()) {
    
    // Instanciar el objeto PHPMailer
    $mail = new PHPMailer(true);
    
    
    try {
    // Configuración del servidor de correo
    $mail->SMTPDebug = 2;  
    $mail->isSMTP();
    $mail->Host = 'mail.comunicadosaraiza.com';
    $mail->SMTPAuth = true;
    $mail->Username = '_mainaccount@comunicadosaraiza.com';
    $mail->Password = 'f[*w4xFx$#u)Zl';
    $mail->SMTPSecure = 'tls'; 
    $mail->Port = 587;
    
    $message = "
    <html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:o=\"urn:schemas-microsoft-com:office:office\">
    <head>
    <!--[if gte mso 9]>
    <xml>
      <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
      <meta name=\"x-apple-disable-message-reformatting\">
      <!--[if !mso]><!--><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
      <!--<![endif]-->
      <title>Cumple</title>
        </head>
    
    <body style=\"width:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:tahoma, verdana, segoe, sans-serif;padding:0;Margin:0;background-color:#E8E8E4\">
    <br/>
    <div class=\"es-wrapper-color\" >
    <table class=\"es-wrapper\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#E8E8E4\">
    <tr style=\"border-collapse:collapse\"><td valign=\"top\" style=\"padding:0;Margin:0\">
    <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%\">
    <tr style=\"border-collapse:collapse\"><td class=\"es-adaptive\" align=\"center\" style=\"padding:0;Margin:0\">
    <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#fafcfb;width:600px\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fafcfb\" align=\"center\"><tr style=\"border-collapse:collapse\"><td align=\"left\" style=\"padding:0;Margin:0\">
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"><tr style=\"border-collapse:collapse\">
    <td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:600px\">
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\"><tr style=\"border-collapse:collapse\">
    <td style=\"padding:0;Margin:0;position:relative\" align=\"center\">
    <img class=\"adapt-img\" src=\"https://www.comunicadosaraiza.com/portal_api/API/images_correo/feliz_aniv.png\" alt title width=\"600\" height=\"286\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic\"></td>
    </tr><tr style=\"border-collapse:collapse\"><td class=\"es-m-txt-c\" align=\"center\" style=\"padding:0;Margin:0;padding-left:30px;padding-right:30px;padding-top:40px\"><h3 style=\"Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#666666\"><br></h3></td></tr>
    <tr style=\"border-collapse:collapse\"><td class=\"es-m-txt-c\" esdev-links-color=\"#ffffff\" align=\"center\" style=\"Margin:0;padding-top:5px;padding-bottom:15px;padding-left:30px;padding-right:30px\"><h2 style=\"Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#ca9d5d\">
    <strong>¡Feliz Aniversario<br>".$fila['nombres']." ".$fila['apellidoPaterno']." ".$fila['apellidoMaterno']."!</strong></h2></td></tr>
    </table></td></tr></table></td>
    </tr><tr style=\"border-collapse:collapse\"><td align=\"left\" style=\"padding:0;Margin:0;padding-left:30px;padding-right:30px;padding-bottom:40px\">
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\">
    <tr style=\"border-collapse:collapse\"><td valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;width:540px\">
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px\">
    <tr style=\"border-collapse:collapse\"><td class=\"es-m-txt-c\" align=\"center\" style=\"padding:0;Margin:0\">
    <p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;line-height:21px;color:#000000;font-size:14px\">
    Te felicitamos por tu increíble trayectoria. Igualmente<br>te agredecemos por tu lealtad y perseverancia en<br>Hotel araiza. Por ti, seguimos creciendo y logrando<br>
    nuevas metas.&nbsp;</p>
    </td></tr>
    <tr style=\"border-collapse:collapse\"><td class=\"es-m-txt-c\" align=\"center\" style=\"padding:0;Margin:0;padding-top:20px\">
    <p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;line-height:21px;color:#000000;font-size:14px\">
    Sinceramente,<br><strong>Grupo Araiza</strong> <br/><br/>
    <a href='https://www.comunicadosaraiza.com'> Visita nuestro portal de comunicación</a>
    </p></td></tr>
    </table></td></tr></table></td></tr></table></td></tr></table></td></tr>
    </table></div></body>
    
    </html>
    ";
    // Establecer el remitente y el destinatario
    $mail->setFrom('comunicacionrh@araizahoteles.com', 'Portal de Comunicación');
    $mail->addAddress($fila['correo']);
    $mail->CharSet = "UTF-8";
    // Configurar el contenido del correo
    $mail->isHTML(true);
    $mail->Subject = '¡Araiza te desea un feliz aniversario!';
    $mail->Body = $message;

    // Enviar el correo
    $mail->send();
    echo 'El correo se envió correctamente.';
    } catch (Exception $e) {
    echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
    
    }

}


?>