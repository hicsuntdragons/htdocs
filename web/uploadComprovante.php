<?php


include_once 'inc/conexao.php';
include_once 'inc/config.php';


	if(!empty($_POST['cpf']) && 
		!empty($_FILES['comprovante']['tmp_name']))
	{

		//SE TIVER ARQUIVO
		$ccpf = validaCPF(retirar_letras_e_pontos($_POST['cpf']))?retirar_letras_e_pontos($_POST['cpf']):'';
		$rs = $con->prepare("SELECT * FROM inscricoes WHERE cpf = ? limit 1");
		$rs->bindParam(1, $ccpf);

		//verificar se o usuário existe na lista dos alunos
		if($rs->execute()){
			if($rs->rowCount() == 0){
				echo 'cpf';
			}else{
		
				//USUÁRIO EXISTE E ESTÁ CADASTRADO
				//EXIBIR DADOS DO USUÁRIO
				$arquivos = $rs->fetch(PDO::FETCH_OBJ);
				$rs = null;
				session_start();
				// verificar se o usuário está logado.
				if($arquivos->session_id != session_id() || $arquivos->cpf != $_SESSION['enepet2017_cpf']){
					#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
					 
					echo 'erro';
					 session_destroy();
					 exit(0);
				}
				if($arquivos->pago){ echo 'Opa! Comprovante já enviado.'; exit(0);}
				
				if($_FILES['comprovante']['type'] != 'image/png' && $_FILES['comprovante']['type'] != 'image/jpeg' && $_FILES['comprovante']['type'] != 'application/pdf'){
					//print_r($_FILES);
					echo 'naoeimg';
					exit(0);
				}
				
				//$links = json_decode($arquivos->link);
				
				$diretorio = 'admin/pages/ComprovantesPagamento/'.$arquivos->cpf.'/';
				$lista_comprov = glob("$diretorio{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG}", GLOB_BRACE);
				if(!is_dir($diretorio))mkdir($diretorio);
				$novo_nome = $arquivos->cpf;
				$caminho = $_FILES['comprovante']['tmp_name'];
				$caminho2 = $_FILES['comprovante']['name'];
				$infos = pathinfo($caminho2);
				$nome_arquivo = 'comprovante_'. $novo_nome.'_'.(count($lista_comprov)+1).'.'.$infos['extension'];
				//$link = $nome_arquivo;
				$arquivo = $diretorio.$nome_arquivo;
				//move_uploaded_file($value, $nome_original)
				if(!move_uploaded_file($caminho, $arquivo)){echo 'naoinserido'; exit(0);}
				//$links['comprovante'] = $nome_arquivo;
				//$autor = $usuario->getNome();
				$infos = pathinfo($arquivo);
				
				//$link = json_encode($links);
				// $stmt = $con->prepare("INSERT INTO `trabalhos_cientificos` VALUES ('',?,?,?,?,?,0,0,CURRENT_TIMESTAMP,'',?,?)");
				// $stmt->bindParam(1,$titulo);
				// $stmt->bindParam(2,$arquivos->cpf); 
				// $stmt->bindParam(3,$autor2);
				// $stmt->bindParam(4,$autor3);
				// $stmt->bindParam(5,$autor4);
				// $stmt->bindParam(6,$_POST['area']);
				// $stmt->bindParam(7,$link);
				$stmt = $con->prepare("UPDATE `inscricoes` SET pago=1 WHERE cpf=?");
				$stmt->bindParam(1,$arquivos->cpf);
				
				if($stmt->execute())
				{
					echo 'sucesso';
		
					$name = $arquivos->nome;
					$email_address = $arquivos->email;
					$nome_pet = $arquivos->pet;
					// Create the email and send the message
					//$to = $email_address; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
					$email_subject = $name.', seu comprovante de pagamento foi enviado.';
					$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
					//$name = utf8_decode($name);
					$email_body = utf8_decode("<html><body><p>Olá! Acabamos de receber seu comprovante de pagamento. <br><br>Aqui estão os detalhes da sua inscrição:<br><br>Nome: $name<br>Email: $email_address<br>$nome_pet<br><br>Logo em breve enviaremos a confirmação do seu pagamento. Fique atento às datas. Todas as informações a respeito da submissão de trabalhos estão <a href=\"http://www.enepet2017.com/?p=submissao\">AQUI</a>: datas importantes, modelos de resumo e banner.<br><br>Submeter trabalhos, pagar a inscrição, verificar o status de seus trabalhos e de sua inscrição, escolher atividades científicas que quer participar. Tudo isso você faz na página de Acompanhamento de Inscrição! <a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para acompanhar a sua inscrição.</a><br><br><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
 
					 //enviar e-mail de confirmação para o inscrito.

					 require 'PHPMailer-master/PHPMailerAutoload.php';

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
					//Set who the message is to be sent from
					$mail->setFrom('contato@enepet2017.com', 'ENEPET 2017');
					//Set an alternative reply-to address
					$mail->addReplyTo('ouvidoria.enepet2017@outlook.com', 'Ouvidoria ENEPET 2017');
					//Set who the message is to be sent to
					$mail->addAddress($email_address, $name);
					//Set the subject line
					$mail->Subject = $email_subject;
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML($email_body);
					//Replace the plain text body with one created manually
					$mail->AltBody = 'Esta é uma mensagem de confirmação de inscrição';
					//Attach an image file
					//$mail->addAttachment('../img/cidade.jpg');

					//send the message, check for errors
					$mail->send();
				
				}else{
				
					echo 'naoinserido';
					
					@unlink($arquivo);
				}
			}
		}
		
	}else{
		echo 'vazio';
	}


?>
