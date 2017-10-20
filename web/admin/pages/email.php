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
	$assunto = isset($_POST['assunto'])?$_POST['assunto']:null;
	$destino = isset($_POST['destino'])?$_POST['destino']:null;
	$texto = isset($_POST['email'])?$_POST['email']:null;
	$pago = isset($_POST['pago'])?$_POST['pago']:null;
	$alojamento = isset($_POST['alojamento'])?$_POST['alojamento']:null;
	$festa = isset($_POST['festa'])?$_POST['festa']:null;
	
	if(empty($destino) || empty($texto) || empty($assunto) ){
		echo '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Campos vazios. Tente novamente.
					</div>';
		exit(0);
	}
	
	 require '../../PHPMailer-master/PHPMailerAutoload.php';
	$assunto = '=?UTF-8?B?'.base64_encode($assunto).'?=';
	$texto = utf8_decode($texto);
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
	//Set an alternative reply-to address
	$mail->addReplyTo('ouvidoria.enepet2017@outlook.com', 'Ouvidoria ENEPET 2017');
	//Set who the message is to be sent to
	//$mail->addAddress($email_address, $name);
	//Set the subject line
	$mail->Subject = $assunto;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML($texto);
	//Replace the plain text body with one created manually
	$mail->AltBody = 'Esta é uma mensagem de confirmação de inscrição';
	//Attach an image file
	//$mail->addAttachment('../img/cidade.jpg');

	switch($destino)
	{
		case 1:
			//Todos os inscritos
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes where pago=? and alojamento=? and festa=? limit 0,100");
			$rs->bindParam(1, $pago);
			$rs->bindParam(2, $alojamento);
			$rs->bindParam(3, $festa);
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 2:
		//Interessados em festas
		// echo '<div class="alert alert-danger" role="alert">
					  // <strong>Opa!</strong> Campos vazios. festas</a>.
					// </div><script>alert(\'teste\);</script>';
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CULTURAL ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes WHERE festa = 1");
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
			
		break;
		case 3:
		//Para os inscritos que não pagaram
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes WHERE pago = 0");
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 4:
		//Para os inscritos que não se logaram no sistema
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes WHERE session_id like ''");
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 5:
		//Pacote 101 - 200
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes where pago=? and alojamento=? and festa=? limit 100,100");
			$rs->bindParam(1, $pago);
			$rs->bindParam(2, $alojamento);
			$rs->bindParam(3, $festa);
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 6:
		//Pacote 201 - 300
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes where pago=? and alojamento=? and festa=? limit 200,100");
			$rs->bindParam(1, $pago);
			$rs->bindParam(2, $alojamento);
			$rs->bindParam(3, $festa);
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 7:
		//Pacote 301 - 400
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes where pago=? and alojamento=? and festa=? limit 300,100");
			$rs->bindParam(1, $pago);
			$rs->bindParam(2, $alojamento);
			$rs->bindParam(3, $festa);
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 8:
		//Pacote 401 - 500
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes where pago=? and alojamento=? and festa=? limit 400,100");
			$rs->bindParam(1, $pago);
			$rs->bindParam(2, $alojamento);
			$rs->bindParam(3, $festa);
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 9:
		//Pacote 501 - 600
		
			//Set who the message is to be sent from
			$mail->setFrom('noreply@enepet2017.com', 'CONTATO ENEPET 2017');
			$rs = $con->prepare("SELECT nome_cracha,email FROM inscricoes where pago=? and alojamento=? and festa=? limit 500,100");
			$rs->bindParam(1, $pago);
			$rs->bindParam(2, $alojamento);
			$rs->bindParam(3, $festa);
			
			if($rs->execute()){
				 //enviar e-mail de confirmação para o inscrito.

				//send the message, check for errors				
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					$nome = utf8_decode($arquivos->nome_cracha);
					$mail->clearAddresses();
					$mail->addAddress($arquivos->email,$nome);

					if (!$mail->send()) 
					{
						echo '<div class="alert alert-danger" role="alert">
									  <strong>Opa!</strong> Erro '.$arquivos->email.' Error: ' . $mail->ErrorInfo.'.
									</div>';
					} 
					else 
					{
						echo '<div class="alert alert-success" role="alert">
									  <strong>Enviado!</strong>'.$arquivos->email.'.
									</div>';
					}
				
				}
			}
		break;
		case 10:
		//Filtro SQL

			echo '<div class="alert alert-success" role="alert">
						  <strong>Ainda em construção.
						</div>';
		
		break;
	}

	exit(0);
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
                    <h1 class="page-header">E-mail</h1>
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
                            Formulário de e-mail em massa
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="formEmail">
                                        <div class="form-group">
                                            <label>Assunto</label>
                                            <input class="form-control" type="text" id="assunto" placeholder="digite aqui o assunto do e-mail">
                                        </div>
                                        <div class="form-group">
                                            <label>De:</label>
                                            <p class="form-control-static">contato@enepet2017.com</p>
                                        </div>
										<div class="form-group">
											<label>Checkboxes</label>
											<div class="checkbox">
												<label>
													<input type="checkbox" id="alojamento">Alojamento
												</label>
											</div>
											<div class="checkbox">
												<label>
													<input type="checkbox" id="festa">Festa
												</label>
											</div>
											<div class="checkbox">
												<label>
													<input type="checkbox" id="pago">Pago
												</label>
											</div>
										</div>
                                        <div class="form-group">
                                            <label>Destinos</label>
                                            <select class="form-control" id="destino" onchange="exibirCampo()">
                                                <option value="1">Pacote 0 - 100</option>
                                                <option value="4">Para os inscritos que não entraram na página de acompanhamento</option>
                                                <option value="5">Pacote 101 - 200</option>
                                                <option value="6">Pacote 201 - 300</option>
                                                <option value="7">Pacote 301 - 400</option>
                                                <option value="8">Pacote 401 - 500</option>
                                                <option value="9">Pacote 501 - 600</option>
                                                <option value="10">Filtro SQL</option>
                                            </select>
                                        </div>
										<div id="campo">
										
										
										</div>
                                        <div class="form-group">
                                            <label>Corpo do e-mail</label>
                                            <textarea class="form-control" rows="5" id="email"></textarea>
                                        </div>
                                        <button type="submit" onclick="enviarEmail()" class="btn btn-default">Enviar e-mail</button>
                                        <button type="reset" class="btn btn-default">Resetar form</button>
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
	
	function exibirCampo(){
		if($('select').val()==10){
			$('#campo').hide().html("<div class=\"form-group\"><label>Coluna SQL</label><input class=\"form-control\" placeholder=\"digite aqui a coluna SQL\"></div>");
			$('#campo').append("<div class=\"form-group\"><label>VALOR</label><input class=\"form-control\" placeholder=\"digite aqui a valor SQL\"></div>").fadeIn('slow');
		}else{
			$('#campo').html('');
		}
	}
	function enviarEmail()
	{

		$('#formEmail').on('submit',function (e)
		{
			e.preventDefault();
		});
		
		
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
		if($('#pago').prop("checked")==true){
		
			var pago = '1';
			
		}else{
		
			var pago = '0';
		}
		var assunto = $("input#assunto").val();
		var destino = $("select#destino").val();
		var email = $("textarea#email").val();
		$("form#formEmail button[type=submit]").html("<i class=\"fa fa-clock-o\"></i> Enviando...").prop("disabled",true);
		
		//var str = $('#formEmail').serialize();
		//alert(str);
		$.ajax({
			type: "POST",
			url: "email.php?p=enviar",
			dataType: "html",
			data: {
				assunto: assunto,
				destino: destino,
				email: email,
				pago: pago,
				alojamento: alojamento,
				festa:festa
            }
			
		
		
		}).done(function(msg){
			$("form#formEmail button[type=submit]").html("<i class=\"fa fa-check\"></i> Enviar outro e-mail").prop("disabled",false);
			$('#retorno').hide().html(msg).fadeIn('slow');
		});
		
	}
	</script>

</body>

</html>
