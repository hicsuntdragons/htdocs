<?php


include_once 'inc/conexao.php';
include_once 'inc/config.php';
$recibo = isset($_GET['cpf'])?$_GET['cpf']:'';

//limpar máscara
$recibo = preg_replace('/[^0-9]/', '', $recibo);

if(!empty($recibo))
{
//gerar declaracao de aceite em pdf


		
		
		$rt = $con->prepare("SELECT nome,cpf,pet,session_id,email,pago,alojamento,lote FROM inscricoes WHERE (cpf = ?) limit 1");
		$rt->bindParam(1,$recibo);
		 //verificar se o usuário existe na lista dos alunos
		 if($rt->execute()){
			if($rt->rowCount() == 1){
			
				$arq = $rt->fetch(PDO::FETCH_OBJ);
				$email = str_replace(' ', '', $arq->email);
				$pago = $arq->pago;
				$cpf = $arq->cpf;
				$nome = $arq->nome;
				$pet = $arq->pet;
				$lote = $arq->lote;
				$alojamento = $arq->alojamento;
				
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
				$ru = $con->prepare("SELECT * FROM pets_nordeste WHERE nome_pet LIKE ?");
				$ru->bindParam(1,$pet);
				$ru->execute();
				$arq2 = $ru->fetch(PDO::FETCH_OBJ);
				$tutor = $arq2->tutor;
				$cpf_tutor = $arq2->cpf;
				if(empty($tutor) || empty($cpf_tutor))
				{
					//cadastrar tutor
					
					$tutor = isset($_GET['tutor'])?$_GET['tutor']:'';
					$cpf_tutor = isset($_GET['cpf_tutor'])?$_GET['cpf_tutor']:'';
					$cpf_tutor = preg_replace('/[^0-9]/', '', $cpf_tutor);
					
					if(empty($tutor))
					{
						echo '<h1>Nome do Tutor vazio. Tente novamente.</h1>';
						exit(0);
					}
					if(!validaCPF($cpf_tutor))
					{
						echo '<h1>Cpf do Tutor digitado incorretamente. Tente novamente.</h1>';
						exit(0);
					}
					//até aqui tudo ok
					//Vamos guardar os dados
					
					$tutor = mb_strtoupper($tutor, 'utf-8');
					$stmt = $con->prepare("UPDATE `pets_nordeste` SET tutor=?,cpf=? WHERE nome_pet LIKE ?");
					$stmt->bindParam(1,$tutor);
					$stmt->bindParam(2,$cpf_tutor);
					$stmt->bindParam(3,$pet);
					if($stmt->execute())
					{
					
						//echo '<h1>Tutor cadastrado. Nome: '.$tutor.', CPF: '.$cpf_tutor.', '.$pet.'.</h1>';
					
					}else{
					
						echo '<h1>Erro ao atualizar o banco de dados. Mandar mensagem para a ouvidoria.</h1>';
						exit(0);
					}
				}
				
				if($pago != 2)
				{
					//inscrição não confirmada
						echo '<h1>Inscrição não confirmada. Mandar mensagem para a ouvidoria do EVENTO.</h1>';
						exit(0);
				}
				
				
			}elseif($rt->rowCount() == 0){
				
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

				
				
				
					//AQUI COMEÇA O CERTIFICADO
$html = '
<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=Generator content="Microsoft Word 14 (filtered)">
<title>RECIBO</title>
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:SimSun;
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
@font-face
	{font-family:"\@SimSun";
	panose-1:2 1 6 0 3 1 1 1 1 1;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman","serif";}
h3
	{mso-style-link:"Título 3 Char";
	margin:0cm;
	margin-bottom:.0001pt;
	page-break-after:avoid;
	font-size:8.0pt;
	font-family:"Arial","sans-serif";}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{mso-style-link:"Cabeçalho Char";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{mso-style-link:"Rodapé Char";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman","serif";}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-link:"Texto de balão Char";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:"Tahoma","sans-serif";}
span.Ttulo3Char
	{mso-style-name:"Título 3 Char";
	mso-style-link:"Título 3";
	font-family:"Arial","sans-serif";
	font-weight:bold;}
span.CabealhoChar
	{mso-style-name:"Cabeçalho Char";
	mso-style-link:Cabeçalho;}
span.RodapChar
	{mso-style-name:"Rodapé Char";
	mso-style-link:Rodapé;}
span.TextodebaloChar
	{mso-style-name:"Texto de balão Char";
	mso-style-link:"Texto de balão";
	font-family:"Tahoma","sans-serif";}
.MsoChpDefault
	{font-size:10.0pt;
	font-family:"Calibri","sans-serif";}
 /* Page Definitions */
 @page WordSection1
	{size:595.3pt 841.9pt;
	margin:42.55pt 3.0cm 70.85pt 2.0cm;}
div.WordSection1
	{page:WordSection1;}
-->
</style>

</head>

<body lang=PT-BR>

<div class=WordSection1>

<p class=MsoHeader><span style=\'align:center;\'><img width="100%"
src="recibo_arquivos/image001.png"></p>

<p class=MsoNormal>&nbsp;</p>

<p class=MsoNormal>&nbsp;</p>

<p class=MsoNormal align=center style=\'text-align:center\'><b><span
style=\'font-size:16.0pt;font-family:"Arial","sans-serif"\'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style=\'text-align:center\'><b><span
style=\'font-size:16.0pt;font-family:"Arial","sans-serif"\'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style=\'text-align:center\'><b><span
style=\'font-size:16.0pt;font-family:"Arial","sans-serif"\'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style=\'text-align:center\'><b><span
style=\'font-size:16.0pt;font-family:"Arial","sans-serif"\'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style=\'text-align:center\'>&nbsp;</p>

<br clear=ALL>

<p class=MsoNormal align=center style=\'text-align:center\'><b><span
style=\'font-size:16.0pt\'>RECIBO</span></b></p>

<p class=MsoNormal style=\'line-height:150%\'>&nbsp;</p>

<p class=MsoNormal style=\'line-height:150%\'>&nbsp;</p>

<p class=MsoNormal align=right style=\'text-align:right;line-height:150%\'>&nbsp;</p>

<p class=MsoNormal style=\'text-align:justify;line-height:150%\'>&nbsp;</p>


<p class=MsoNormal style=\'text-align:justify;text-indent:35.4pt;line-height:
150%;background:white\'>Recebemos de <span style=\'color:#222222\'>'.$nome.', portador do CPF: '.$cpf.'</span>, do '.$pet.', a importância supra de <b>'.precoRecibo($alojamento, $lote).',</b>
referente à inscrição no XVI ENCONTRO NORDESTINO DOS GRUPOS PET (ENEPET).</p>

<p class=MsoNormal style=\'text-align:justify;line-height:150%;background:white\'>&nbsp;</p>

<p class=MsoNormal style=\'text-align:justify;line-height:150%;background:white\'>OBSERVAÇÕES:
</p>

<p class=MsoNormal style=\'text-align:justify;line-height:150%;background:white\'>Tutor:
'.$tutor.', CPF. Nº '.$cpf_tutor.', do '.$pet.'.</p>

<p class=MsoNormal style=\'text-align:justify;text-indent:35.4pt;line-height:
150%;background:white\'>&nbsp;</p>

<p class=MsoNormal align=right style=\'text-align:right;line-height:150%\'>Teresina,
'. date("d") .' de '.mes(date("m")).' de '. date("Y") .'.</p>

<p class=MsoNormal style=\'text-align:justify;line-height:150%\'>&nbsp;</p>

<p class=MsoNormal style=\'text-align:justify;line-height:150%\'>&nbsp;</p>

<p class=MsoNormal align=center style=\'text-align:center\'>&nbsp;</p>

<p class=MsoNormal align=center style=\'text-align:center\'><img width=349
height=167 id="Imagem 1" src="recibo_arquivos/image002.jpg"></p>

</div>

</body>

</html>





	';
		
		require_once("dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();
		$dompdf->load_html(utf8_decode($html));
		$dompdf->set_paper('A4','portrait');
		$dompdf->render();

		$dompdf->stream("recibo_".$cpf.".pdf");
		exit(0);

		}

}

?>
