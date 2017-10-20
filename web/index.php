<?php

//include_once 'inc/conexao.php';
include_once 'inc/config.php';
$p = isset($_GET['p'])?$_GET['p']:'';
if($p == 'acompanhar')
{
	$q = isset($_GET['acao'])? $_GET['acao']:'';
	if($q == 'sair'){
		session_start();
		$_SESSION = array();
		session_destroy();
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

    <title>iv ECEEL</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css?v2" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/agency.css?v2" rel="stylesheet">

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css?v2">
    <!-- Custom Fonts -->
	<link href="font_wood/stylesheet.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="shortcut icon" href="logo.ico">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top" class="index" onload="abrir('<?php echo $p; ?>')">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top" style="font-size: 40px; margin-top:-10px;"> 
				  <span style="white-space:nowrap;">
					<img src="img/complogoblk.png" alt="fgr" style="width:50px;height:50px; margin-right:-21px">
					Inicio</span> </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#evento" id="link">O Evento</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#programacao" id="link">Programação</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#inscrevase" id="link">Inscreva-se!</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact" id="link">Contate-nos!</a>
                    </li>
					<li><a href="https://instagram.com/enepetpiaui2017" target="_blank"><i class="fa fa-instagram fa-2x"></i></a>
					</li>
                </ul>
            </div>
        </div>	
		
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
			
                <div class="intro-heading">iv ECEEL</div>
				<div class="intro-heading" style="font-size:30px">encontro cientifico de estudantes<br> de engenharia eletrica</div>
				
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="evento" style="background-color:#f7f7f7;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Dias 16 e 17 de novembro</h2>
					<h2 class="section-heading">Local: Centro de Tecnologia - CT	 UFPI</h2>
                    <h3 class="section-subheading text-muted"> O ECEEL é um evento concebido e executado pelo grupo PET Potência - Pet Engenharia elétrica,
					com o apoio do departamento de Engenharia Elétrica e da Universidade Federal do Piauí. O evento tem por objetivo promover o curso nos ambitos da
					pesquisa científica e extensão, além de promover a interação entre professores e alunos de todos os períodos e diferentes faculdades/universidades.
					<br> Confira a seguir as atrações do ECEEL!</h3>
                </div>
            </div>
			
        </div>	
		
		<div id="row">
		
					<?php
					
						$itens = glob('./img/carrossel_ufpi/{*.JPG,*.jpg}', GLOB_BRACE);
						// $noticia_principal = array(0 => array(0 => '0','frase1',
						// 'frase2','circuit-slider.png','obj1'),1 => array(0 => '1','frase1','frase2','slider-background.png','obj2'));

						if ($itens !== false) {
							$n_item = 0;

							foreach ($itens as $item) {
								$n_item++;
								$urlArray = explode('/', $item);
								$nome_img[] = $urlArray[(count($urlArray))-1];
							}
						}
					
					if($n_item>0){
					?>
					  <div id="myCarousel" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
						<?php $prima = 0;
							foreach ($itens as $item) {
								echo '<li data-target="#myCarousel" data-slide-to="'.$prima.'"'. (($prima==0)? 'class="active"':'') .'></li>';
								$prima++;
							}
						?>
						</ol>

						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox" align="center">
						
						<?php $prima = 0;
							foreach ($itens as $item) {
								echo '
						  <div class="item'.( ($prima==0)? ' active':'').'">
							<img src="img/carrossel_ufpi/'.$nome_img[$prima].'">
							  <div class="carousel-caption d-none d-md-block">
								<h3></h3>
								<p></p>
							  </div>
						  </div>';
								$prima++;
							}
						?>
						</div>

						<!-- Left and right controls -->
						<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
						  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						  <span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
						  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						  <span class="sr-only">Next</span>
						</a>
					  </div>
					  <?php } ?>
		
		</div>
    </section>

<?php

include_once 'inc/conexao.php';
?>

    <!-- About Section -->
    <section id="programacao" style="background-color:#f7f7f7;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">A programação</h2>
                    <h3 class="section-subheading text-muted">Confira abaixo a programação oficial do iv ECEEL.</h3>
                </div>
            </div>
			<div class="row">
			  
			<?php

	$prog = $con->prepare("SELECT * FROM programacao order by horario asc");
	if($prog->execute()){
		if($prog->rowCount() > 0){
		
		echo '<table class="table table-hover">';
			$dia = 0;
			$mes = 0;
			$cont = 0;
			$primeiro = 1;
			while($arquivos = $prog->fetch(PDO::FETCH_OBJ))
			{
			?>
			<?php 
			
			if($dia != date_format(date_create($arquivos->horario),"d") || $mes !=date_format(date_create($arquivos->horario),"m"))
			{
				$dia = date_format(date_create($arquivos->horario),"d");
				$mes = date_format(date_create($arquivos->horario),"m");
				$cont = $cont + 1;
				if($cont != 0)
				{
					if($primeiro==0){
						echo '</tbody>';
					}
					$primeiro = 0;
				}
				?>
				
				<thead>
				  <tr>
					<th>Horário</th>
					<th><?php echo $cont; ?>º dia (<?php echo date_format(date_create($arquivos->horario),"d/m/Y");?>)</th>
					<th>Local</th>
				  </tr>
				</thead>
				<tbody>
				
				<?php
			}
			?>
				  <tr>
					<td><?php echo date_format(date_create($arquivos->horario),"H\h i\m\i\\n");?></td>
					<td><?php echo $arquivos->descricao;?></td>
					<td><?php echo $arquivos->local;?></td>
				  </tr>

			
			<?php
			}
			echo 
				'</tbody>';
			?>
			  </table>
				<p>Obs.: A programação está sujeita a alterações e atualizações.</p>
				<p>Você pode baixar o cronograma <a href="img/cronogramaOnly.png" download> aqui.</a> </p>
			<?php  
		}else{
		?>
		
            <div class="row">
                <div class="col-lg-12 text-center">
					<img src="img/cronogramaOnly.png" class="img-responsive img-centered" />
                </div>
            </div>
		<?php
		}
	}
			?>
        </div>
        </div>
	</section>

    <!-- Team Section -->
    <section id="inscrevase" class="bg-light-gray">
        <div class="container">
		
			<div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Inscreva-se!</h2>
                    <h3 class="section-subheading text-muted">Confira a seguir como se inscrever, escolher seu minicurso e submeter seus trabalhos.</h3>
                </div>
            </div>
		
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Inscrições</h4>
					  <a href="#inscricoes" onclick="newForm()" id="link" class="portfolio-link btn btn-success" data-toggle="modal"><i class="fa fa-plus"></i> Quero realizar a minha inscrição</a>
                    <p class="text-muted">As inscrições vão até o dia <b><?php echo (date_format(date_create(end($dataFimInscricao)),"d/m/Y"));?></b>. Ao optar por um minicurso, a taxa será <?php echo $precoComAlojamento; ?>. Ao optar por uma apresentação de trabalho : <?php echo $precoSemAlojamento; ?>.<br> Não perca tempo e realize logo a sua.</p>


				</div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-send-o fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Acompanhar Inscrição</h4>
					<a href="#acompanhamento" id="link" class="portfolio-link btn btn-success" data-toggle="modal"><i class="fa fa-clock-o"></i> Acompanhar inscrição</a>
					
                    <p class="text-muted">Submeter trabalhos, verificar o <i>status</i> de seus trabalhos e de sua inscrição. Tudo isso você faz aqui!</p>
				</div>
                <div class="col-md-4">
                     <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-flask fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Submissão de Trabalhos</h4>
                    <p class="text-muted">Todas as informações a respeito submissão de trabalhos estão <a href="#submissao" id="link" class="portfolio-link" data-toggle="modal"><b>aqui</b></a>: datas importantes, modelos de resumo e banner.</p>
                </div>
			</div>
		</div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" style="background-color:#f7f7f7;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contate-nos!</h2>
                    <h3 class="section-subheading text-muted" style="color:#fff">Dúvidas? Escreva para a gente.<br>
					Você também pode nos procurar na sala do PET, no bloco 8 do curso de Engenharia Elétrica.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Seu Nome *" id="name" required data-validation-required-message="Por favor, digite seu nome.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Seu E-mail *" id="email" required data-validation-required-message="Por favor, digite um endereço de e-mail.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Seu número de telefone *" id="phone" required data-validation-required-message="Por favor, digite um número de telefone.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Sua Mensagem *" id="message" required data-validation-required-message="Por favor, digite a sua mensagem."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Enviar mensagem <span class="glyphicon glyphicon-chevron-right"></span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer style="background-color:#f7f7f7;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; PET Potência 2017</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="https://instagram.com/enepetpiaui2017/" target="_blank"><i class="fa fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="?p=inscricoes">Inscrever</a></li>
                        <li><a href="?p=acompanhamento">Acompanhar</a></li>
                        <li><a href="?p=evento">O evento</a></li>
                        <li><a href="?p=submissao">Regras</a></li>
                        <li><a href="?p=programacao">Programação</a></li>
                        <li><a href="?p=contact">Contate-nos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Portfolio Modals -->
    <!-- Use the modals below to showcase details about your portfolio projects! -->

    
    <!-- Portfolio Modal 2 -->
    <div class="portfolio-modal modal fade" id="inscricoes" tabindex="-1" role="dialog" aria-hidden="true">
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
								<h2><?php if(strtotime(end($dataFimInscricao)) >= (strtotime(date("Y-m-d"))))echo 'Inscrição iv ECEEL';else echo 'Acompanhamento de inscrição';?></h2>
								<?php if(strtotime(end($dataFimInscricao)) >= (strtotime(date("Y-m-d")))){ ?>
								<p class="item-intro text-muted" align="justify">Caso opte por participar de um minicurso ou apresentar trabalhos, é necessário realizar o pagamento da taxa 
								correspondente. O pagamento será feito presencialmente na Sala do PET Potência, no Bloco 8 do CT - UFPI. Enquanto isso, você já pode submeter trabalhos e 
								acompanhar sua inscrição.</p>
								<?php } else { 
									echo '<div class="alert alert-warning" role="alert">
										  <strong>Opa!</strong> Inscrições encerradas.
										</div>';
										}?>
								<div class="row" id="retorno">
									Habilite o javascript em seu navegador.
								</div>
								<hr>
								<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Voltar à página</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>

    <!-- Portfolio Modal 5 -->
    <div class="portfolio-modal modal fade" id="resumoPets" tabindex="-1" role="dialog" aria-hidden="true">
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
								<h2>O GRUPO PET PIAUÍ NO ENEPET MACEIÓ 2016</h2>
								<p class="item-intro text-muted">Nesta página, você conhecerá um pouco dos PETs existentes no Piauí.</p>
								<hr>
								<!-- /.row -->

								<div class="row">
								<?php
								
									$itens = glob('./img/carrossel/{*.JPG,*.jpg}', GLOB_BRACE);
									// $noticia_principal = array(0 => array(0 => '0','frase1',
									// 'frase2','circuit-slider.png','obj1'),1 => array(0 => '1','frase1','frase2','slider-background.png','obj2'));

									if ($itens !== false) {
										$n_item = 0;

										foreach ($itens as $item) {
											$n_item++;
											$urlArray = explode('/', $item);
											$nome_img2[] = $urlArray[(count($urlArray))-1];
										}
									}
								
								if($n_item>0){
								?>
								  <div id="myCarousel2" class="carousel slide" data-ride="carousel">
									<!-- Indicators -->
									<ol class="carousel-indicators">
									<?php $prima = 0;
										foreach ($itens as $item) {
											echo '<li data-target="#myCarousel2" data-slide-to="'.$prima.'"'. (($prima==0)? 'class="active"':'') .'></li>';
											$prima++;
										}
									?>
									</ol>

									<!-- Wrapper for slides -->
									<div class="carousel-inner" role="listbox" align="center">
									
									<?php $prima = 0;
										foreach ($itens as $item) {
										
											$urlArray = explode('/', $item);
											echo '
									  <div class="item'.( ($prima==0)? ' active':'').'">
										<img src="img/carrossel/'.$nome_img2[$prima].'">
									  </div>';
											$prima++;
										}
									?>
									</div>

									<!-- Left and right controls -->
									<a class="left carousel-control" href="#myCarousel2" role="button" data-slide="prev">
									  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
									  <span class="sr-only">Previous</span>
									</a>
									<a class="right carousel-control" href="#myCarousel2" role="button" data-slide="next">
									  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
									  <span class="sr-only">Next</span>
									</a>
								  </div>
								  <?php } ?>
								</div>
								<!-- /.row -->

								<hr>
								<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Fechar janela</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- Portfolio Modal 6 -->
    <div class="portfolio-modal modal fade" id="submissao" tabindex="-1" role="dialog" aria-hidden="true">
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
								<h2>Submissão de Trabalhos</h2>
								<h4>Requisitos para envio dos resumos</h4>
								<p class="item-intro text-muted" align="justify">
								Os resumos informativos simples devem ser enviados em formato PDF.<br>
								O apresentador deve ser integrante do Programa de Educação Tutorial (PET)
								e participante e inscrito no XVI Enepet.
								Os resumos devem conter um autor principal, o qual será o apresentador do pôster.
								Os trabalhos devem:<br><hr>
								ser homologados pelo tutor através de um Termo de Ciência (modelo logo abaixo)<br>
								respeitar as datas limite para entrega.<br>
								respeitar as regras de envio e formatação.<br>
								respeitar os direitos humanos, dos animais e ambientais.
								</p>
								<hr>
								<h4>Áreas de submissão</h4>
								<p class="item-intro text-muted" align="justify">
								Os resumos devem ser enviados tendo como base a atividade desenvolvida e não
								necessariamente a área na qual se classifica o curso vinculado ao PET.<br>
								A área de submissão deve ser referente a qual o seu trabalho se enquadra. O assunto
								abordado é livre, podendo relatar atividades de ensino, pesquisa, extensão ou outros
								abordando, preferencialmente, a ligação da educação tutorial dentro desta atividade.
								</p>
								<p class="item-intro text-muted" align="justify">
								Por questões didáticas/avaliativas os trabalhos serão divididos nas seguintes
								áreas do conhecimento:<br><hr>
								Ciências Exatas e da Terra<br>
								Ciências Biológicas<br>
								Engenharias<br>
								Ciências da Saúde<br>
								Ciências Agrárias<br>
								Ciências Sociais Aplicadas<br>
								Ciências Humanas<br>
								Linguística, Letras e Artes<br>
								Multidisciplinares e Outros
								</p>
								<hr>
								<h4>Datas Importantes</h4>
								
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th>Descrição</th>
													<th>Deadline</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Prazo final para inscrição no evento </td>
													<td><?php 
													$ok = true;
													for($i=0;$i<count($dataFimInscricao);$i++)
													{
														if((strtotime(date("Y-m-d"))) > strtotime($dataFimInscricao[$i].' - 2 days'))
															echo '<strike>'.(date_format(date_create($dataFimInscricao[$i]),"d/m/Y")).'</strike> ';
														elseif($ok || $i == (count($dataFimInscricao)-1))
														{
															$ok = false;
															echo (date_format(date_create($dataFimInscricao[$i]),"d/m/Y")). ' ';
														}
													}
													
													?></td>
												</tr>
												<tr>
													<td>2</td>
													<td>Prazo final para submissão de trabalhos </td>
													<td><?php 
													$ok = true;
													for($i=0;$i<count($dataSubmissaoFim);$i++)
													{
														if((strtotime(date("Y-m-d"))) > strtotime($dataSubmissaoFim[$i].' - 2 days')&& $i != (count($dataSubmissaoFim)-1))
															echo '<strike>'.(date_format(date_create($dataSubmissaoFim[$i]),"d/m/Y")).'</strike> ';
														elseif($ok || $i == (count($dataSubmissaoFim)-1))
														{
															$ok = false;
															echo (date_format(date_create($dataSubmissaoFim[$i]),"d/m/Y")). ' ';
														}
													}
													?></td>
												</tr>
												<tr>
													<td>3</td>
													<td>Informe de aprovação ou de sugestões de correção da banca</td>
													<td><?php 
													$ok = true;
													for($i=0;$i<count($dataFinalAprovaBanca);$i++)
													{
														if((strtotime(date("Y-m-d"))) > strtotime($dataSubmissaoFim[$i].' - 2 days')&& $i != (count($dataSubmissaoFim)-1))
															echo '<strike>'.(date_format(date_create($dataFinalAprovaBanca[$i]),"d/m/Y")).'</strike> ';
														elseif($ok || $i == (count($dataFinalAprovaBanca)-1))
														{
															$ok = false;
															echo (date_format(date_create($dataFinalAprovaBanca[$i]),"d/m/Y")). ' ';
														}
													}
													?></td>
												</tr>
												<tr>
													<td>4</td>
													<td>Prazo final para reenvio de trabalhos notificados para correção </td>
													<td><?php
													$ok = true;
													for($i=0;$i<count($dataFinalReenvio);$i++)
													{
														if((strtotime(date("Y-m-d"))) > strtotime($dataSubmissaoFim[$i].' - 2 days')&& $i != (count($dataSubmissaoFim)-1))
															echo '<strike>'.(date_format(date_create($dataFinalReenvio[$i]),"d/m/Y")).'</strike> ';
														elseif($ok || $i == (count($dataFinalReenvio)-1))
														{
															$ok = false;
															echo (date_format(date_create($dataFinalReenvio[$i]),"d/m/Y")). ' ';
														}
													}
													?></td>
												</tr>
												<tr>
													<td>5</td>
													<td>Informe de aprovação do reenvio dos trabalhos </td>
													<td><?php 
													$ok = true;
													for($i=0;$i<count($dataFinalAprovaReenvio);$i++)
													{
														if((strtotime(date("Y-m-d"))) > strtotime($dataSubmissaoFim[$i].' - 2 days')&& $i != (count($dataSubmissaoFim)-1))
															echo '<strike>'.(date_format(date_create($dataFinalAprovaReenvio[$i]),"d/m/Y")).'</strike> ';
														elseif($ok || $i == (count($dataFinalAprovaReenvio)-1))
														{
															$ok = false;
															echo (date_format(date_create($dataFinalAprovaReenvio[$i]),"d/m/Y")). ' ';
														}
													}
													?></td>
												</tr>
												<tr>
													<td>6</td>
													<td>Data final para envio de Banners</td>
													<td><?php 
															echo (date_format(date_create($dataFinalBanner),"d/m/Y")). ' ';
														
													
													?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->
								</div>
								<!-- /.panel-body -->
								<hr>
								<h2>formatação</h2>
								<h4>Resumo</h4>
								<p class="item-intro text-muted" align="justify">
								Os resumos deverão conter o máximo de 300 palavras. O texto deve estar justificado e
								sem parágrafos e não devem conter imagens, tabelas ou gráficos.<br><hr>
								Fonte: Times New Roman;<br>
								Tamanho: 12;<br>
								Espaçamento entrelinhas simples;<br>
								Alinhamento do texto justificado.<br>
								Margem superior 3 cm,<br>
								Inferior 2 cm;<br>
								Direita 2 cm;<br>
								Esquerda 3 cm.
								</p>
								<hr>
								<h2>formatação</h2>
								<h4>Pôster</h4>
								<p class="item-intro text-muted" align="justify">
								O pôster deve ser confeccionado nas seguintes dimensões:<br><hr>
								Largura: 50 cm a 90 cm;<br>
								Altura: 80 cm a 120 cm.
								</p>
								<p class="item-intro text-muted" align="justify">
								Os pôsteres deverão ser autoexplicativos e conter o menor volume de texto possível,
								sendo permitido o uso de recursos visuais tais como figuras, fotos, tabelas, gráficos e
								quadros.<br>
								O pôster deverá conter:<br><hr>
								nome e logotipo do evento (XVI ENEPET);<br>
								título do trabalho (em destaque no topo do pôster);<br>
								abaixo do título: autores, co-autores e tutor, devendo ser apresentado por um dos
								autores;<br>
								o nome do curso se o PET é vinculado à graduação ou o tema (no caso de PET
								Temático);<br>
								a instituição de ensino e a área do trabalho.
								</p>
								<p class="item-intro text-muted" align="justify">
								É obrigatória a inclusão dos seguintes itens:<br><hr>
								Introdução;<br>
								Material e Métodos<br>
								Resultados e Discussão;<br>
								Referências.
								</p>
								<p class="item-intro text-muted" align="justify">
								No dia da apresentação um dos autores deverá estar ao lado do pôster para apresentação
								do trabalho no período determinado na programação do evento.
								</p>
								<hr>
								<h2>Modelos</h2>
								<hr>
								<p align="justify"><a href="modelo/termodeciencia.doc" class="btn btn-success"><i class="fa fa-file-word-o"></i> Modelo de Termo de Ciência</a></p>
								<p align="justify"><a href="modelo/banner.ppt" class="btn btn-success"><i class="fa fa-file-powerpoint-o"></i> Modelo de pôster</a>.</p>
								<p align="justify"><a href="modelo/regras.pdf" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Regras para submissão de trabalho em PDF</a>.</p>
								<hr>
								<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Fechar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
	
    <!-- Portfolio Modal 6 -->
    <div class="portfolio-modal modal fade" id="acompanhamento" tabindex="-1" role="dialog" aria-hidden="true">
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
								<h2>Acompanhamento de Inscrição e submissão de trabalho</h2>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="modal-body">
								
								<div class="panel-heading">
									<h3 class="panel-title">Login</h3>
								</div>
								<div class="panel-body">
									<form id="novoLogin">
										<fieldset>
											<div class="form-group">
												<input class="form-control" placeholder="CPF" id="cpf" name="cpf" type="text" autofocus>
											</div>
											<div class="form-group">
												<input class="form-control" placeholder="Senha" id="senha" name="senha" type="password" value="">
											</div>
											<!-- Change this to a button or input when using this as a form -->
											<button type="submit" class="btn btn-lg btn-success btn-block" id="acao" name="acao" value="logar">Entrar</button>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
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
    <script src="js/jquery.js"></script>

	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/agency.js"></script>
    <script src="js/script.js"></script>
	<!-- <script src="jquery.countdown-2.2.0/jquery.countdown.js"></script> -->
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	
	
</body>

</html>