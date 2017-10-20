<?php 
require_once 'usuario.php';
require_once 'sessao.php';
require_once 'autenticador.php';
include_once '../../inc/config.php';
include_once '../../inc/conexao.php';
$aut = Autenticador::instanciar();

$usuario = null;
if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
}
else {
    $aut->expulsar();
}
echo '
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="favico.ico">

    <title>Frequência - Enepet 2017</title>


</head>

<body>';

$cpf = isset($_GET['cpf'])?$_GET['cpf']:null;
if($cpf)
{
	
	if( !validaCPF($cpf) ){
	
		echo '<div>
					  <strong>Opa!</strong> Campos vazios. Tente novamente.
					</div>';
		exit(0);
	}
	$local = $usuario->getArea();
	if(empty($local)){

		echo '<div>
					  <strong>Opa!</strong> Dispositivo não autorizado. 
					</div>';
		exit(0);
	}	
	$rx = $con->prepare("SELECT cpf,local FROM `frequencia` where `cpf` LIKE ? and `local` LIKE ?");
	$rx->bindParam(1, $cpf);
	$rx->bindParam(2, $local);
	$rx->execute();
	if($rx->rowCount() > 0)
	{

		while($arq3 = $rx->fetch(PDO::FETCH_OBJ))
		{
			echo '<div>
				  <strong>Opa!</strong> Presença já marcada. <hr>CPF: '.$arq3->cpf.' <hr>Local: '.$arq3->local.'
				</div>';
		}
		exit(0);
	
	}else
	{
	
		$stmt = $con->prepare("INSERT INTO `frequencia` VALUES (NULL,?,?,CURRENT_TIMESTAMP)");
		$stmt->bindParam(1,$cpf);
		$stmt->bindParam(2,$local);
		if($stmt->execute())
		{
			echo '<div>
					  <strong>Opa!</strong> Presença feita.
				</div>';
		}else
		{
			echo '<div>
					  <strong>Opa!</strong> Erro ao marcar frequência. Tente novamente.
				</div>';
			exit(0);
		}
	}
						
	$rt = $con->prepare("SELECT nome,email,pet FROM inscricoes where cpf=?");
	$rt->bindParam(1,$cpf);
	
	if($rt->execute()){
		 //enviar e-mail de confirmação para o inscrito.

			if($rt->rowCount() == 0 || $rt->rowCount() > 1 ){
			
				echo  '<div>
							  <strong>Opa!</strong> Erro. No banco de dados. CPF não consta ou CPF duplicado. Contactar o ADM.
							</div>';
				exit(0);
			}else{
		
				$arq2 = $rt->fetch(PDO::FETCH_OBJ);
				$nome = $arq2->nome;
				$email = $arq2->email;
				$pet = $arq2->pet;
				
			}
	}else{
	
		echo  '<div>
					  <strong>Opa!</strong> Erro. No banco de dados. Contactar o ADM.
					</div>';
		exit(0);
	
	}
	
	require '../../PHPMailer-master/PHPMailerAutoload.php';
	
	$email_subject = 'Presença efetuada em '.$local.'!';
	//Trabalho aprovado
	$texto = utf8_decode("<html><body><p>Olá ". $nome . ", do ".$pet."! Muito obrigado por marcar presença aqui. </a><br><br><hr>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.</p></body></html>");
	
	$assunto = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
	//$name = utf8_decode($name);
	
	
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
	$mail->Username = "contato@enepet2017.com";
	//Password to use for SMTP authentication
	$mail->Password = "@LAmed@6";
	//Set an alternative reply-to address
	$mail->addReplyTo('ouvidoria.enepet2017@outlook.com', 'Ouvidoria ENEPET 2017');
	//Set who the message is to be sent to
	//$mail->addAddress($email_address, $name);
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($texto);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'Esta é uma mensagem de confirmação de trabalho';
	//Attach an image file
	//$mail->addAttachment('../img/cidade.jpg');
	$mail->setFrom('contato@enepet2017.com', 'CONTATO ENEPET 2017');
	
		//Set the subject line
		$mail->Subject = $assunto;
		$mail->addAddress($email);

		if (!$mail->send()) 
		{
			echo '<div>
				  <strong>Erro '.$email.' Error: ' . $mail->ErrorInfo.'.
				</div>';
			exit(0);
		} 
		else 
		{
			echo '<div>
				  <strong>Enviado!</strong>'.$email.'.
				</div>';
			exit(0);
		}
	
}

echo '
</body>

</html>';

?>

