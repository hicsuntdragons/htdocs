<?php


include_once 'inc/conexao.php';
include_once 'inc/config.php';



	
	
	if(!empty($_POST['id']) && 
		isset($_POST['cpf']) && 
		isset($_POST['checkAutoriza']) && 
		(!empty($_FILES['arquivoPDF']['tmp_name']) || !empty($_FILES['arquivoPDF2']['tmp_name'])))
	{

		//SE TIVER ARQUIVO
		$cpf = validaCPF(retirar_letras_e_pontos($_POST['cpf']))?retirar_letras_e_pontos($_POST['cpf']):'';
		$id = retirar_letras_e_pontos($_POST['id']);
		$rs = $con->prepare("SELECT * FROM `trabalhos_cientificos` WHERE id = ? and cpf = ?");
		$rs->bindParam(1, $id);
		$rs->bindParam(2, $cpf);

		//verificar se o usuário existe na lista dos alunos
		if($rs->execute()){
			if($rs->rowCount() == 0){
				echo 'Id inválido. Tente novamente.';
			}else{
		
				if(!empty($_FILES['arquivoPDF']['tmp_name']))
				{
					if($_FILES['arquivoPDF']['type'] != 'application/pdf'){
						echo 'Opa! O arquivo não está em pdf.';
						exit(0);
					}
				}
				if(!empty($_FILES['arquivoPDF2']['tmp_name']))
				{
					if($_FILES['arquivoPDF2']['type'] != 'application/pdf'){
						echo 'Opa! O termo não está em pdf.';
						exit(0);
					}
				}		
				//USUÁRIO EXISTE E ESTÁ CADASTRADO
				//EXIBIR DADOS DO USUÁRIO
				$arquivos = $rs->fetch(PDO::FETCH_OBJ);
				$links = json_decode($arquivos->link,true);
				$cpf = $arquivos->cpf;
				$titulo = $arquivos->titulo;
				
				session_start();
				// verificar se o usuário está logado.
				if($arquivos->cpf != $_SESSION['enepet2017_cpf']){
					#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
					 
					echo '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Você foi deslogado. <a href="?p=acompanhamento">Clique aqui para Entrar novamente</a>.
					</div>';
					 session_destroy();
					 exit(0);
				}
				
				$diretorio = 'admin/pages/SubmissaoTrabalhos/'.$arquivos->cpf.'/';
				
				if(!is_dir($diretorio))mkdir($diretorio);
				
				$novo_nome = $arquivos->cpf.'_'.sanitizeString($titulo);
				
				if($arquivos->aprovado==2)
				{
				
					if((strtotime(date("Y-m-d"))) > strtotime(end($dataFinalReenvio)))
					{
						//fora do prazo
								echo 'O prazo para reenvio de arquivos corrigidos acabou :/';
								
								exit(0);
					
					}
				//Trabalho corrigido
					if(!empty($_FILES['arquivoPDF']['tmp_name']) && !empty($_FILES['arquivoPDF2']['tmp_name']))
					{
						//temos dois arquivos corrigidos
					
						//o resumo corrigido
						
						$caminho = $_FILES['arquivoPDF']['tmp_name'];
						$caminho2 = $_FILES['arquivoPDF']['name'];
						$infos = pathinfo($caminho2);
						
						$nome_arquivo = 'resumo_'. $novo_nome.'_v2.'.$infos['extension'];
						//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
						$links['corrigido_resumo'] = $nome_arquivo;
						//$msg = 'Resumo corrigido';
						
						$arquivo = $diretorio.$nome_arquivo;
						//move_uploaded_file($value, $nome_original)
						if(!move_uploaded_file($caminho, $arquivo)){echo 'Erro ao mover arquivo.'; exit(0);}
						//$autor = $usuario->getNome();
					
						//termo corrigido
						
						$caminho = $_FILES['arquivoPDF2']['tmp_name'];
						$caminho2 = $_FILES['arquivoPDF2']['name'];
						$infos = pathinfo($caminho2);
						
						$nome_arquivo = 'termo_'. $novo_nome.'_v2.'.$infos['extension'];
						//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
						//Aguardando uma nova análise
						$aprovado = 3;
						//Aguardando envio do banner
						$aprovado_banner = 0;
						$links['corrigido_termo'] = $nome_arquivo;
						$msg = 'os seus Termo e resumo corrigidos';
						
						$arquivo = $diretorio.$nome_arquivo;
						//move_uploaded_file($value, $nome_original)
						if(!move_uploaded_file($caminho, $arquivo)){echo 'Erro ao mover arquivo. Tente novamente.'; exit(0);}
					
					}elseif(!empty($_FILES['arquivoPDF']['tmp_name']))
					{
						//temos somente o resumo corrigido
						
						$caminho = $_FILES['arquivoPDF']['tmp_name'];
						$caminho2 = $_FILES['arquivoPDF']['name'];
						$infos = pathinfo($caminho2);
						
						$nome_arquivo = 'resumo_'. $novo_nome.'_v2.'.$infos['extension'];
						//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
						//Aguardando uma nova análise
						$aprovado = 3;
						//Aguardando envio do banner
						$aprovado_banner = 0;
						$links['corrigido_resumo'] = $nome_arquivo;
						$msg = 'o seu Resumo corrigido';
						
						$arquivo = $diretorio.$nome_arquivo;
						//move_uploaded_file($value, $nome_original)
						if(!move_uploaded_file($caminho, $arquivo)){echo 'Erro ao mover arquivo.'; exit(0);}
						//$autor = $usuario->getNome();
					
					}else{
					//somente o termo corrigido
						
						$caminho = $_FILES['arquivoPDF2']['tmp_name'];
						$caminho2 = $_FILES['arquivoPDF2']['name'];
						$infos = pathinfo($caminho2);
						
						$nome_arquivo = 'termo_'. $novo_nome.'_v2.'.$infos['extension'];
						//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
						//Aguardando uma nova análise
						$aprovado = 3;
						//Aguardando envio do banner
						$aprovado_banner = 0;
						$links['corrigido_termo'] = $nome_arquivo;
						$msg = 'o seu Termo corrigido';
						
						$arquivo = $diretorio.$nome_arquivo;
						//move_uploaded_file($value, $nome_original)
						if(!move_uploaded_file($caminho, $arquivo)){echo 'Erro ao mover arquivo.'; exit(0);}
						//$autor = $usuario->getNome();
					}
				
				}else if($arquivos->aprovado==1)
				{
				
					if((strtotime(date("Y-m-d"))) > strtotime($dataFinalBanner))
					{
						//fora do prazo
								echo 'O prazo para envio de banner acabou :/';
								
								exit(0);
					
					}
				//trabalho aprovado
					$aprovado = 1;
					if($arquivos->aprovado_banner==0 || $arquivos->aprovado_banner==2)
					{
					
						$caminho = $_FILES['arquivoPDF']['tmp_name'];
						$caminho2 = $_FILES['arquivoPDF']['name'];
						$infos = pathinfo($caminho2);
						
						$nome_arquivo = 'banner_'. $novo_nome.'.'.$infos['extension'];
						//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
						//Aguardando análise
						$aprovado_banner = 3;
						$links['banner'] = $nome_arquivo;
						$msg = 'o seu Banner';
						//Status corrigir banner
						
						$arquivo = $diretorio.$nome_arquivo;
						//move_uploaded_file($value, $nome_original)
						if(!move_uploaded_file($caminho, $arquivo)){echo 'Erro ao mover arquivo.'; exit(0);}
						
					}else{
						echo 'Opa, acho que você já enviou o seu trabalho...';
						exit(0);
					
					}

				}
				//$titulo = mb_strtoupper($_POST['titulo'], 'utf-8');
				// $autor2 = isset($_POST['autor2'])?mb_strtoupper($_POST['autor2'], 'utf-8'):'';
				// $autor3 = isset($_POST['autor3'])?mb_strtoupper($_POST['autor3'], 'utf-8'):'';
				// $autor4 = isset($_POST['autor4'])?mb_strtoupper($_POST['autor4'], 'utf-8'):'';
				$link = json_encode($links);
				// $stmt = $con->prepare("INSERT INTO `trabalhos_cientificos` VALUES ('',?,?,?,?,?,0,0,CURRENT_TIMESTAMP,'',?,?)");
				// $stmt->bindParam(1,$titulo);
				// $stmt->bindParam(2,$arquivos->cpf); 
				// $stmt->bindParam(3,$autor2);
				// $stmt->bindParam(4,$autor3);
				// $stmt->bindParam(5,$autor4);
				// $stmt->bindParam(6,$_POST['area']);
				// $stmt->bindParam(7,$link);
				
				$stmt = $con->prepare("UPDATE `trabalhos_cientificos` SET aprovado=?,link=?,aprovado_banner=? WHERE id=?");
				$stmt->bindParam(1,$aprovado);
				$stmt->bindParam(2,$link);
				$stmt->bindParam(3,$aprovado_banner);
				$stmt->bindParam(4,$arquivos->id);
				
				if($stmt->execute())
				{
				
	$rt = $con->prepare("SELECT nome_cracha,email FROM `inscricoes` WHERE cpf = ?");
	$rt->bindParam(1, $cpf);

	//verificar se o trabalho existe na lista dos trabalhos científicos
	$rt->execute();
			
			//USUÁRIO EXISTE E ESTÁ CADASTRADO
			//EXIBIR DADOS DO USUÁRIO
			$arq = $rt->fetch(PDO::FETCH_OBJ);
			$email = $arq->email;
			$nome = $arq->nome_cracha;
			$nome = utf8_decode($nome);
					$email_subject = 'ENEPET PIAUÍ INFORMA: Nós recebemos '.$msg.'!';
					//Trabalho reprovado
					$texto = utf8_decode("<html><body><p>Olá $nome. Nós recebemos ".$msg.". Aguarde a avaliação da comissão organizadora. ".( $aprovado==3 ?'Após a aprovação do seu RESUMO, aí sim você poderá enviar o PDF do seu banner pela nossa plataforma de submissão.':'' ) ."<br /><br /> <a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para acompanhar o seu trabalho.</a><br><br><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
					
					$assunto = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
					//$name = utf8_decode($name);
					
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
					//Set an alternative reply-to address
					$mail->addReplyTo('ouvidoria.enepet2017@outlook.com', 'Ouvidoria ENEPET 2017');
					//Set who the message is to be sent to
					$mail->addAddress($email, $nome);
					
					//Set the subject line
					$mail->Subject = $assunto;
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML($texto);
					//Replace the plain text body with one created manually
					$mail->AltBody = 'Esta é uma mensagem de confirmação de trabalho';
					//Attach an image file
					//$mail->addAttachment('../img/cidade.jpg');
					$mail->setFrom('contato@enepet2017.com', 'CONTATO ENEPET 2017');
					

					$mail->send(); 
					
					echo 'Sucesso! Arquivo enviado.';
					
				
				}else{
				
					echo 'erro! Arquivo não inserido. Tente novamente.';
					
					@unlink($arquivo);
				}
			}
		}
		
	}else{
		echo 'Campo(s) vazio(s)! Tente novamente.';
	}


?>
