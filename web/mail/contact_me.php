<?php

include_once '../inc/config.php';
// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
/*	
// Create the email and send the message
$to = $emailOuvidoria; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Visitante do ENEPET 2017:  $name";
$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
$name = $name;
$email_body = "Você recebeu uma nova mensagem enviada pelo site do ENEPET 2017.\n\nAqui estão os detalhes:\n\n";
$email_body .= "Nome: $name\n\nEmail: $email_address\n\nTelefone: $phone\n\nMensagem:\n$message";
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

$headers .= "From: contato@enepet2017.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";	

*/

require '../PHPMailer-master/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Set the hostname of the mail server
$mail->Host = "smtp.uhserver.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
$mail->Charset   = 'utf8_decode()';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "noreply@enepet2017.com";
//Password to use for SMTP authentication
$mail->Password = "@LAmed@6";
//Set who the message is to be sent from
$mail->setFrom('noreply@enepet2017.com', 'ENEPET 2017');
//Set an alternative reply-to address
$mail->addReplyTo($email_address, $name);
//Set who the message is to be sent to
$mail->addAddress('ouvidoria.enepet2017@outlook.com', 'Ouvidoria ENEPET 2017');
//Set the subject line
$mail->Subject = 'Visitante ENEPET 2017';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$email_html = utf8_decode("Você recebeu uma nova mensagem enviada pelo site do ENEPET 2017.<br /><br />Aqui estão os detalhes:<br /><br />");
$email_html .= utf8_decode("Nome: $name<br />Email: $email_address<br />Telefone: $phone<br />Mensagem:<br />$message");
$mail->msgHTML($email_html);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('../img/cidade.jpg');

//send the message, check for errors
if (!$mail->send()) {
    return false;
} else {
    return true;
}
//mail($to,$email_subject,$email_body,$headers);
return true;			
?>