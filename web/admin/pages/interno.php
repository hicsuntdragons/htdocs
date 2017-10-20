<?php 
require_once 'usuario.php';
require_once 'sessao.php';
require_once 'autenticador.php';
include_once '../inc/conexao.php';
$aut = Autenticador::instanciar();

$usuario = null;
if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
}
else {
    $aut->expulsar();
}

if(isset($_REQUEST['pagina']))
{
	$pagina = $_REQUEST['pagina'];
}else{
	$pagina = 0;
}

?>


<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.2.0.60623 -->
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Página interna</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all">


    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
    <script src="jquery.maskedinput-1.1.4.pack.js"></script>
	<script type="text/javascript">$(document).ready(function(){	$("#ncpf").mask("999.999.999-99");});</script>
	<script type="text/javascript">
	function validaCampo()
	{
	if(document.cadastro.nome.value=="")
		{
		alert("O Campo Nome Completo é obrigatório!");
		return false;
		}
	else
		if(document.cadastro.email.value=="")
		{
		alert("O Campo E-mail é obrigatório!");
		return false;
		}
	else
		if(document.cadastro.matricula.value=="")
		{
		alert("O Campo Matrícula é obrigatório!");
		return false;
		}
	else
		if(document.cadastro.cpf.value=="")
		{
		alert("O Campo Cpf é obrigatório!");
		return false;
		}
	else
	return true;
	}
	<!-- Fim do JavaScript que validarÃ¡ os campos obrigatÃ³rios! -->
	</script>

</head>
<body>
<div id="art-main">
<nav class="art-nav">
    <ul class="art-hmenu"><li><a href="controle.php?acao=sair" class="active">Sair</a></li></ul> 
    </nav>
<header class="art-header">

    <div class="art-shapes">
        
            </div>

<h1 class="art-headline">
    <a href="#">PET POTÊNCIA</a>
</h1>
<h2 class="art-slogan">Programa de Educação Tutorial - Eng. Elétrica</h2>





                
                    
</header>
<div class="art-sheet clearfix">
            <div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                <div class="art-postmetadataheader">
                                        <h2 class="art-postheader">Página interna do sistema</h2>
                                                            
                                    </div>
                                <div class="art-postcontent art-postcontent-0 clearfix">
								<p>Você está logado como 
									<strong><?php print $usuario->getNome(); ?></strong>.
								</p>
								<p><a href="controle.php?acao=sair">Sair</a></p>
<?php

switch($usuario->getTipo())
{
	case 'ADMIN':
	{
		switch($pagina)
		{
			case 1:{
			
			if(isset($_POST['submit'])){
				
				if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['tipo'])){
					$stmt = $con->prepare("INSERT INTO `usuarios`(`id`, `nome`, `email`, `senha`, `tipo`) VALUES ('',?,?,?,'CADASTRADOR')"); 
					$stmt->bindParam(1,$_POST['nome']);  
					$stmt->bindParam(2,$_POST['email']);  
					$stmt->bindParam(3,$_POST['senha']); 
					$stmt->execute();
					echo '<p>Sucesso! o nome '.$_POST['nome'].' foi cadastrado.</p>';
				}else{
				
					echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
				}
			
			
			}
			//CADASTRO DE USUÁRIOS DO SISTEMA
			echo '
				<h2>Cadastro de usuários do sistema.</h2>
				<p>Primeiro comece pelo usuário, e-mail, senha e Tipo.</p>
				<form action="" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
				
					<p><span style="color: rgb(41, 41, 41);">
					<label for="nome" style="display: inline-block; line-height: 25px;">Usuário</label>
					<input id="nome" name="nome" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
					</span><br></p>
					
					<p><span style="color: rgb(41, 41, 41);">
						<label for="email" style="display: inline-block; line-height: 25px;">Email</label>
						<input id="email" name="email" type="email" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
					</p>
					
					<p><span style="color: rgb(41, 41, 41);">
						<label for="senha" style="display: inline-block; line-height: 25px;">Senha</label><br>
						<input id="senha" name="senha" type="password" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
					</p>
					
					<p><span style="color: rgb(41, 41, 41);">
						<label for="tipo" style="display: inline-block; line-height: 25px;">Tipo</label><br>
						<input id="tipo" name="tipo" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
					</p>
					<br><br>

						<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Iniciar o cadastro" style="zoom: 1;"></span></p>
						
				</form>
				';
			
			}break;
		
			case 2:
			{
						echo '<h2>Selecione o evento:</h2>
									<form action="" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
									
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="evento" style="display: inline-block; line-height: 25px;">Selecione a turma desejada:</label><br>
											<select id="evento" name="evento" size="1">';
										
						$rt = $con->prepare("SELECT id,nome_curso,qde_insc_aprov,qde_inscritos FROM cursos order by inicio_curso desc");
						 
						 //verificar se o usuário existe na lista dos alunos
						 if($rt->execute()){
							if($rt->rowCount() == 0){ 
								
								echo '<option value="0">Nenhum curso/evento cadastrado.</option>';
								
							}else{
								while($row2 = $rt->fetch(PDO::FETCH_OBJ))
								{
									echo '<option value="'.$row2->id.'" '.(isset($_REQUEST['evento'])? ($_REQUEST['evento']==$row2->id ? 'selected' : '') : '').'>'.$row2->nome_curso.' - '.$row2->qde_insc_aprov.' inscrições pagas/'.$row2->qde_inscritos.' inscrições cadastradas</option>';
								}
							}
						}
												
							echo '
											</select><input name="submit" class="art-button" type="submit" value="Listar" style="zoom: 1;"></span></p>';
					
					if(isset($_REQUEST['submit']) && isset($_REQUEST['evento']))
					{
						if(limpar_string($_REQUEST['submit'])=='Listar')
						{
							$rs = $con->prepare("SELECT id,aluno,email,matricula,certificado,taxa_paga,presenca,iniciacao,titulo_pesquisa FROM lista_de_alunos where idcurso=? order by aluno asc");
							$rs->bindParam(1,$_POST['evento']);  
							 //verificar se o usuário existe na lista dos alunos
							 if($rs->execute()){
								if($rs->rowCount() == 0){
									
									echo '<p>nenhum aluno cadastrado! </p>';
									
								}else{
								echo '
								
										';
									$cont = 0;
									
									while($row = $rs->fetch(PDO::FETCH_OBJ))
									{
										$cont++;
										$aluno = $row->aluno;
										if($row->iniciacao)
										{
											echo '<b><p><input name="checkid[]" class="art-checkbox" type="checkbox" value="'.$row->id.'">'.$aluno.' | Matricula: '. $row->matricula .'</p></b>';
											echo '<p>Status: PROFESSOR/PALESTRANTE! Trabalho: '.$row->titulo_pesquisa.'</p>';
										}else{
										
											echo '<p><input name="checkid[]" class="art-checkbox" type="checkbox" value="'.$row->id.'">'.$aluno.' | Matricula: '. $row->matricula .'</p>';
											echo '<p>Status: '. ($row->taxa_paga?'Homologado':'Não homologado') .'</p>';
											echo '<p>Certificado emitido? '. ($row->certificado?'Sim':'Não ainda') .'</p>';
											echo '<p>Presenças? '. $row->presenca .'</p>';
											echo '<p>Trabalho cientifico: '. $row->titulo_pesquisa .'</p>';
										
										}
									}
									
							echo '
									<h2>Envio de email eletrônico em MASSA.</h2>
									<p>Primeiro comece pelo assunto e, por último, a mensagem.</p>
									
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="assunto" style="display: inline-block; line-height: 25px;">Assunto</label>
										<input id="asunto" name="assunto" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="texto" style="display: inline-block; line-height: 25px;">TEXTO ou notícia (TAGS HTML SÃO VÁLIDOS):</label>
											
											<textarea rows="20" cols="50" id="texto" name="texto" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></textarea></span>
										</p>
										
				';
								echo '
								
											<p><span style="color: rgb(41, 41, 41);">
												<label for="funcao" style="display: inline-block; line-height: 25px;">Selecione uma ação:</label><br>
												<select id="funcao" name="funcao" size="1">
													<option value="0">Escolha uma opção:</option>
													<option value="1">Homologar</option>
													<option value="2">Deletar</option>
													<option value="3">Marcar CHECKPOINT ou PRESENÇA</option>
													<option value="4">Enviar email para os marcados</option>
													<option value="5">Tornar os marcados ministrante de minicurso</option>
													<option value="6">Considerar os marcados palestrantes de trabalho oral/científico em eventos</option>
												</select><input name="submit" class="art-button" type="submit" value="Executar" style="zoom: 1;"></span></p>
										';
								}
							}
						}elseif(limpar_string($_REQUEST['submit'])=='Executar' && !empty($_REQUEST['funcao']))
						{
							if(limpar_numeros( $_POST['funcao']) == 1)
							{
							//HOMOLOGAR
							
								foreach($_POST['checkid'] as $value)
								{
									
									$up2 = $con->prepare("UPDATE `lista_de_alunos` SET `taxa_paga` = 1 WHERE taxa_paga = 0 AND `id` = ?");
									$up2->bindParam(1,$value);
									$erro=0;
									if($up2->execute() && $up2->rowCount())
									{
										echo $value;
										$up1 = $con->prepare("UPDATE `cursos` SET `qde_insc_aprov` = qde_insc_aprov+1 WHERE `id` = ?");
										$up1->bindParam(1,$_REQUEST['evento']);
										$up1->execute();
									}else
									{
										$erro++;
									}
								}
								if($erro){echo '<p>Algum aluno já pagou a inscrição. Mas mesmo assim a homologação foi realizada com sucesso!';} else { echo '<p>Homologação feita com sucesso!'; }
							}elseif(limpar_numeros( $_POST['funcao']) == 2)
							{
							//DELETAR
							
								foreach($_POST['checkid'] as $value)
								{
									$up5 = $con->prepare("SELECT taxa_paga FROM lista_de_alunos where id=?");
									$up5->bindParam(1,$value);
									 //verificar se o usuário existe na lista dos alunos
									 if($up5->execute()){
										if($up5->rowCount()){
										
											$up2 = $con->prepare("DELETE FROM `lista_de_alunos` WHERE `id` = ?");
											$up2->bindParam(1,$value);
											$erro=0;
											if($up2->execute() && $up2->rowCount())
											{
												$taxa_paga = $up5->fetch(PDO::FETCH_OBJ);
												if($taxa_paga->taxa_paga)
												{
													$up3 = $con->prepare("UPDATE `cursos` SET `qde_insc_aprov` = qde_insc_aprov-1,`qde_inscritos` = qde_inscritos-1  WHERE `id` = ?");
													$up3->bindParam(1,$_REQUEST['evento']);
													$up3->execute();
												}else
												{
													$up4 = $con->prepare("UPDATE `cursos` SET `qde_inscritos` = qde_inscritos-1  WHERE `id` = ?");
													$up4->bindParam(1,$_REQUEST['evento']);
													$up4->execute();
												}
											}else
											{
												$erro++; 
											}
										
										}
									}
								}
								if($erro){echo '<p>O procedimento foi feito porém deu erro em algum momento!</p>';} else { echo '<p>Aluno deletado com sucesso!</p>'; }
							}elseif(limpar_numeros( $_POST['funcao']) == 3)
							{
							//PRESENÇAS
								if(isset($_POST['checkid']))
								{
									$cont = 0;
									foreach($_POST['checkid'] as $value)
									{
										
										$up2 = $con->prepare("UPDATE `lista_de_alunos` SET `presenca` = presenca+1,`hora_presenca`=CURRENT_TIMESTAMP WHERE (hora_presenca=0 and `id` = ?) OR (DATE_ADD(hora_presenca, INTERVAL 1 DAY) < NOW() and `id`= ?)");
										$up2->bindParam(1,$value);
										$up2->bindParam(2,$value);
										$erro=0;
										if($up2->execute() && $up2->rowCount())
										{
											$cont++;
										}
									}
									echo '<p>'.$cont.' presenças efetuadas com sucesso!</p>';
								}else
								{
								
									echo '<p>Por favor, selecione os alunos!</p>';
								
								}
							}elseif(limpar_numeros( $_POST['funcao']) == 4){
								
								if(isset($_POST['submit']))
								{
								
									if(isset($_POST['checkid']))
									{
										if(!empty($_POST['assunto']) && !empty($_POST['texto']))
										{
											$to = 0;
											foreach($_POST['checkid'] as $value)
											{
												
												$up5 = $con->prepare("SELECT email FROM lista_de_alunos where id=?");
												$up5->bindParam(1,$value);
												 //verificar se o usuário existe na lista dos alunos
												 if($up5->execute()){
													if($up5->rowCount()){
														$dados = $up5->fetch(PDO::FETCH_OBJ);
														 
														// Passando os dados obtidos pelo formulário para as variáveis abaixo
														 
														$to      = ($to==0)? $dados->email : $to . ','. $dados->email;
														
													}
												}
											}
											if($to){
												$subject = 'PET POTENCIA - '.$_POST['assunto'];
												$message = $_POST['texto'].'Enviado via www.petpotencia.eng.br\nColoque este e-mail como confiável!\nBy Grupo Pet Potência do curso de Engenharia Elétrica da Universidade Federal do Piauí.';
												echo $usuario->getEmail();
												$headers = 'From: ' . $usuario->getNome() . ' <' . $usuario->getEmail() . '>' . "\n" .
													'Reply-To: ' . $usuario->getEmail();

												if(mail($to, $subject, $message, $headers)) echo 'certo';
												echo '<p>Email(s) enviado(s)!</p>';
											}else echo 'Nenhum contato selecionado.';
										}else
										{
											echo '<p>Por favor, preencha o formulário corretamente!</p>';
										}
									}else
									{
									
										echo '<p>Por favor, selecione os alunos!</p>';
									
									}
								}
							}elseif(limpar_numeros( $_POST['funcao']) == 5){
							
							
								if(isset($_POST['checkid']))
								{
									foreach($_POST['checkid'] as $value)
									{
										//imprimir certificado para professor/ministrante
										$up2 = $con->prepare("UPDATE `lista_de_alunos` SET `iniciacao`=1 , `certificado`=1 WHERE `id`= ?");
										$up2->bindParam(1,$value);
										$up2->execute();
									}
									echo $value .'<p>Golpe efetuado com sucesso!</p>';
								}else
								{
								
									echo '<p>Por favor, selecione os alunos!</p>';
								
								}
							
							}elseif(limpar_numeros( $_POST['funcao']) == 6){
							
								if(isset($_POST['checkid']))
								{
									foreach($_POST['checkid'] as $value)
									{
										//imprimir certificado para palestrante de trabalhos orais
										$up2 = $con->prepare("UPDATE `lista_de_alunos` SET iniciacao=2 WHERE `id`=?");
										$up2->bindParam(1,$value);
										$up2->execute();
									}
									echo '<p>Golpe efetuado com sucesso!</p>';
								}else
								{
								
									echo '<p>Por favor, selecione os alunos!</p>';
								
								}
							
							
							}elseif(limpar_numeros( $_POST['funcao']) == 7){
							
							
							
							}else{echo '<p>Selecione a função desejada!</p>';}
						}
					}
					echo '
											
									</form>';
			
			}
			break;
			
			case 3:
			{
				if(isset($_POST['submit'])){
					
					if(isset($_POST['titulo']) && isset($_POST['subtitulo']) && isset($_POST['texto'])){

						
						$diretorio = '../images/noticias/';
						$novo_nome = md5(uniqid(time()));
							
						foreach($_FILES['imagem']['tmp_name'] as $key => $value)
						{
							$nome_original = $diretorio .'original_'. $key .'_'. $novo_nome.'.jpg';
							
							$nome_menor = $diretorio .'menor_'. $key .'_'. $novo_nome.'.jpg';
							$nome_thumb = $diretorio .'thumb_'. $key .'_'. $novo_nome.'.jpg';
							
							if (move_uploaded_file($value, $nome_original)) {
								print "A imagem ".($key+1)." é válido e foi carregado com sucesso. Aqui está alguma informação:\n";
								
								// Pega o tamanho original da imagem e armazena em um Array:
								$size = getimagesize( $nome_original );
								 
								// Configura a nova largura da imagem:
								
								$thumb_width = 500;
								 
								// Calcula a altura da nova imagem para manter a proporção na tela: 
								$thumb_height = ( int )(( $thumb_width/$size[0] )*$size[1] );
								 
								// Cria a imagem com as cores reais originais na memória.
								$thumbnail = ImageCreateTrueColor( $thumb_width, $thumb_height );
								$thumbnail2 = ImageCreateTrueColor( 500, 400 );
								 
								// Criará uma nova imagem do arquivo.
								$src_img = ImageCreateFromJPEG( $nome_original );
								 
								// Criará a imagem redimensionada:
								ImageCopyResampled( $thumbnail, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1] );
								ImageCopyResampled( $thumbnail2, $src_img, 0, (400-$thumb_height)/2, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1] );
								 
								// Informe aqui o novo nome da imagem e a localização:
								ImageJPEG( $thumbnail, $nome_menor );
								ImageJPEG( $thumbnail2, $nome_thumb );
								 
								// Limpa da memoria a imagem criada temporáriamente: 
								ImageDestroy( $thumbnail );
								ImageDestroy( $thumbnail2 );
								
								$foto[] = $key .'_'. $novo_nome.'.jpg';
								
								$comentario[] = $_POST['comentario'.($key+1)];
							} else {
								$foto[] = 'vazio.jpg';
								$comentario[] = 'vazio';
							}
						}
						$autor = $usuario->getNome();
						$textoarea = nl2br($_POST['texto']);
						
						$stmt = $con->prepare("INSERT INTO `paginas`(`id`,`data`, `titulo`, `subtitulo`, `texto_noticia`, `autor`, `foto1`, `comentario1`, `foto2`, `comentario2`, `visualizacoes`, `menu`, `ancora`) 
						VALUES (NULL,CURRENT_TIMESTAMP,?,?,?,?,?,?,?,?,0,?,?)");  
						$stmt->bindParam(1,$_POST['titulo']);  
						$stmt->bindParam(2,$_POST['subtitulo']); 
						$stmt->bindParam(3,$textoarea);
						$stmt->bindParam(4,$autor);
						$stmt->bindParam(5,$foto[0]);
						$stmt->bindParam(6,$comentario[0]);
						$stmt->bindParam(7,$foto[1]);
						$stmt->bindParam(8,$comentario[1]);
						$stmt->bindParam(9,$_POST['menu']);
						$stmt->bindParam(10,$_POST['ancora']);
						
						if($stmt->execute())
						{
						
							echo '<p>Sucesso! A notícia de título: '.$_POST['titulo'].' foi cadastrada.</p>';
						
						}else{
						
							echo '<p>erro! A notícia de título: '.$_POST['titulo'].' foi cancelada.</p>';
							
							@unlink($diretorio . 'original_0_'.$novo_nome.'.jpg');
							@unlink($diretorio . 'menor_0_'.$novo_nome.'.jpg');
							@unlink($diretorio . 'original_1_'.$novo_nome.'.jpg');
							@unlink($diretorio . 'menor_1_'.$novo_nome.'.jpg');
							@unlink($diretorio . 'thumb_0_'.$novo_nome.'.jpg');
							@unlink($diretorio . 'thumb_1_'.$novo_nome.'.jpg');
						}
						
					}else{
					
						echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
					}
				
				
				}else

				
				//CADASTRO DE NOTÍCIAS
				echo '
									<h2>Cadastro de NOTÍCIAS do sistema.</h2>
									<p>Primeiro comece pelo TÍTULO, SUBTÍTULO, texto e foto.</p>
									<form action="" enctype="multipart/form-data" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
									
										<p><span style="color: rgb(41, 41, 41);">
										<label for="titulo" style="display: inline-block; line-height: 25px;">TÍTULO</label>
										<input id="titulo" name="titulo" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="subtitulo" style="display: inline-block; line-height: 25px;">Subtítulo</label>
										<input id="subtitulo" name="subtitulo" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="texto" style="display: inline-block; line-height: 25px;">TEXTO ou notícia (TAGS HTML SÃO VÁLIDOS):</label>
											
											<textarea rows="20" cols="50" id="texto" name="texto" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></textarea></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="imagem1" style="display: inline-block; line-height: 25px;">imagem 1</label><br>
											<input id="imagem1" name="imagem[0]" type="file" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="comentario1" style="display: inline-block; line-height: 25px;">Comentário da imagem 1</label><br>
											<input id="comentario1" name="comentario1" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="imagem2" style="display: inline-block; line-height: 25px;">imagem 2</label><br>
											<input id="imagem2" name="imagem[1]" type="file" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="comentario2" style="display: inline-block; line-height: 25px;">Comentário da imagem 2</label><br>
											<input id="comentario2" name="comentario2" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
										</p>
										<p><span style="color: rgb(41, 41, 41);">
											<label for="menu" style="display: inline-block; line-height: 25px;">Selecione a página</label><br>
											<select id="menu" name="menu" size="1">
												<option value="noticias">Notícias</option>
												<option value="inicio">Início</option>
												<option value="opet">O pet</option>
												<option value="ogrupo">O grupo</option>
												<option value="ensino">Ensino</option>
												<option value="pesquisa">Pesquisa</option>
												<option value="extensao">Extensão</option>
												<option value="integracao">Integração</option>
												<option value="downloads">Downloads</option>
												<option value="eceel">Eceel</option>
												<option value="minicursos">Minicursos</option>
												<option value="tutoria">Tutoria</option>
												<option value="producao">Produção</option>
												<option value="selecao">Seleção</option>
												<option value="contato">Contato</option>
											</select></span>
										</p>
										<p><span style="color: rgb(41, 41, 41);">
											<label for="ancora" style="display: inline-block; line-height: 25px;">página-âncora?</label><br>
											<select id="ancora" name="ancora" size="1">
												<option value="0">Não</option>
												<option value="1">Sim</option>
											</select></span>
										</p>
										<br><br>

											<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Iniciar o cadastro" style="zoom: 1;"></span></p>
											
									</form>
				';
			
			}
			break;
			case 4:
			{
				if(isset($_POST['submit'])){
					
					if(isset($_POST['titulo']) && isset($_POST['comentario']) && isset($_FILES['arquivo'])){

						
						$diretorio = '../download/';
						$novo_nome = md5(uniqid(time()));
						
						$caminho = $_FILES['arquivo']['tmp_name'];
						$caminho2 = $_FILES['arquivo']['name'];
						$tamanho = ($_FILES['arquivo']['size'])/1048576; // tamanho em megabytes
						$infos = pathinfo($caminho2);
						$arquivo = $diretorio .'arquivo_'. $novo_nome.'.'.$infos['extension'];
						//move_uploaded_file($value, $nome_original)
						$titulo_arq = $_POST['titulo'] . ' - <b>'. $tamanho . ' megabytes.</b>';
						 
						if(!move_uploaded_file($caminho, $arquivo))exit(0);
						$autor = $usuario->getNome();
						$infos = pathinfo($arquivo);
						$stmt = $con->prepare("INSERT INTO `downloads`(`id`,`arquivo`, `comentario`, `autor`, `link`, `data`, `exibir`)
						VALUES (NULL,?,?,?,?,CURRENT_TIMESTAMP,?)");
						$stmt->bindParam(1,$titulo_arq);
						$stmt->bindParam(2,$_POST['comentario']); 
						$stmt->bindParam(3,$autor);
						$stmt->bindParam(4,$infos['basename']);
						$stmt->bindParam(5,$_POST['disponivel']);
						
						if($stmt->execute())
						{
						
							echo '<p>Sucesso! Arquivo enviado. O link é petpotencia.eng.br/download/'.$infos['basename'].'</p>';
							echo '<p>Utilize esta tag html para inserir link: </p><blockquote>&lt;a href=&quot;petpotencia.eng.br/download/'.$infos['basename'].'&quot;&gt;'.$titulo_arq.'&lt;/a&gt;</blockquote>';
						
						}else{
						
							echo '<p>erro! Arquivo não inserido..</p>';
							
							@unlink($diretorio . $arquivo);
						}
						
					}else{
					
						echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
					}
				
				
				}else

				
				//CADASTRO DE DOWNLOADS
				echo '
									<h2>Cadastro de arquivos do sistema.</h2>
									<p>Primeiro comece pelo nome do arquivo, comentário, arquivo.</p>
									<form action="" enctype="multipart/form-data" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
									
										<p><span style="color: rgb(41, 41, 41);">
										<label for="titulo" style="display: inline-block; line-height: 25px;">Nome do arquivo</label>
										<input id="titulo" name="titulo" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="comentario" style="display: inline-block; line-height: 25px;">Comentário</label>
										<input id="comentario" name="comentario" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="imagem1" style="display: inline-block; line-height: 25px;">Arquivo</label><br>
											<input id="imagem1" name="arquivo" type="file" value="" size="30" style="margin-right: auto; margin-left: auto; box-sizing: border-box; background-repeat-x: no-repeat; background-repeat-y: no-repeat;"></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="disponivel" style="display: inline-block; line-height: 25px;">Disponibilizar?</label><br>
											<select id="disponivel" name="disponivel" size="1">
												<option value="0">Não</option>
												<option value="1">Sim</option>
											</select></span>
										</p>
										<br><br>

											<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Iniciar o cadastro" style="zoom: 1;"></span></p>
											
									</form>
				';
			}
			break;
			case 5:
			{
				if(isset($_POST['submit'])){
					
					if(isset($_POST['nome_curso']) && isset($_POST['fim_inscricao']) && isset($_POST['certificado_horas']) && isset($_POST['inicio_curso']) && isset($_POST['dias_curso']) && !empty($_POST['qde_presencas'])){

						
						//$diretorio = '../images/';
						//$novo_nome = md5(uniqid(time()));
						
						//$autor = $usuario->getNome();
						//$textoarea = nl2br($_POST['texto']);
						
						$stmt = $con->prepare("INSERT INTO `cursos` (`id`, `nome_curso`, `menu`, `qde_alunos`, `qde_presenca`, `taxa`, `certificado_horas`, `inicio_curso`, `dias_curso`, `qde_inscritos`, `qde_insc_aprov`, `fim_inscricao`, `publico_alvo`, `checkpoint`, `questionario`) 
						VALUES (NULL, ?, ?, ?, ?, ?, ?, DATE_ADD(now(), INTERVAL ? DAY), ?, 0, 0, DATE_ADD(now(), INTERVAL ? DAY), ?, ?,?)"); 
						//             1    2    3    4    5    6                            7        8                                      9        10   11
						$stmt->bindParam(1,$_POST['nome_curso']); 
						$stmt->bindParam(2,$_POST['menu']);  
						$stmt->bindParam(3,$_POST['qde_alunos']);
						$stmt->bindParam(4,$_POST['qde_presencas']);
						$stmt->bindParam(5,$_POST['taxa']);  
						$stmt->bindParam(6,$_POST['certificado_horas']); 
						$stmt->bindParam(7,$_POST['inicio_curso']); 
						$stmt->bindParam(8,$_POST['dias_curso']); 
						$stmt->bindParam(9,$_POST['fim_inscricao']); 
						$stmt->bindParam(10,$_POST['publico_alvo']); 
						$stmt->bindParam(11,$_POST['check']);
						$stmt->bindParam(12,$_POST['quest']);
						
						if($stmt->execute())
						{
						
							echo '<p>Sucesso! O curso de título: '.$_POST['nome_curso'].' foi cadastrado.</p>';
						
						}else{
						
							echo '<p>erro! O curso de título: '.$_POST['nome_curso'].' foi cancelado.</p>';
							
						}
						
					}else{
					
						echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
					}
				
				
				}else

				
				//CADASTRO DE MINICURSOS
				echo '
									<h2>Cadastro de minicursos e de eventos</h2>
									<form action="" enctype="multipart/form-data" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
									
										<p><span style="color: rgb(41, 41, 41);">
										<label for="nome_curso" style="display: inline-block; line-height: 25px;">Nome do minicurso/evento</label>
										<input id="nome_curso" name="nome_curso" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 600px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="fim_inscricao" style="display: inline-block; line-height: 25px;">Número de dias de inscrições</label>
										
											<select id="fim_inscricao" name="fim_inscricao" size="1">
												<option value="0">Escolha</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
												<option value="11">11</option>
												<option value="12">12</option>
												<option value="13">13</option>
												<option value="14">14</option>
												<option value="15">15</option>
												<option value="16">16</option>
												<option value="17">17</option>
												<option value="18">18</option>
												<option value="19">19</option>
												<option value="20">20</option>
												<option value="25">25</option>
												<option value="30">30</option>
												<option value="40">40</option>
												<option value="50">50</option>
												<option value="60">60</option>
											</select></span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="menu" style="display: inline-block; line-height: 25px;">Inscrições para</label>
											<select id="menu" name="menu" size="1">
												<option value="eceel">ECEEL</option>
												<option value="minicursos">MINICURSOS</option>
												<option value="integracao">Integração</option>
												<option value="selecao">Seleção de novos petianos</option>
											</select></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="qde_presencas" style="display: inline-block; line-height: 25px;">Quantidade mínima de presenças para emissão do certificado</label>
											<select id="qde_presencas" name="qde_presencas" size="1">
												<option value="0">Escolha</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
												<option value="11">11</option>
												<option value="12">12</option>
												<option value="13">13</option>
												<option value="14">14</option>
												<option value="15">15</option>
											</select></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="qde_alunos" style="display: inline-block; line-height: 25px;">Máxima quantidade de inscrições:</label>
											<select id="qde_alunos" name="qde_alunos" size="1">
												<option value="1000">Não se aplica</option>
												<option value="10">10</option>
												<option value="20">20</option>
												<option value="30">25</option>
												<option value="30">30</option>
												<option value="30">35</option>
												<option value="40">40</option>
												<option value="40">50</option>
												<option value="100">100</option>
												<option value="200">250</option>
												<option value="300">300</option>
												<option value="500">500</option>
											</select></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="taxa" style="display: inline-block; line-height: 25px;">Cobrar taxa de inscrição?</label>
											<select id="taxa" name="taxa" size="1">
												<option value="0">Não</option>
												<option value="1">Sim</option>
											</select></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="check" style="display: inline-block; line-height: 25px;">Sistema de Checklist no dia do evento?</label>
											<select id="check" name="check" size="1">
												<option value="0">Não</option>
												<option value="1">Sim</option>
											</select></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="publico_alvo" style="display: inline-block; line-height: 25px;">Publico-alvo</label>
											<select id="publico_alvo" name="publico_alvo" size="1">
												<option value="1">Curso/evento para todos</option>
												<option value="2">somente para os alunos da engenharia elétrica.</option>
												<option value="3">Curso somente para algum evento aberto disponível.</option>
											</select></span>
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="certificado_horas" style="display: inline-block; line-height: 25px;">Certificado - Quantidade de horas</label>
										
											<select id="certificado_horas" name="certificado_horas" size="1">
												<option value="2">2</option>
												<option value="4">4</option>
												<option value="6">6</option>
												<option value="8">8</option>
												<option value="10">10</option>
												<option value="12">12</option>
												<option value="14">14</option>
												<option value="16">16</option>
												<option value="18">18</option>
												<option value="20">20</option>
											</select></span><br>
										
										</p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="inicio_curso" style="display: inline-block; line-height: 25px;">Início Curso (Daqui a quantos dias para o começo do curso)</label>
										<input id="inicio_curso" name="inicio_curso" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 200px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="quest" style="display: inline-block; line-height: 25px;">Questionário para avaliação número</label>
										<input id="quest" name="quest" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 200px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
										</span><br></p>
										
										<p><span style="color: rgb(41, 41, 41);">
										<label for="dias_curso" style="display: inline-block; line-height: 25px;">Número de dias Curso</label>
										
										
											<select id="dias_curso" name="dias_curso" size="1">
												<option value="0">Escolha</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
											</select></span><br></p>
										
										<br><br>

											<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Iniciar o cadastro" style="zoom: 1;"></span></p>
											
									</form>
				';
			}
			break;
			case 6:
			
			if(isset($_POST['submit'])){
				
				if(isset($_POST['questoes'])){
				
					$rs = $con->prepare("SELECT numero FROM questoes order by id desc limit 1");
					$rs->bindParam(1,$questao);
					if($rs->execute()){
						$qde_questoes = $rs->rowCount();
						if($qde_questoes > 0){ 
							$row = $rs->fetch(PDO::FETCH_OBJ);
							$numero = $row->numero;
							$numero++;
							foreach($_POST['questoes'] as $value)
							{
								if(!empty($value))
								{
									$stmt = $con->prepare("INSERT INTO `questoes`(`id`, `numero`, `pergunta`) VALUES ('',?,?)"); 
									$stmt->bindParam(1,$numero);  
									$stmt->bindParam(2,$value);
									$stmt->execute();
								}
							}
						}
					}
					echo '<p>Sucesso! As questões de avaliaçao numero '.$numero.' foram cadastradadas.</p>';
				}else{
				
					echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
				}
			
			
			}
			//CADASTRO DE questões para avaliação
			echo '
				<h2>Cadastro de questões para avaliação.</h2>
				<p>Preencha os campos abaixo com as perguntas desejadas. Máximo de 30 perguntas. Pode-se deixar questões em branco.</p>
				<form action="" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">';
				for($i=0;$i<30;$i++)
				{
				echo '
					<p><span style="color: rgb(41, 41, 41);">
					<label for="questoes" style="display: inline-block; line-height: 25px;">Questão '. ($i+1) .'</label>
					<input id="questoes" name="questoes[]" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
					</span><br></p>';
				}
			echo '		
					<br><br>

						<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Iniciar o cadastro" style="zoom: 1;"></span></p>
						
				</form>
				';
			break;
			case 7:
			
			if(isset($_POST['submit'])){
				
				if(isset($_POST['funcao'])){
				
					echo '<p>Lista de questões do Questionário '.$_POST['funcao'].'</p>';
					$rs = $con->prepare("SELECT * FROM questoes WHERE numero=?");
					$rs->bindParam(1,$_POST['funcao']);
					if($rs->execute()){
						$qde_questoes = $rs->rowCount();
						if($qde_questoes > 0){
							while($row2 = $rs->fetch(PDO::FETCH_OBJ))
							{
								
							echo '
							<ul><li><blockquote>'. $row2->pergunta. ' </blockquote></li></ul>
							';
							}
						}
					}
				}else{
				
					echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
				}
			
			
			}
			//CADASTRO DE questões para avaliação
			echo '
				<h2>Questões para avaliação.</h2>
				<p>Visualize as questões para avaliação. Não tem como altera-las.</p>
				<form action="" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
						
											<p><span style="color: rgb(41, 41, 41);">
												<label for="funcao" style="display: inline-block; line-height: 25px;">Selecione uma ação:</label><br>
												<select id="funcao" name="funcao" size="1">';
						$rt = $con->prepare("SELECT * FROM questoes order by id desc limit 1");
						 
						 //verificar se o usuário existe na lista dos alunos
						 if($rt->execute()){
							if($rt->rowCount() == 0){ 
								
								echo '<option value="0">Nenhuma questão cadastrada.</option>';
								
							}else{
								$row2 = $rt->fetch(PDO::FETCH_OBJ);
								for($i=0;$i<=$row2->numero;$i++)
								{
									echo '<option value="'.$i.'">Questionário '.$i.'</option>';
								}
							}
						}
			echo '		</select></span></p>
					<br><br>

						<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Listar questões" style="zoom: 1;"></span></p>
						
				</form>
				';
			break;
			case 8:
			
			if(isset($_POST['submit'])){
				
				if(isset($_POST['questoes'])){
				
					$rs = $con->prepare("SELECT numero FROM questoes order by id desc limit 1");
					$rs->bindParam(1,$questao);
					if($rs->execute()){
						$qde_questoes = $rs->rowCount();
						if($qde_questoes > 0){ 
							$row = $rs->fetch(PDO::FETCH_OBJ);
							$numero = $row->numero;
							$numero++;
							foreach($_POST['questoes'] as $value)
							{
								if(!empty($value))
								{
									$stmt = $con->prepare("INSERT INTO `questoes`(`id`, `numero`, `pergunta`) VALUES ('',?,?)"); 
									$stmt->bindParam(1,$numero);  
									$stmt->bindParam(2,$value);
									$stmt->execute();
								}
							}
						}
					}
					echo '<p>Sucesso! As questões de avaliaçao numero '.$numero.' foram cadastradadas.</p>';
				}else{
				
					echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
				}
			
			
			}
			//CADASTRO DE questões para avaliação
			echo '
				<h2>Arquivos enviados ao servidor</h2>
				<p>Pegue o link do arquivo e jogue nas notícias com as tags html.</p>
				<form action="" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">';
				
				$form = $con->prepare("SELECT * FROM downloads order by id desc");
				if($form->execute()){
					if($form->rowCount() > 0){
						while($arquivos = $form->fetch(PDO::FETCH_OBJ))
						{
						
							echo '
							<ul><li><a href="download/' .$arquivos->link .'">'.$arquivos->arquivo.'.</a><br />Comentário: '. $arquivos->comentario. '<br />Enviado por: '. $arquivos->autor .'.<br />Data de envio: '. $arquivos->data.'<br /></p>Tag html: <blockquote>&lt;a href=&quot;petpotencia.eng.br/download/'.$arquivos->link.'&quot;&gt;'.$arquivos->arquivo.'&lt;/a&gt;</blockquote> </li></ul>
							';
							
						}
					}
				}
			echo '		
					<br><br>

						<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Deletar selecionados" style="zoom: 1;"></span></p>
						
				</form>
				';
			break;
		}
?>


		<div class="art-content-layout layout-item-0">
			<div class="art-content-layout-row">
			<div class="art-layout-cell layout-item-1" style="width: 20%">
				<p style="font-size:18px;">Criar</p><br>
				<ul>
					<li><a href="?pagina=1">Cadastro de usuários</a></li>
					<li><a href="?pagina=4">CADASTRO DE DOWNLOADS</a></li>
					<li><a href="?pagina=3">LANÇAR NOTÍCIAS NO SITE</a></li>
					<li><a href="?pagina=5">ABRIR NOVAS INSCRIÇÕES PARA ECEEL ou MINICURSO</a></li>
					<li><a href="?pagina=6">Questionário para avaliação</a></li>
				</ul>
			</div>
			<div class="art-layout-cell layout-item-1" style="width: 18%">
				<p style="font-size:18px;">Alterar/Deletar</p><br>
				<ul>
					<li><a href="#">NOTÍCIAS</a></li>
					<li><a href="#">USUÁRIOS</a></li></li>
				</ul>
			</div>
			<div class="art-layout-cell layout-item-1" style="width: 18%">
				<p style="font-size:18px;">Listar</p><br>
				<ul>
					<li><a href="?pagina=2">CONSULTAR TURMAS</a></li>
					<li><a href="?pagina=7">Questionário para avaliação</a></li>
					<li><a href="?pagina=8">Arquivos enviados</a></li>
				</ul>
			</div>
			</div>
		</div>

<?php

	}
	break;
	case 'CADASTRADOR':{

		if(isset($_POST['submit'])){
			
			if(isset($_POST['nome'])){
			$nome = strtoupper(limpar_string($_POST['nome']));
					if(!empty($nome))
					{
						$rs = $con->prepare("SELECT aluno FROM lista_de_alunos WHERE aluno = ? order by aluno asc");
						$rs->bindParam(1, $nome );
				 
						 //verificar se o usuário existe na lista dos alunos
						 if($rs->execute()){
							if($rs->rowCount() == 0){


									$stmt = $con->prepare("INSERT INTO `lista_de_alunos`(`id`, `aluno`, `email`, `matricula`, `numerocpf`) VALUES ('',?,NULL,NULL,NULL)"); 
									$stmt->bindParam(1,$nome);  
									$stmt->execute();
									echo '<p>Sucesso! o '.$nome.' foi cadastrado.</p>';
								
							}
						}	
					}else{
					
						echo '<p>Nome inválido</p>';
					}
					
				}else{
				
					echo '<p>Campo(s) vazio(s)! Tente novamente</p>';
				}
		
		}
?>


								<h2>Cadastre os alunos participantes.</h2>
								<form action="interno.php" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
								
									<p><span style="color: rgb(41, 41, 41);">
									<label for="nome" style="display: inline-block; line-height: 25px;">NOME COMPLETO</label>
									<input id="nome" name="nome" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 948px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
									</span><br></p>
									
									<br><br>

										<p class="comment-form-comment" style="margin-top: 0px; margin-bottom: 0px;"><span style="color: rgb(41, 41, 41);"><input name="submit" class="art-button" type="submit" value="Iniciar o cadastro" style="zoom: 1;"></span></p>
										
								</form>

	<?php
		echo '<h2>Lista de alunos cadastrados nos minicursos:</h2>';
		 $rs = $con->prepare("SELECT aluno,certificado FROM lista_de_alunos order by aluno asc");
	 
	 //verificar se o usuário existe na lista dos alunos
	 if($rs->execute()){
		if($rs->rowCount() == 0){
			
			echo '<p>nenhum aluno cadastrado! </p>';
			
		}else{
			$cont = 0;
			while($row = $rs->fetch(PDO::FETCH_OBJ))
			{
				$cont++;
				$aluno = $row->aluno;
				$cert = $row->certificado;
				$fez = $cert==true?'Sim':'Não';
				echo '<p>'.$cont.' - '.$aluno.' | Imprimiu certificado? '.$fez.'</p>';
			}
		}
	}


	}
	break;
	case 'CHECKPOINT':{
			
						echo '<h5>CHECKPOINT <B>'.$usuario->getNome().'</B></h5>
									<form action="" method="post" id="cadastro" name="cadastro" onsubmit="return validaCampo(); return false;">
									
										
										<p><span style="color: rgb(41, 41, 41);">
											<label for="evento" style="display: inline-block; line-height: 25px;">Selecione:</label><br>
											<select id="evento" name="evento" size="1">';
										
						$rt = $con->prepare("SELECT id,nome_curso FROM cursos WHERE (now() between inicio_curso and DATE_ADD(inicio_curso, INTERVAL dias_curso+1 DAY)) AND checkpoint=1 order by nome_curso asc");
						 
						 //verificar se o usuário existe na lista dos alunos
						 if($rt->execute()){
							if($rt->rowCount() == 0){ 
								
								echo '<option value="0">Nenhum evento disponível neste momento.</option>';
								
							}else{
								while($row2 = $rt->fetch(PDO::FETCH_OBJ))
								{
									echo '<option value="'.$row2->id.'" '.(isset($_REQUEST['evento'])? ($_REQUEST['evento']==$row2->id ? 'selected' : '') : '').'>'.$row2->nome_curso.'</option>';
								}
							}
						}
												
							echo '
											</select></span></p>
											';
											if($rt->rowCount())
											echo '
											
											<p><span style="color: rgb(41, 41, 41);">
											<label for="ncpf" style="display: inline-block; line-height: 25px;">Digite o seu CPF:</label>
											<input id="ncpf" name="ncpf" type="text" value="" size="30" style="margin-right: auto; margin-left: auto; width: 200px; box-sizing: border-box; max-width: 100%; background-repeat-x: no-repeat; background-repeat-y: no-repeat;">
											</span><input name="submit" class="art-button" type="submit" value="Marcar checkpoint" style="zoom: 1;"></p>
											
											';
					
					if(isset($_REQUEST['submit']) && isset($_REQUEST['evento']) && isset($_REQUEST['ncpf']))
					{
						//PRESENÇAS
						$idcurso = limpar_numeros($_REQUEST['evento']);
						$cpf = validaCPF($_POST['ncpf']);
						if($cpf && $idcurso)
						{
							$cpf = retirar_letras_e_pontos($_POST['ncpf']);
								$up2 = $con->prepare("UPDATE `lista_de_alunos` SET `presenca` = presenca+1,`hora_presenca`=CURRENT_TIMESTAMP WHERE (hora_presenca=0 and `numerocpf` = ? and `idcurso` = ?) OR (DATE_ADD(hora_presenca, INTERVAL 1 HOUR) < NOW() and `numerocpf`= ? and `idcurso` = ?)");
								$up2->bindParam(1,$cpf);
								$up2->bindParam(2,$idcurso);
								$up2->bindParam(3,$cpf);
								$up2->bindParam(4,$idcurso);
								if($up2->execute() && $up2->rowCount())
								{
									echo '<p>Presença efetuada com sucesso! Aguardar 1h para efetuar uma nova presença.</p>';
								}else{echo '<p>Favor, aguardar 1h para efetuar uma nova presença ou usuário não cadastrado.</p>';}
						}else
						{
						
							echo '<p>Por favor, Verifique se o seu cpf está correto. Tente novamente!</p>';
						
						}
					}
					echo '
											
									</form>';
			
			}

}
?>
								
								<p><br></p>
								
								</div>
                                
                

</article></div>
                    </div>
                </div>
            </div><footer class="art-footer">
<div class="art-content-layout layout-item-0">
    <div class="art-content-layout-row">
    <div class="art-layout-cell layout-item-1" style="width: 20%">
        <p style="font-size:18px;">Pet</p><br><ul><li><a href="#">Bem-Vindo</a></li><li><a href="#">Pessoas</a></li><li><a href="#">Management</a></li></ul>
    </div><div class="art-layout-cell layout-item-1" style="width: 18%">
        <p style="font-size:18px;">Site</p><br><ul><li><a href="#">Map</a></li><li><a href="#">Address</a></li><li><a href="#">Contact Us</a></li></ul>
    </div><div class="art-layout-cell layout-item-1" style="width: 25%">
        <p style="font-size:18px;">O que é o PET?</p><br><ul><li><a href="#">Company</a></li><li><a href="#">Terms</a></li></ul>
    </div><div class="art-layout-cell" style="width: 37%">
        <p style="text-align:right;"><br></p><p style="text-align:right;"><img width="32" height="32" alt="" src="images/rss_32-2.png" style="margin:5px;" class=""><img width="32" height="32" alt="" src="images/picasa_32-2.png" style="margin:5px;" class=""><img width="32" height="32" alt="" src="images/facebook_32-2.png" style="margin:5px;" class=""><img width="32" height="32" alt="" src="images/flickr_32-2.png" style="margin:5px;" class=""><br><a href="http://artdesigner.lv"></a></p><p style="text-align: right;"><span style="text-align: right;"><br></span></p><p style="text-align: right;"><span style="text-align: right;">Acesso restrito: <a href="#" title="Ethernet">Admin</a></span><br></p><p style="text-align: right;"><br></p><p style="text-align: right;"><br></p>
    </div>
    </div>
</div>

</footer>

    </div>
    <p class="art-page-footer">
        <span id="art-footnote-links">Designed by <a href="http://fb.com/francisco.marcolino" target="_blank">Francisco Marcolino</a>.</span>
    </p>
</div>


</body></html>