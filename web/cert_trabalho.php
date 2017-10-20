<?php


include_once 'inc/conexao.php';
include_once 'inc/config.php';
$idtrab = isset($_GET['idtrab'])?$_GET['idtrab']:'';

//limpar máscara
$idtrab = preg_replace('/[^0-9]/', '', $idtrab);

if(!empty($idtrab))
{
//gerar declaracao de aceite em pdf
//verificar se existe algum trabalho enviado
	//pegar informações dos trabalhos deste autor
	$ru = $con->prepare("SELECT area,titulo,autor2,aprovado,aprovado_banner,cpf FROM trabalhos_cientificos WHERE id = ?");
	$ru->bindParam(1,$idtrab);
	$ru->execute();
	if($ru->rowCount())
	{
		//opa, alguma linha no banco de dados! Certeza que tem um trabalho haushuah
		//resgatar em forma de objeto
		$trab = $ru->fetch(PDO::FETCH_OBJ);
		if($trab->aprovado != 1)
		{
			//trabalho não foi aprovado, não continuar com o certificado
			echo utf8_decode('<h1>Infelizmente seu trabalho não foi aprovado. Mals ae :/</h1>');
			exit(0);
		}
/* 		//Banner não foi enviado. Mals ae
		if($trab->aprovado_banner != 1)
		{
			//banner não foi aprovado, não continuar com o certificado
			echo utf8_decode('<h1>Infelizmente seu pôster não foi enviado.</h1>');
			exit(0);
		} */
		//Resgatar do bd os co-autores
		$autores = json_decode($trab->autor2);
		$cpf = $trab->cpf;
		//Os outros dados do trabalho vamos acessá-los com o ponteiro $trab
	}else
	{
		//Não existe nenhum trabalho
		echo utf8_decode('<h1>Você não enviou nenhum trabalho para o XVI ENEPET. Mals ae :/</h1>');
		exit(0);
	
	}
	
	$rt = $con->prepare("SELECT nome,cpf,pet,session_id,pago FROM inscricoes WHERE (cpf = ?) limit 1");
	$rt->bindParam(1,$cpf);
	//verificar se o usuário existe na lista dos alunos
	if($rt->execute())
	{
		if($rt->rowCount() == 1)
		{
			$arq = $rt->fetch(PDO::FETCH_OBJ);
			$pago = $arq->pago;
			$cpf = $arq->cpf;
			$nome = $arq->nome;
			$pet = $arq->pet;
			
			//DESCONSIDEREI O PAGAMENTO, TODO MUNDO VAI RECEBER CERTIFICADOOOOOOO DE APRESENTAÇÃO!!! URRUL, SOU UM PAI!
			/* 
			if($pago != 2)
			{
				// inscrição não confirmada
				echo '<h1>Inscrição não confirmada. Mandar mensagem para a ouvidoria do EVENTO.</h1>';
				exit(0);
			} */
			
			
			session_start();
			$SESSAO_CPF = isset($_SESSION['enepet2017_cpf'])?$_SESSION['enepet2017_cpf']:'';
			// verificar se o usuário está logado.
			if($arq->session_id != session_id() || $cpf != $SESSAO_CPF)
			{
				#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
				 
				echo '<h1>Deslogado. Você deve logar-se pela página de acompanhamento. <a href="http://enepet2017.com/?p=acompanhamento">Acompanhar</a></h1>';
				 session_destroy();
				 exit(0);
			}
			
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
		//Tratar array com os nomes dos autores
		$cont = 0;
		$coautores = '';
		if(!empty($autores)){
			foreach($autores as $file)
			{
				if(!empty($file)){
					$coautores .= mb_strtoupper($file,'utf8') . '; ';
					$cont++;
				}
			}
		}
		//Texto do certificado:
		/*
		O comitê científico do XVI ENEPET 2017 confere a
		##Nomes dos autores##
		o presente certificado pela apresentação do trabalho #NOME DO TRABALHO#, em forma de pôster, no XVI ENCONTRO NORDESTINO DOS PETS.
		*/
		if($cont < 5)
		{
			//Se tiver até quatro co-autores, o certificado será respeitável, com letras grandes
			$tam1 = '30'; $tam2 = '35'; $tam3 = '20';
		
		}elseif ($cont <= 12)
		{
			//Se tiver acima de 4, pqp, tomar no tu. Vou diminuir a letra, carai. haushuah
			$tam1 = '25'; $tam2 = '29'; $tam3 = '18';
		}else
		{
			//Se tiver acima de 4, pqp, tomar no tu. Vou diminuir a letra, carai. haushuah
			$tam1 = '22'; $tam2 = '27'; $tam3 = '17';
		}
$texto_cert = '
	<p style="font-size: '.$tam1.'px;text-align:center;">O comitê científico do XVI ENEPET PIAUÍ 2017 confere a</p>
	
	<p style="font-size: '.$tam2.'px;text-align:center;">'.$nome.'; '.$coautores.'</p>
	<p style="font-size: '.$tam1.'px;text-align: justify;">do <b> '.$pet.'</b>, o presente certificado pela apresentação do trabalho '.($trab->titulo).', em forma de pôster, no XVI ENCONTRO NORDESTINO DOS PETS.</p>
	<p style="font-size: '.$tam3.'px;text-align:center;"></p>
	<p style="font-size: '.$tam3.'px;text-align:center;">Teresina, '. date("d") .' de '.mes(date("m")).' de '. date("Y") .'.</p>';
		
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
		<br><br>
		<div class="art-content-layout">
			<div class="art-content-layout-row">
				<div class="art-layout-cell layout-item-0" style="width: 100%" >

				'.$texto_cert.'	
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>
';

		require_once("dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();
		$dompdf->load_html(utf8_decode($html));
		$dompdf->set_paper('A4','landscape');
		$dompdf->render();

		$dompdf->stream("cert_apresent_enepet2017_".$cpf.".pdf");
		exit(0);
		

	}

}

?>
