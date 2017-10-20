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
$p = isset($_GET['p'])?$_GET['p']:null;
if($p == 'enviar')
{
	$escolha = isset($_POST['escolha'])?$_POST['escolha']:null;
	$cpf = isset($_POST['cpf'])?$_POST['cpf']:null;
	if($escolha!=3 && $escolha!=4)
	{
		$comentario = isset($_POST['comentario'])?$_POST['comentario']:null;
		$data_envelope = isset($_POST['data'])?$_POST['data']:null;
		$n_envelope = isset($_POST['envelope'])?$_POST['envelope']:null;
		if(empty($escolha) || empty($comentario) || empty($cpf) ){
			$msg['erro'] =  '<div class="alert alert-danger" role="alert">
						  <strong>Opa!</strong> Campos vazios. Tente novamente.
						</div>';
			exit(json_encode($msg));
		}
			
		$rx = $con->prepare("SELECT envelope,data FROM deposito where envelope=?");
		$rx->bindParam(1, $n_envelope);
		$rx->execute();
		if($rx->rowCount() > 0){

			$arq3 = $rx->fetch(PDO::FETCH_OBJ);
			$msg['erro'] =  '<div class="alert alert-danger" role="alert">
						  <strong>Opa!</strong> Já existe um depósito com este número de envelope. Nome: '.$arq3->data.'
						</div>';
			exit(json_encode($msg));
		
		}else{
		
			if(!empty($data_envelope) && !empty($n_envelope))
			{
				$stmt = $con->prepare("INSERT INTO `deposito` VALUES (NULL,?,?)");
											
				$stmt->bindParam(1,$n_envelope);
				$stmt->bindParam(2,$data_envelope);
				$stmt->execute();
			}
		}
	
									
									
		$rt = $con->prepare("SELECT nome,email,pago FROM inscricoes where cpf=?");
		$rt->bindParam(1, $cpf);
		
		if($rt->execute()){
			 //enviar e-mail de confirmação para o inscrito.

				if($rt->rowCount() == 0 || $rt->rowCount() > 1 ){
				
					$msg['erro'] =  '<div class="alert alert-danger" role="alert">
								  <strong>Opa!</strong> Erro. No banco de dados. CPF não consta ou CPF duplicado. Contactar o ADM.
								</div>';
					exit(json_encode($msg));
				}else{
						$arq2 = $rt->fetch(PDO::FETCH_OBJ);
						
					if($arq2->pago == 2 || $arq2->pago == 0)
					{
					$msg['erro'] =  '<div class="alert alert-danger" role="alert">
								  <strong>Opa!</strong> Pagamento já feito ou comprovante não enviado.
								</div>';
					exit(json_encode($msg));
					}
						
						$nome = $arq2->nome;
						$email = $arq2->email;
					
				}
		}else{
		
			$msg['erro'] =  '<div class="alert alert-danger" role="alert">
						  <strong>Opa!</strong> Erro. No banco de dados. Contactar o ADM.
						</div>';
			exit(json_encode($msg));
		
		}
		
		require '../../PHPMailer-master/PHPMailerAutoload.php';
	}
	switch($escolha)
	{
		case 1:
			$aprovado = 2;
			$email_subject = 'ENEPET PIAUÍ INFORMA: Seu pagamento foi APROVADO!';
			//Trabalho aprovado
			$texto = utf8_decode("<html><body><p>Parabéns, ".$nome."! O seu pagamento foi <b>APROVADO com sucesso</b>. Comentário: \"".$comentario."\"<br><br><a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para acompanhar o status do seu pagamento.</a><br><br><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
			
		break;
		case 2:
			$aprovado = 0;
			$email_subject = 'ENEPET PIAUÍ INFORMA: Não localizamos seu pagamento!';
			//Trabalho aprovado parcialmente
			$texto = utf8_decode("<html><body><p>Opa! Não localizamos seu pagamento. Comentário: \"".$comentario."\". Reenvie-nos um comprovante válido.<br /><br /> <a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para reenviar o seu comprovente.</a><br><br><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
		
		break;
		case 3:
		//lote 1
			$stmt = $con->prepare("UPDATE `inscricoes` SET lote=0 WHERE cpf=? limit 1");
			$stmt->bindParam(1,$cpf);
			
			if($stmt->execute()){
			
				$msg['sucesso'] = 'Lote alterado com sucesso!';
				exit(json_encode($msg));
			}else{
			
				$msg['erro'] = 'Erro. Lote não alterado!';
				exit(json_encode($msg));
			}
		break;
		case 4:
		//lote 2
			$stmt = $con->prepare("UPDATE `inscricoes` SET lote=1 WHERE cpf=? limit 1");
			$stmt->bindParam(1,$cpf);
			
			if($stmt->execute()){
			
				$msg['sucesso'] = 'Lote alterado com sucesso!';
				exit(json_encode($msg));
			}else{
			
				$msg['erro'] = 'Erro. Lote não alterado!';
				exit(json_encode($msg));
			}
		break;
	
	}
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
	
	$stmt = $con->prepare("UPDATE `inscricoes` SET pago=? WHERE cpf=? limit 1");
	$stmt->bindParam(1,$aprovado);
	$stmt->bindParam(2,$cpf);
	
	if($stmt->execute()){
		 //enviar e-mail de confirmação para o inscrito.

		//Set the subject line
		$mail->Subject = $assunto;
		$nome = utf8_decode($nome);
		$mail->addAddress($email,$nome);

		if (!$mail->send()) 
		{
			$msg['erro'] = '<div class="alert alert-danger" role="alert">
						  <strong>Opa!</strong> Erro '.$email.' Error: ' . $mail->ErrorInfo.'.
						</div>';
			exit(json_encode($msg));
		} 
		else 
		{
			$msg['sucesso'] = '<div class="alert alert-success" role="alert">
						  <strong>Enviado!</strong>'.$email.'.
						</div>';
			exit(json_encode($msg));
		}
	}else{

		$msg['erro'] =  '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Erro. Avaliação não enviada. Tentar novamente a avaliação.
					</div>';
		exit(json_encode($msg));
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
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Read All Messages</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 1</strong>
                                        <span class="pull-right text-muted">40% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 2</strong>
                                        <span class="pull-right text-muted">20% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 3</strong>
                                        <span class="pull-right text-muted">60% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 4</strong>
                                        <span class="pull-right text-muted">80% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            <span class="sr-only">80% Complete (danger)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i><?php print $usuario->getNome(); ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Meu Perfil</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Configurações</a>
                        </li>
                        <li class="divider"></li>
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
                    <h1 class="page-header">Pagamentos e depósitos</h1>
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
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Ver detalhes</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
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
                                    <div class="huge" id="num_tarefas">0</div>
                                    <div>Tarefas incompletas!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Conferir todas</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
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
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Confirmar pagamentos</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
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
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Verificar trabalhos</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Pagamentos
                        </div>
                        <div class="panel-body">
							<div class="row">
								<?php echo 'Avaliador: ' . $usuario->getNome();?>
							</div>
                            <div class="row">
								<table id="trabalhos_cientificos"  class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>Nome</th>
											<th>PET</th>
											<th>Valor</th>
											<th>Lote</th>
											<th>Estado</th>
											<th>Situação</th>
											<th>Link</th>
											<th></th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>ID</th>
											<th>Nome</th>
											<th>PET</th>
											<th>Valor</th>
											<th>Lote</th>
											<th>Estado</th>
											<th>Situação</th>
											<th>Link</th>
											<th></th>
										</tr>
									</tfoot>
								</table>
                            </div>
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

    <!-- Portfolio Modal 6 -->
    <div class="portfolio-modal modal fade" id="avaliacao" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="close-modal" data-dismiss="modal">
					<div class="lr">
						<div class="rl">
						</div>
					</div>
				</div>
				<div>
					<div class="row">
						<div class="col-lg-12">
							<div class="modal-body">
								<!-- Project Details Go Here -->
								<h2>Avaliação da banca</h2>
								
							</div>
						</div>
					</div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" id="id">
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="formAvaliacao">
                                        <div class="form-group">
                                            <label>Escolha</label>
                                            <select class="form-control" id="escolha">
                                                <option value="1">Aprovar pagamento</option>
                                                <option value="2">Mandar reenviar</option>
                                                <option value="3">Lote 1</option>
                                                <option value="4">Lote 2</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Número Envelope</label>
                                            <input class="form-control" type="text" id="envelope" placeholder="digite aqui o número do envelope">
                                        </div>
                                        <div class="form-group">
                                            <label>Nome COMPLETO do depositante</label>
                                            <input class="form-control" type="text" id="data" placeholder="digite aqui o nome que consta no comprovante">
                                        </div>
										<div id="campo">
										
										
										</div>
                                        <div class="form-group">
                                            <label>Comentário da Banca</label>
                                            <textarea class="form-control" rows="5" id="comentario"></textarea>
                                        </div>
                                        <button type="submit" onclick="enviarAvaliacao()" class="btn btn-default">Enviar e-mail</button>
                                        <button type="reset" class="btn btn-default">Apagar Texto</button>
                                    </form>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                                <div class="col-lg-6" id="retorno">
								
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
					<div class="row">
						<div class="col-lg-12">
						<div class="modal-body">
							<div class="row">
								<div id="retornoAcomp" class="modal-body">
									<div class="row" id="retorno">
									
									</div>
								</div>
							</div>
						</div>
								<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Fechar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script src="script.js"></script>
    
	<script type="text/javascript">
	
	$(document).ready(function() {
		
		numeros();
		
		
		
		$('#trabalhos_cientificos').DataTable( {
            "columns": [
                {"data": "id1"},
                {"data": "nome"},
                {"data": "pet"},
                {"data": "preco"},
                {"data": "lote"},
                {"data": "estado"},
                {"data": "aprovado"},
                {"data": "link"},
                {"data": "id"}
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: 'server_processing2.php',
                type: 'POST'
            }
		} );
	} );
	
	function enviar(cpf){
	
	
		$('#formAvaliacao').on('submit',function (e)
		{
			e.preventDefault();
		});
	
		
		var data = $("input#data").val();
		var envelope = $("input#envelope").val();
		var escolha = $("select#escolha").val();
		var comentario = $("textarea#comentario").val();
		
		//var str = $('#formEmail').serialize();
		//alert(str);
		$.ajax({
			type: "POST",
			url: "pagamentos.php?p=enviar",
			data: {
				cpf: cpf,
				escolha: escolha,
				comentario: comentario,
				data: data,
				envelope: envelope
            },
			dataType: "json",
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
		
				$("form#formAvaliacao button[type=submit]").html("<i class=\"fa fa-clock-o\"></i> Enviando...").prop("disabled",true);
				//$("form#FormPagamento button[type=submit]").html("Enviando...").prop("disabled",true);
				//$(".modal").modal("show");
			},
			success: function(msg){
		
				$("form#formAvaliacao button[type=submit]").html("<i class=\"fa fa-check-o\"></i> Enviado!").prop("disabled",false);
				
				//$('#atualizaTrab').hide().html(msg['msg']).fadeIn('slow');
				if(msg['sucesso'])alert(msg['sucesso']);
				if(msg['erro'])alert(msg['erro']);
				$("textarea#comentario").val('');
			}
			
		
		
		});
	}
	function avaliar(cpf){
	
		$("div#id").html("Trabalho Científico - CPF #"+cpf);
		$("form#formAvaliacao button[type=submit]").html("<i class=\"fa fa-edit-o\"></i> Avaliar Comprovante").attr("onclick",'enviar(\''+cpf+'\')').prop("disabled",false);
		
		//var str = $('#formEmail').serialize();
		//alert(str);
		
	
	}
	</script>

</body>

</html>
