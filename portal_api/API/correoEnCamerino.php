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
    $file = json_decode(file_get_contents('php://input'), true);

    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8"); //para visualizar acentos
    if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
    }

    $sql = "select abv, correo, concat(nombres,' ',apellidoPaterno,' ',apellidoMaterno) nombre from local inner join usuario on cveLocal = idLocal where idUsuario = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $file["numero"]);
    $stmt->execute();
    $result = $stmt->get_result();

if ($result->num_rows > 0) {
    $consulta = $result->fetch_assoc();

    // Instanciar el objeto PHPMailer
    $mail = new PHPMailer(true);
    

    try {
    // Configuración del servidor de correo
    //$mail->SMTPDebug = 2;  
    $mail->isSMTP();
    $mail->Host = 'mail.comunicadosaraiza.com';
    $mail->SMTPAuth = true;
    $mail->Username = '_mainaccount@comunicadosaraiza.com';
    $mail->Password = 'f[*w4xFx$#u)Zl';
    $mail->SMTPSecure = 'tls'; 
    $mail->Port = 587;
    
    $message = "<!DOCTYPE html>
    <html lang=\"es\">
    <head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Sugerencia para Mejoras</title>
    </head>
    <body>
      <div style=\"font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;\">
        <div style=\"max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);\">
          <div style=\"text-align: center; margin-bottom: 20px;\">
            <img src=\"https://www.comunicadosaraiza.com/portal_api/API/images_correo/logo_gigante.png\" alt=\"Logo\" width='50' height='50';\">
            <h1>Votación</h1>
          </div>
          <div style=\"font-size: 16px; line-height: 1.6; margin-bottom: 20px;\">
            <p>¡Buenos días!</p>
            <blockquote>
              <p>".$file['sugerencia']."</p>
            </blockquote>
          </div>
          <div style=\"text-align: center; font-size: 14px; color: #999;\">
            <p>Este correo electrónico fue enviado por ".$consulta["nombre"]." ( ".$consulta["correo"]." ) </p>
          </div>
        </div>
      </div>
    </body>
    </html> ";

    // Establecer el remitente y el destinatario
    $mail->setFrom('auxcomunicacion@araizahoteles.com', 'Portal de Comunicación');
    $mail->addAddress($file['correo']);
    //'lineadeapoyo@araizahoteles.com'$file["correo"]
    $mail->CharSet = "UTF-8";
    // Configurar el contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $consulta["abv"].' - Votaciones';
    $mail->Body = $message;

    // Enviar el correo
    $mail->send();
    } catch (Exception $e) {

    }
}
   
?>