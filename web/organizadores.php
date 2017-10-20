<?php


include_once 'inc/conexao.php';
include_once 'inc/config.php';
$recibo = filter_var($_GET['email'],FILTER_VALIDATE_EMAIL)?strtolower($_GET['email']):'';


if(!empty($recibo))
{
//gerar declaracao de aceite em pdf

	$rt = $con->prepare("SELECT nome,pet FROM tarefas WHERE (email = ?) limit 1");
	$rt->bindParam(1,$recibo);
	//verificar se o usuário existe na lista dos alunos
	if($rt->execute())
	{
		if($rt->rowCount() >= 1)
		{
			$arq = $rt->fetch(PDO::FETCH_OBJ);
			$nome = $arq->nome;
			$pet = $arq->pet;
			
			
			
		}elseif($rt->rowCount() == 0)
		{
			echo '<h1>Nenhum dado. Mandar mensagem para a ouvidoria do EVENTO.</h1>';
			exit(0);
		}
			
		function mes($mes)
		{
			switch($mes)
			{
				case 1: return 'Janeiro';
				case 2: return 'Fevereiro';
				case 3: return 'Março';
				case 4: return 'Abril';
				case 5: return 'Maio';
				case 6: return 'Junho';
				case 7: return 'Julho';
				case 8: return 'Agosto';
				case 9: return 'Setembro';
				case 10: return 'Outubro';
				case 11: return 'Novembro';
				case 12: return 'Dezembro';
			}
		
		}
		//Texto do certificado:
		/*
		//<p style="font-size: 27px;text-align: justify;">Certificamos que ANDRÉ FELÍCIO DE SOUSA SILVA, portador do CPF. nº 051.538.363-55, 
//		participou como membro ORGANIZADOR do I ECEEL - Encontro Científico de Engenharia Elétrica, realizado pelo grupo PET POTÊNCIA do curso de Engenharia Elétrica da Universidade Federal do Piauí. O evento ocorreu nos dias 11 e 12 de novembro de 2013, com carga horária de 20 horas.</p>de Federal do Piauí, na cidade de Teresina (PI)
		*/
$texto_cert = '
	<p style="font-size: 30px;text-align:center;">A Coordenação Geral do XVI ENEPET PIAUÍ 2017 confere o presente Certificado a</p>
	
	<p style="font-size: 37px;text-align:center;">'.mb_strtoupper( $nome,'utf8').',</p>
	<p style="font-size: 30px;text-align: justify;"></b>do <b> '.$pet.'</b>, por ter feito parte da ORGANIZAÇÃO do ENEPET PIAUÍ 2017 (XVI Encontro Nordestino dos PETs), realizado entre 20 e 23 de ABRIL de 2017, na Universidade Federal do Piauí, em Teresina.</p>
	<p style="font-size: 25px;text-align:center;"></p>
	<p style="font-size: 25px;text-align:center;">Teresina, '. date("d") .' de '.mes(date("m")).' de '. date("Y") .'.</p>';
		
		
		//AQUI COMEÇA O CERTIFICADO
		$html = '
<html>
<head>
	<link rel="stylesheet" href="img/style.css" media="screen">
<style>
html, body {
  height: 100%;
  width: 100%;
  padding: 0;
  margin: 0;
  font-family: Agency_fb;
}

p{
	text-align: justify;
}
#full-screen-background-image {
  z-index: -9999;
  min-height: 100%;
  min-width: 1024px;
  width: 100%;
  height: auto;
  position: fixed;
  top: 0;
  left: 0;
}
#wrapper
{
	position: fixed;
	z-index: 9999;
	margin: 120;
	top: 0;

}
.art-postheader
{
   color: #400202;
   margin: 10px 0 0 0;
   font-size: 40px;
   font-family: agency_fb;
   font-weight: normal;
   font-style: normal;
   text-align: center;
   text-transform: uppercase;
}
.art-content .art-postcontent-0 .layout-item-0 { padding-right: 25px;padding-left: 25px;  }
</style></head>
<body>
  <img alt="full screen background image" src="img/enepet2.jpg" id="full-screen-background-image" />
  <div id="wrapper">
<br><br><br><br><br>
<div class="art-content-layout">
	<div class="art-content-layout-row">
	<div class="art-layout-cell layout-item-0" style="width: 100%" >
	
	'.$texto_cert.'
							
	</div>
	</div>
</div>	
  </div>
</body></html>
';

		require_once("dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();
		$dompdf->load_html(utf8_decode($html));
		$dompdf->set_paper('A4','landscape');
		$dompdf->render();

		$dompdf->stream("cert_org_enepet2017_".$nome.".pdf");
		exit(0);
	

	}

}
?>
<html>
<head>
<title>Imprimir certificado - ENEPET 2017 </title>
</head>
<body>
<p><a href="http://enepet2017.com/admin/pages/divisaotrab.php" target="_blank">Lista completa dos organizadores</a></p>
<form>
<p>Digite aqui seu e-mail, por gentileza.</p>
<input name="email" type="email"> 

<input type="submit" value="Baixar certificado">

</form>

</body>

</html>
