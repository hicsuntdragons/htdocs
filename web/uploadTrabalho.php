<?php


include_once 'inc/conexao.php';
include_once 'inc/config.php';


	if((strtotime(date("Y-m-d"))) > strtotime(end($dataSubmissaoFim)))
	{
		//fora do prazo
				echo 'vazio';
				
				exit(0);
	
	}
	
	if(!empty($_POST['titulo']) && 
		!empty($_POST['cpf']) && 
		!empty($_POST['area']) && 
		isset($_POST['checkAutoriza']) &&  
		!empty($_FILES['arquivoPDF']['tmp_name']))
	{

		//SE TIVER ARQUIVO
		$ccpf = validaCPF(retirar_letras_e_pontos($_POST['cpf']))?retirar_letras_e_pontos($_POST['cpf']):'';
		$rs = $con->prepare("SELECT * FROM eceel_inscricoes WHERE cpf = ?");
		$rs->bindParam(1, $ccpf);

		//verificar se o usuário existe na lista dos alunos
		if($rs->execute()){
			if($rs->rowCount() == 0 || $rs->rowCount()>=2){
				//cpf inválido
				echo 'cpf';
					 exit(0);
			}else{
		
				if($_FILES['arquivoPDF']['type'] != 'application/pdf'){
					//não pdf
					echo 'npdf';
					 exit(0);
				}
					
				//USUÁRIO EXISTE E ESTÁ CADASTRADO
				//EXIBIR DADOS DO USUÁRIO
				$arquivos = $rs->fetch(PDO::FETCH_OBJ);
				session_start();
				// verificar se o usuário está logado.
				if($arquivos->session_id != session_id() || $arquivos->cpf != $_SESSION['enepet2017_cpf']){
					 
					echo 'cpf';
					 session_destroy();
					 exit(0);
					
				}
				
				$diretorio = 'admin/pages/SubmissaoTrabalhos/'.$arquivos->cpf.'/';
				if(!is_dir($diretorio))mkdir($diretorio);
				
				$titulo = mb_strtoupper($_POST['titulo'],'utf-8');
				//$titulo = mb_strtoupper($titulo, 'utf-8');
				$cont = 0;
				if(isset($_POST['coautor'])){
					foreach($_POST['coautor'] as $file)
					{
						if(!empty($file)){
							$coautores .= mb_strtoupper($file,'utf-8') . '<br />';
							$coautor2[] = $file;
							$cont++;
						}
					}
				}
				$autor2 = isset($coautor2)?json_encode($coautor2):'[]';
				
				$novo_nome = $arquivos->cpf.'_'.sanitizeString($titulo);
				
				
				$caminho_resumo = $_FILES['arquivoPDF']['tmp_name'];
				$caminho2_resumo = $_FILES['arquivoPDF']['name'];
				$infos_resumo = pathinfo($caminho2_resumo);
				$nome_arquivo_resumo = 'resumo_'. $novo_nome.'.'.$infos_resumo['extension'];
				//$nome_arquivo_resumo = str_replace(" ", "_", $nome_arquivo_resumo);
				$link['resumo'] = $nome_arquivo_resumo;
				$arquivo_resumo = $diretorio.$nome_arquivo_resumo;
				//move_uploaded_file($value, $nome_original)
				
				if(!move_uploaded_file($caminho_resumo, $arquivo_resumo)){
					//@unlink($arquivo_resumo);
					
					echo 'Erroarquivo';
					exit(0);
				}
				
				$autor_principal = $arquivos->nome;
				$email = $arquivos->email;
				//$nome_pet = $arquivos->pet;
				
				$links = json_encode($link);
				
				//$autor = $usuario->getNome();
				//excluir as colunas (coloquei o nome delas no whats)
				//criar a coluna instituicao
				$cpf = $arquivos->cpf;
				$area = $_POST['area'];
				$instituicao = $arquivos->instituicao;
				$stmt = $con->prepare("INSERT INTO `trabalhos_cientificos` VALUES (NULL,?,?,?,'0','0',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?,'Aguardando comentário da banca.',?)");
				$stmt->bindParam(1,$titulo);
				$stmt->bindParam(2,$cpf); 
				$stmt->bindParam(3,$autor2);
				$stmt->bindParam(4,$area);
				$stmt->bindParam(5,$links);
				$stmt->bindParam(6,$instituicao);  
				
				if($stmt->execute())
				{
				
					/* // Create the email and send the message
					$to = $email; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
					$email_subject = 'ENEPET PIAUÍ 2017 - TRABALHO ENVIADO: '. $titulo;
					$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
					//$name = utf8_decode($name);
					
					$email_body = utf8_decode("<html><body><p>Parabéns! O seu resumo foi enviado com sucesso! <br><br>Aqui estão os detalhes da sua submissão:<br><br>Nome do autor principal: $autor_principal<br />Co-autores ($cont): <br />$coautores<br />E-mail: $email<br>Área do conhecimento: ".$_POST['area']."<br>$nome_pet<br><br>Até o <i>deadline</i> $data_submissao_txt, você receberá uma posição da banca avaliadora.  Enquanto isso, você já pode preparar seu pôster. Lembre-se que você tem a OBRIGAÇÃO de enviá-lo pela nossa plataforma de submissão (também em formato PDF - imediatamente após a aprovação do seu resumo). Após a aprovação do seu RESUMO e do seu PÔSTER, aí sim você poderá imprimir tranquilamente o seu pôster em sua gráfica de preferência.<br /><br /> Submeter mais trabalhos, pagar a inscrição, <b>verificar o status de seus trabalhos</b> e de sua inscrição, escolher atividades científicas que quer participar. Tudo isso você faz na página de Acompanhamento de Inscrição! <a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para acompanhar os seus trabalhos.</a><br><br><b>".($receberComprovante==false?'O pagamento da inscrição ainda não está disponível. Assim que for possível realizá-lo, a organização entrará em contato com maiores informações.':'Informações para depósito também está na página de acompanhamento.')."</b><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
 
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
					$mail->addAddress($email, $autor_principal);
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
					$mail->send(); */
					
					/* echo 
										'<div class="alert alert-success" role="alert">
										  <h4 class="alert-heading">'.$nome.', O seu trabalho foi submetido!</h4>
										</div>';
					 */
					//Sucesso!
					echo 'sucesso';
					
					 exit(0);
				
				}else{
					//arquivo não inserido
					
					@unlink($arquivo_resumo);
					
					echo 'arquivo';
					
					 exit(0);
				}
			}
		}
		
	}else{
	//Alguns campos não preenchidos
		echo 'vazio';
		//return false;
	}

?>
