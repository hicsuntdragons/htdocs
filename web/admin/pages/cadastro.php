<?php 
require_once 'usuario.php';
require_once 'sessao.php';
require_once 'autenticador.php';
include_once '../../inc/conexao.php';
$aut = Autenticador::instanciar();

$usuario = null;
if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
}
else {
    $aut->expulsar();
}

if($usuario->getTipo() == 'PAG')
{
	header('location: pagamentos.php');
}

$p = isset($_GET['p'])?$_GET['p']:null;
if($p == 'enviar')
{
//header('Content-Type: application/json');
	$pesquisa = isset($_POST['pesquisa'])?$_POST['pesquisa']:null;
	
	if(empty($pesquisa)){
		$msg['erro'] =  '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Campo vazio. Tente novamente.
					</div>';
					
		exit(json_encode($msg));
	}
	$msg['erro'] = '';
	$rt = $con->prepare("SELECT * FROM inscricoes where email like ? or cpf=? or nome like ? limit 1");
	$rt->bindParam(1, $pesquisa);
	$rt->bindParam(2, $pesquisa);
	$rt->bindParam(3, $pesquisa);
			
	if($rt->execute()){
		if($rt->rowCount() == 0){
			$msg['dados'] = '';
			$msg['msg'] = '';
			$msg['erro'] =  '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Nenhum dado.
					</div>';
					
			exit(json_encode($msg));
		
		
		}
		//enviar e-mail de confirmação para o inscrito.

		//send the message, check for errors				
		$arq = $rt->fetch(PDO::FETCH_OBJ);
		$local = 'credenciamento';
		$rx = $con->prepare("SELECT cpf,local FROM `frequencia` where `cpf` LIKE ? and `local` LIKE ?");
		$rx->bindParam(1, $arq->cpf);
		$rx->bindParam(2, $local);
		$rx->execute();
		if($rx->rowCount() > 0)
		{
		$msg['erro'] =  '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> CREDENCIAMENTO JÁ FEITO!
					</div>';
		
		}else{
		
		$msg['erro'] =  '<div class="alert alert-success" role="alert">
					  <a href="freq.php?cpf='.$arq->cpf.'" target="_blank" class="btn btn-secondary btn-lg btn-block">CREDENCIAR</a>
					</div>';
		
		
		}
		$msg['dados'] = 
		'
			<form role="form" id="form2">
				<div class="form-group">
					<label>Nome</label>
					<input class="form-control" type="text" id="nome" placeholder="digite aqui" value="'.$arq->nome.'">
				</div>
				<div class="form-group">
					<label>CPF</label>
					<input class="form-control" type="text" id="cpf" placeholder="digite aqui" value="'.$arq->cpf.'">
				</div>
				<div class="form-group">
					<label>e-mail</label>
					<p class="form-control-static">'.$arq->email.'</p>
				</div>
				<div class="form-group">
					<label>PET</label>
					<p class="form-control-static">'.$arq->pet.'</p>
				</div>
				<div class="form-group">
					<label>Universidade</label>
					<p class="form-control-static">'.$arq->universidade.'</p>
				</div>
				<div class="form-group">
					<label>Cidade/Estado</label>
					<p class="form-control-static">'.$arq->cidade.'/'.$arq->estado.'</p>
				</div>
				<div class="form-group">
					<label>Status Pagamento:</label>
					<p class="form-control-static">'.($arq->pago==1?'Comprovante enviado':($arq->pago==2?'Depósito feito':'Esperando envio de um novo comprovante')).'</p>
				</div>
				
				
				<div class="dropdown show">
				  <a class="btn btn-secondary dropdown-toggle" href="?" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-photo"></i> Verificar comprovantes enviados
				  </a>

				  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				  ';
				  
				$itens = glob('./ComprovantesPagamento/'.$arq->cpf.'/{*.JPG,*.jpg,*.png,*.PNG,*.jpeg,*.JPEG,*.PDF,*.pdf}', GLOB_BRACE);

				if ($itens !== false) {
					$n_item = 0;

					foreach ($itens as $item) {
						$n_item++;
						$urlArray = explode('/', $item);
						$nome_img = $urlArray[(count($urlArray))-1];
						$msg['dados'] .= '<a class="dropdown-item" href="ComprovantesPagamento/'.$arq->cpf.'/'.$nome_img.'" target="_blank"><i class="fa fa-photo"></i> '.$nome_img.'</a>';
					}
				}else{
					$msg['dados'] .= '<p class="dropdown-item">Nenhum comprovante enviado.</p>';
				
				}
			$msg['dados'] .= '
				  </div>
				</div>
				
				<div class="form-group">
				'.(	$arq->pago==1|| $arq->pago==0 ?'
				<label for="pago" style="display: inline-block; line-height: 25px;">Alterar status pagamento</label><br>
				<select id="pago" name="pago" size="1">
					<option value="0">Receber um novo comprovante</option>
					<option value="1">Status de comprovante enviado</option>
					<option value="2">Pago</option>
				</select>
				</div>':'
				<label for="pago" style="display: inline-block; line-height: 25px;">Alterar status pagamento</label><br>
				<select id="pago" name="pago" size="1">
					<option value="2">Pago</option>
				</select>
				</div>'
				).'
				<div class="form-group">
					<label>Alterar senha</label>
					<input class="form-control" type="text" id="senha" placeholder="digite aqui uma nova senha" value="">
				</div>
				<div class="form-group">
					<label>Transferir pagamento</label>
					<input class="form-control" type="text" id="cpf_destino" placeholder="Digite o cpf para transferir o pagamento" value="">
				</div>
				<div class="form-group">
					<label>Checkboxes</label>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="alojamento" '.($arq->alojamento==1?'checked':'').'>Alojamento
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="festa" '.($arq->festa==1?'checked':'').'>Festa
						</label>
					</div>
				</div>
				<div id="qrcode">
				
				<a rel="nofollow" href="http://www.qrcode-generator.de" border="0" style="cursor:default"><img src="https://chart.googleapis.com/chart?cht=qr&chl=http%3A%2F%2Fenepet2017.com%2Fadmin%2Fpages%2Ffreq.php%3Fcpf%3D'. $arq->cpf .'&chs=320x320&choe=UTF-8&chld=L|2" alt=""></a>
				
				</div>
				<a href="#" id="imprimir" onclick="cont()">Agora imprima o QR CODE</a>
				<button type="submit" onclick="atualizar(\''.$arq->id.'\',\''.$arq->email.'\')" class="btn btn-default"><i class=\"fa fa-refresh\"></i> Atualizar</button>
				<button type="reset" class="btn btn-default">Resetar form</button>
			</form>';
			
		$rs = $con->prepare("SELECT * FROM `trabalhos_cientificos` WHERE cpf = ?");
		$rs->bindParam(1, $arq->cpf);

		$msg['msg'] = '';
		//verificar se o usuário existe na lista dos alunos
		if($rs->execute()){
			if($rs->rowCount() == 0){
			
			
			
				$msg['msg'] .=  '
					<tr>
						<td colspan="4">Nenhum trabalho enviado.</td>
					</tr>';
			}else{
				
				
				//USUÁRIO EXISTE E ESTÁ CADASTRADO
				//EXIBIR DADOS DO USUÁRIO
				$cont = 1;
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$msg['msg'] .= '
					<tr'.(($arquivos->aprovado == 1)?' class="success"':(($arquivos->aprovado == 2)?' class="warning"':(($arquivos->aprovado == 4)?' class="danger"':''))).'>
						<td>#'.$cont.'</td>
						<td onclick="statusTrabalho(\''.$arquivos->id.'\')">'.$arquivos->titulo.'</td>
						<td>'.(($arquivos->aprovado == 1)?'<i class="fa fa-smile-o"></i> Aprovado':(($arquivos->aprovado == 2)?'<i class="fa fa-meh-o"></i> Corrigir trabalho':(($arquivos->aprovado == 3)?'<i class="fa fa-clock-o"></i> Aguardando nova avaliação':(($arquivos->aprovado == 4)?'<i class="fa fa-frown-o"></i> Não aprovado':'<i class="fa fa-clock-o"></i> Aguardando avaliação')))).'</td>
						<td><a href="#" onclick="deletar(\''.$arquivos->id.'\',\''.$arq->email.'\',\''.$arquivos->titulo.'\')"><i class="fa fa-trash-o"></i> Deletar</a></td>
					</tr>';
					$cont = $cont + 1;
				}
				
			}
		}
		
	}
	exit(json_encode($msg));
}elseif($p == 'deletar')
{
	
	$id = isset($_POST['id'])?$_POST['id']:null;
	$titulo = isset($_POST['titulo'])?$_POST['titulo']:null;
	$email = isset($_POST['email'])?$_POST['email']:null;
	if(!empty($id)){
		$rs = $con->prepare("DELETE FROM `trabalhos_cientificos` WHERE id=? limit 1");
		$rs->bindParam(1, $id);
		if($rs->execute())
		{
				if(!empty($email)){
					$email_subject = 'ENEPET PIAUÍ 2017 - Trabalho deletado.';
					$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
					//$name = utf8_decode($name);
					
					$email_body = utf8_decode('<html><body><p>Olá! O trabalho com o título "'.$titulo.'" foi deletado conforme solicitado. Mensagem automática do site do enepet 2017. Confira-o na página de acompanhamento do ENEPET 2017 (www.enepet2017.com)! </body></html>');
 
					//enviar e-mail de confirmação para o inscrito.

					require '../../PHPMailer-master/PHPMailerAutoload.php';

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
					$mail->addAddress($email);
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
					if(!$mail->send())
					{
						
							$msg['msg'] = 'Deletado. E-mail não enviado.';
					}else
						$msg['msg'] = 'Deletado.';
				}else
					$msg['msg'] = 'Deletado. E-mail não enviado.';
		}else{
		
			$msg['msg'] = 'Não deletado do banco de dados.';
		}
	}else{
	
		$msg['msg'] = 'Id inválido.';
	}
	$msg['erro'] = '';
	exit(json_encode($msg));
}elseif($p == 'atualizar')
{

	$id = isset($_POST['id'])?$_POST['id']:null;
	$pago = isset($_POST['pago'])?$_POST['pago']:null;
	$nome = isset($_POST['nome'])?mb_strtoupper($_POST['nome'],'utf-8'):null;
	$aloj = isset($_POST['aloj'])?$_POST['aloj']:null;
	$festa = isset($_POST['festa'])?$_POST['festa']:null;
	$email = isset($_POST['email'])?$_POST['email']:null;
	$nova_senha = isset($_POST['senha'])?$_POST['senha']:null;
	$cpf_destino = isset($_POST['cpf_destino'])?limpar_numeros($_POST['cpf_destino']):null;
	$cpf2 = isset($_POST['cpf2'])?limpar_numeros($_POST['cpf2']):null;

	if(empty($nova_senha) && empty($cpf_destino))
	{
		$rs = $con->prepare("UPDATE `inscricoes` SET nome =?,pago=?,alojamento=?,festa=?,cpf=? WHERE id=? limit 1");
		$rs->bindParam(1, $nome);
		$rs->bindParam(2, $pago);
		$rs->bindParam(3, $aloj);
		$rs->bindParam(4, $festa);
		$rs->bindParam(5, $cpf2);
		$rs->bindParam(6, $id);
		if($rs->execute())
		{
			$msg['erro'] = '';
			$msg['msg'] = 'Atualização de cadastro foi feita com sucesso!';
						
					if(!empty($email))
					{
						$email_subject = 'ENEPET PIAUÍ 2017 - Algum dado do seu cadastro foi alterado.';
						$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
						//$name = utf8_decode($name);
						
						$email_body = utf8_decode('<html><body><p>Dados alterados conforme solicitado. <br />Nome: '.$nome.' <br />CPF: '.$cpf2.' <br />Status pagamento: '.($pago==1?'Comprovante enviado.':($pago==2?'Pagamento confirmado.':'Aguardando envio do comprovante.')).' <br />Alojamento: '.($aloj==1?'Sim':($aloj==2?'Não':'')).' <br /><br />Confira-os na página de acompanhamento do ENEPET 2017 (www.enepet2017.com)! </body></html>');
	 
						//enviar e-mail de confirmação para o inscrito.

						require '../../PHPMailer-master/PHPMailerAutoload.php';

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
						$mail->addAddress($email);
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
						if(!$mail->send())
						{
							$msg['erro'] = 'E-mail não enviado.';
						}
					}else
							$msg['erro'] = 'E-mail não enviado.';
		}
		else
		{
			$msg['erro'] = 'Erro. Tente novamente.';
			$msg['msg'] = '';
		}
		
		exit(json_encode($msg));
	}
	else
	{
		if(!validaCPF($cpf_destino) && !empty($nova_senha))
		{
			$senha_hash = password_hash("$nova_senha", PASSWORD_DEFAULT);
			$rs = $con->prepare("UPDATE `inscricoes` SET nome =?,pago=?,alojamento=?,festa=?,senha=? WHERE id=? limit 1");
			$rs->bindParam(1, $nome);
			$rs->bindParam(2, $pago);
			$rs->bindParam(3, $aloj);
			$rs->bindParam(4, $festa);
			$rs->bindParam(5, $senha_hash);
			$rs->bindParam(6, $id);
			
			if($rs->execute())
			{
				$msg['erro'] = '';
				$msg['msg'] = 'Atualização de cadastro foi feita com sucesso!';
							
						if(!empty($email))
						{
							$email_subject = 'ENEPET PIAUÍ 2017 - Algum dado do seu cadastro foi alterado.';
							$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
							//$name = utf8_decode($name);
							
							$email_body = utf8_decode('<html><body><p>Dados alterados conforme solicitado. <br />Nome: '.$nome.' <br />'.(!empty($nova_senha)?'Senha: '.$nova_senha.' <br />' : '').'Status pagamento: '.($pago==1?'Comprovante enviado.':($pago==2?'Pagamento confirmado.':'Aguardando envio do comprovante.')).' <br />Alojamento: '.($aloj==1?'Sim':($aloj==2?'Não':'')).' <br /><br />Confira-os na página de acompanhamento do ENEPET 2017 (www.enepet2017.com)! </body></html>');
		 
							//enviar e-mail de confirmação para o inscrito.

							require '../../PHPMailer-master/PHPMailerAutoload.php';

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
							$mail->addAddress($email);
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
							if(!$mail->send())
							{
								$msg['erro'] = 'E-mail não enviado.';
							}
						}else
								$msg['erro'] = 'E-mail não enviado.';
			}
			else
			{
				$msg['erro'] = 'Erro. Tente novamente.';
				$msg['msg'] = '';
			}
			
			exit(json_encode($msg));
			
			
		}elseif(validaCPF($cpf_destino)){
			// transferência de pagamento
			//tirando o status de pagamento pago
			$rs = $con->prepare("UPDATE `inscricoes` SET pago=0 WHERE id=? and pago=2 limit 1");
			$rs->bindParam(1, $id);
			if($rs->execute() && $rs->rowCount() != 0)
			{
				//ok, o usuário teve o pagamento aprovado e foi retirado.
				//Agora é colocar status aprovado na outra inscrição
				
				$rt = $con->prepare("UPDATE `inscricoes` SET pago=2 WHERE cpf=? limit 1");
				$rt->bindParam(1, $cpf_destino);
				if($rt->execute())
				{
					
					if(!empty($email))
					{
					
						$ru = $con->prepare("SELECT nome,email,cpf FROM inscricoes where id=? and pago=0 limit 1");
						$ru->bindParam(1, $id);
						
						$rv = $con->prepare("SELECT nome,email,cpf FROM inscricoes where cpf=? and pago=2 limit 1");
						$rv->bindParam(1, $cpf_destino);
						
						//enviar e-mail de confirmação para o inscrito.

						require '../../PHPMailer-master/PHPMailerAutoload.php';

						//Create a new PHPMailer instance
						$mail = new PHPMailer;
						
						
						if($ru->execute() && $rv->execute() && !($ru->rowCount() == 0 || $ru->rowCount() == 0))
						{
							//enviar e-mail de confirmação para o inscrito com o email

							//send the message, check for errors				
							$arq1 = $ru->fetch(PDO::FETCH_OBJ);			
							$arq2 = $rv->fetch(PDO::FETCH_OBJ);
							
							$email_subject = 'ENEPET PIAUÍ 2017 - O pagamento foi transferido para '.$arq2->nome.'.';
							
							$email_body = utf8_decode('<html><body><p>Olá! Conforme solicitado, confirmamos que o pagamento do(a) inscrito(a) '.$arq1->nome.', portador do CPF. nº '.$arq1->cpf.', foi transferido com sucesso para o(a) senhor(a) '.$arq2->nome.', portador do CPF nº '.$arq2->cpf.'. </p> <br><hr><p> Comissão organizadora XVI Enepet 2017 - Teresina - Piauí - Brasil.</p></body></html>');
							
							//Set who the message is to be sent to
							$mail->addAddress($arq1->email);
							$mail->addAddress($arq2->email);
						}else
						{
						
							//Set who the message is to be sent to
							$mail->addAddress($email);
							$email_subject = 'ENEPET PIAUÍ 2017 - O pagamento foi transferido para o cpf '.$cpf_destino.'.';
								
							$email_body = utf8_decode('<html><body><p>Olá! Conforme solicitado, confirmamos que o pagamento do(a) inscrito(a) portador do e-mail '.$email.', foi transferido com sucesso para o portador do CPF nº '.$cpf_destino.'. </p> <br><hr><p> Comissão organizadora XVI Enepet 2017 - Teresina - Piauí - Brasil.</p></body></html>');

						}
						$email_subject2 = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
						//$name = utf8_decode($name);
	 
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
						//Set the subject line
						$mail->Subject = $email_subject2;
						//Read an HTML message body from an external file, convert referenced images to embedded,
						//convert HTML into a basic plain-text alternative body
						$mail->msgHTML($email_body);
						//Replace the plain text body with one created manually
						$mail->AltBody = 'Esta é uma mensagem de confirmação de inscrição';
						//Attach an image file
						//$mail->addAttachment('../img/cidade.jpg');

						//send the message, check for errors
						if(!$mail->send())
						{
							$msg['erro'] = 'E-mail não enviado.';
						}
						
						$msg['msg'] = $email_subject;
					}else{
					
						$msg['erro'] = 'E-mail não enviado.';
					}
					
				}else{
					//problema: Avisar ao ADM do problema.
				
					$msg['msg'] = 'Problema: não foi mudado o status do pagamento do inscrito com CPF '.$cpf_destino.'. Mudar manualmente no site!';
				}
			
			}else{
			// erro
			
					$msg['msg'] = 'Problema: não foi mudado o status do pagamento da pessoa doadora. Tentar novamente!';
			}
		
			
			exit(json_encode($msg));
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="favico.ico">

    <title>Página interna - Enepet 2017</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Sistema Interno ENEPET 2017</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i><?php print $usuario->getNome(); ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="login.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Procurar...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="estatisticas.php"><i class="fa fa-bar-chart-o fa-fw"></i> Estatísticas</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Banca Avaliadora<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
								<li>
									<a href="pagamentos.php"><i class="fa fa-edit fa-fw"></i> Avaliar pagamentos</a>
								</li>
								<li>
									<a href="trabalhos.php"><i class="fa fa-rocket fa-fw"></i> Avaliar trabalhos</a>
								</li>
							</ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Cadastros<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
								<li>
									<a href="cadastro.php"><i class="fa fa-edit fa-fw"></i> Consultar cadastro</a>
								</li>
								<li>
									<a href="alterarsenha.php"><i class="fa fa-edit fa-fw"></i> Alterar senha</a>
								</li>
							</ul>
						</li>
                        <li>
                            <a href="tarefas.php"><i class="fa fa-tasks fa-fw"></i> Tarefas</a>
						</li>
                        <li>
                            <a href="email.php"><i class="fa fa-envelope-o fa-fw"></i> E-mail em massa</a>
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Consultar Cadastro</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-edit fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="num_inscricoes">0</div>
                                    <div>Inscrições!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="confirmadas">0</div>
                                    <div>Insc. confirmadas!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-money fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="num_pagamentos">0</div>
                                    <div>Novos pagamentos!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-rocket fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="num_submissoes">0</div>
                                    <div>Novas submissões</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Consultar CPF ou e-mail
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="form">
                                        <div class="form-group">
                                            <label>CPF ou e-mail</label>
                                            <input class="form-control" type="text" id="pesquisa" placeholder="digite aqui">
                                        </div>
                                        <button type="submit" onclick="enviar()" class="btn btn-default"><i class="fa fa-search fa-fw"></i>Pesquisar</button>
                                        <button type="reset" class="btn btn-default">Resetar form</button>
										<div id="erro"></div>
                                    </form>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                                <div class="col-lg-6">
										<div class="panel-body">
										<div id="retorno"></div>
										<hr />
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>Título</th>
															<th>Status</th>
															<th></th>
														</tr>
													</thead>
													<tbody id="atualizaTrab">
														<tr>
															<td colspan="4">Pesquise ao lado para listar trabalhos</td>
														</tr>
													</tbody>
												</table>
											</div>
											<!-- /.table-responsive -->
										</div>
								</div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Flot Charts JavaScript -->
    <script src="../vendor/flot/excanvas.min.js"></script>
    <script src="../vendor/flot/jquery.flot.js"></script>
    <script src="../vendor/flot/jquery.flot.pie.js"></script>
    <script src="../vendor/flot/jquery.flot.resize.js"></script>
    <script src="../vendor/flot/jquery.flot.time.js"></script>
    <script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
    
	
    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script src="script.js"></script>
	<script type="text/javascript">
	
	$(document).ready(function() {
		$(document).ready(function() {
			numeros();
		});
	});
	
	function atualizar(id,email)
	{

		$('#form2').on('submit',function (e)
		{
			e.preventDefault();
		});
		
		var nome = $("input#nome").val();
		var cpf2 = $("input#cpf").val();
		var senha = $("input#senha").val();
		var cpf_destino = $("input#cpf_destino").val();
		var pago = $("select#pago").val();
		if($('#alojamento').prop("checked")==true){
		
			var alojamento = '1';
			
		}else{
		
			var alojamento = '2';
		}
		if($('#festa').prop("checked")==true){
		
			var festa = '1';
			
		}else{
		
			var festa = '0';
		}
		
		
		//var str = $('#formEmail').serialize();
		//alert(str);
		$.ajax({
			type: "POST",
			url: "cadastro.php?p=atualizar",
			data: {
				id:id,
				nome:nome,
				aloj: alojamento,
				festa: festa,
				pago: pago,
				email: email,
				senha: senha,
				cpf_destino:cpf_destino,
				cpf2:cpf2
            },
			dataType: "json",
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#form button[type=submit]").html("<i class=\"fa fa-refresh\"></i> Atualizando...").prop("disabled",true);
				$("form2#form button[type=submit]").html("<i class=\"fa fa-refresh\"></i> Atualizando...").prop("disabled",true);
				//$("form#FormPagamento button[type=submit]").html("Enviando...").prop("disabled",true);
				//$(".modal").modal("show");
			},
			success: function(msg){
		
				$("form#form button[type=submit]").html("<i class=\"fa fa-check\"></i> Atualizado. Pesquisar novamente...").prop("disabled",false);
				$("form2#form button[type=submit]").html("<i class=\"fa fa-check\"></i> Atualizado. Atualizar novamente...").prop("disabled",false);
				
				//$('#atualizaTrab').hide().html(msg['msg']).fadeIn('slow');
				if(msg['msg'])alert(msg['msg']);
				if(msg['erro'])alert(msg['erro']);
				enviar();
				// $('#retorno').hide().append(msg['msg']).fadeIn('slow');
				// $('#erro').hide().html(msg['erro']).fadeIn('slow');
			}
		
		});
		
	}
	
	function enviar()
	{

		$('#form').on('submit',function (e)
		{
			e.preventDefault();
		});
		
		
		var pesquisa = $("input#pesquisa").val();
		
		
		//var str = $('#formEmail').serialize();
		//alert(str);
		$.ajax({
			type: "POST",
			url: "cadastro.php?p=enviar",
			data: {
				pesquisa: pesquisa
            },
			dataType: "json",
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#form button[type=submit]").html("<i class=\"fa fa-clock-o\"></i> Pesquisando...").prop("disabled",true);
				//$("form#FormPagamento button[type=submit]").html("Enviando...").prop("disabled",true);
				//$(".modal").modal("show");
			},
			success: function(msg){
		
				$("form#form button[type=submit]").html("<i class=\"fa fa-check\"></i> Pesquisar novamente").prop("disabled",false);
				
				$('#atualizaTrab').hide().html(msg['msg']).fadeIn('slow');
				$('#retorno').hide().html(msg['dados']).fadeIn('slow');
				$('#erro').hide().html(msg['erro']).fadeIn('slow');
			}
		
		});
		
	}
	function deletar(id,email,titulo)
	{

		$('#form').on('submit',function (e)
		{
			e.preventDefault();
		});
		
		
		
		//var str = $('#formEmail').serialize();
		//alert(str);
		$.ajax({
			type: "POST",
			url: "cadastro.php?p=deletar",
			data: {
				id: id,
				email: email,
				titulo: titulo
            },
			dataType: "json",
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#form button[type=submit]").html("<i class=\"fa fa-clock-o\"></i> Deletando...").prop("disabled",true);
				//$("form#FormPagamento button[type=submit]").html("Enviando...").prop("disabled",true);
				//$(".modal").modal("show");
			},
			success: function(msg){
		
				$("form#form button[type=submit]").html("<i class=\"fa fa-check\"></i> Atualizado. Pesquisar novamente...").prop("disabled",false);
				$("form2#form button[type=submit]").html("<i class=\"fa fa-check\"></i> Atualizado. Atualizar novamente...").prop("disabled",false);
				
				//$('#atualizaTrab').hide().html(msg['msg']).fadeIn('slow');
				if(msg['msg'])alert(msg['msg']);
				if(msg['erro'])alert(msg['erro']);
				enviar();
			}
		
		});
		
	}
	
	function cont(){
	   var conteudo = document.getElementById('qrcode').innerHTML;
	   tela_impressao = window.open('about:blank');
	   tela_impressao.document.write(conteudo);
	   tela_impressao.window.print();
	   tela_impressao.window.close();
	}
	</script>

</body>

</html>
