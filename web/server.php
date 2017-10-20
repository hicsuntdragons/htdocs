<?php

include_once 'inc/conexao.php';
include_once 'inc/config.php';
$carta = isset($_GET['carta'])?$_GET['carta']:'';
$declaracao = isset($_GET['decl'])?$_GET['decl']:'';

$cursos = $con->prepare("SELECT * FROM `vagas_minicursos`");
if($cursos->execute()){
$c = $cursos->fetchAll();}

if(!empty($declaracao))
{
//gerar declaracao de aceite em pdf

		$rt = $con->prepare("SELECT nome,cpf,pet,session_id,email,pago FROM inscricoes WHERE (cpf = ?) limit 1");
		$rt->bindParam(1,$declaracao);
		 //verificar se o usuário existe na lista dos alunos
		 if($rt->execute()){
			if($rt->rowCount() == 1){
			
				$arq = $rt->fetch(PDO::FETCH_OBJ);
				$email = str_replace(' ', '', $arq->email);
				$pago = $arq->pago;
				$cpf = $arq->cpf;
				$nome = $arq->nome;
				$pet = $arq->pet;
				
				if($pago != 2)
				{
					
					$rv = $con->prepare("SELECT nome,pet FROM tarefas WHERE email = ? group by email");
					$rv->bindParam(1,$email);
					$rv->execute();
					if($rv->rowCount() == 0)
					{
						echo '<h1>Inscrição não confirmada. Mandar mensagem para a ouvidoria do EVENTO.</h1>';
						exit(0);
					}
					$organizador = true;
					$arq6 = $rv->fetch(PDO::FETCH_OBJ);
					$pet = $arq6->pet;
				}
				
				if(!isset($organizador))
				{
					$ru = $con->prepare("SELECT count(*) FROM frequencia WHERE cpf = ?");
					$ru->bindParam(1,$cpf);
					$ru->execute();
					$arq2 = $ru->fetch(PDO::FETCH_NUM);
					$presenca = $arq2[0];
					if($presenca == 0)
					{
						echo '<h1>Nenhuma presença efetuada durante o ENEPET 2017. Mandar mensagem para a ouvidoria do EVENTO.</h1>';
						exit(0);
					}
				}
				
			}elseif($rt->rowCount() == 0){
				
				$rv = $con->prepare("SELECT nome,pet FROM tarefas WHERE email = ? group by email");
				$rv->bindParam(1,$declaracao);
				$rv->execute();
				if($rv->rowCount() == 0)
				{
					echo '<h1>Inscrição não confirmada no ENEPET 2017. Mandar mensagem para a ouvidoria do EVENTO.</h1>';
					exit(0);
				}
				$organizador = true;
				$arq6 = $rv->fetch(PDO::FETCH_OBJ);
				$pet = $arq6->pet;
				$nome = $arq6->nome;
				
			}
				// session_start();
				// $SESSAO_CPF = isset($_SESSION['enepet2017_cpf'])?$_SESSION['enepet2017_cpf']:'';
				// verificar se o usuário está logado.
				// if($arq->session_id != session_id() || $cpf != $SESSAO_CPF)
				// {
					// #abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
					 
					// echo 'deslogado';
					 // session_destroy();
					 // exit(0);
				// }
				$nome = mb_strtoupper($nome, 'utf-8');
					//AQUI COMEÇA O CERTIFICADO
					$html = '<html xmlns:v="urn:schemas-microsoft-com:vml"
	xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:w="urn:schemas-microsoft-com:office:word"
	xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
	xmlns="http://www.w3.org/TR/REC-html40">

	<head>
	<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
	<meta name=ProgId content=Word.Document>
	<meta name=Generator content="Microsoft Word 14">
	<meta name=Originator content="Microsoft Word 14">
	<link rel=File-List
	href="img_carta/filelist.xml">
	<link rel=Edit-Time-Data
	href="img_carta/editdata.mso">
	<!--[if !mso]>
	<style>
	v\:* {behavior:url(#default#VML);}
	o\:* {behavior:url(#default#VML);}
	w\:* {behavior:url(#default#VML);}
	.shape {behavior:url(#default#VML);}
	</style>
	<![endif]-->
	<link rel=themeData
	href="img_carta/themedata.thmx">
	<link rel=colorSchemeMapping
	href="img_carta/colorschememapping.xml">
	<!--[if gte mso 9]><xml>
	 <w:WordDocument>
	  <w:SpellingState>Clean</w:SpellingState>
	  <w:TrackMoves>false</w:TrackMoves>
	  <w:TrackFormatting/>
	  <w:HyphenationZone>21</w:HyphenationZone>
	  <w:ValidateAgainstSchemas/>
	  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
	  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
	  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
	  <w:DoNotPromoteQF/>
	  <w:LidThemeOther>PT-BR</w:LidThemeOther>
	  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
	  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
	  <w:Compatibility>
	   <w:BreakWrappedTables/>
	   <w:SplitPgBreakAndParaMark/>
	  </w:Compatibility>
	  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
	  <m:mathPr>
	   <m:mathFont m:val="Cambria Math"/>
	   <m:brkBin m:val="before"/>
	   <m:brkBinSub m:val="&#45;-"/>
	   <m:smallFrac m:val="off"/>
	   <m:dispDef/>
	   <m:lMargin m:val="0"/>
	   <m:rMargin m:val="0"/>
	   <m:defJc m:val="centerGroup"/>
	   <m:wrapIndent m:val="1440"/>
	   <m:intLim m:val="subSup"/>
	   <m:naryLim m:val="undOvr"/>
	  </m:mathPr></w:WordDocument>
	</xml><![endif]--><!--[if gte mso 9]><xml>
	 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
	  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
	  LatentStyleCount="267">
	  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
	  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
	  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
	  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
	  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
	  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
	  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
	  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
	  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Table Grid"/>
	  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
	  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 1"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
	  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
	  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
	  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
	  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 2"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 3"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 4"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 5"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 6"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
	  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
	  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
	  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
	  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
	  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
	  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
	  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
	 </w:LatentStyles>
	</xml><![endif]-->
	<style>
	<!--
	 /* Font Definitions */
	 @font-face
		{font-family:"Cambria Math";
		panose-1:2 4 5 3 5 4 6 3 2 4;
		mso-font-charset:1;
		mso-generic-font-family:roman;
		mso-font-format:other;
		mso-font-pitch:variable;
		mso-font-signature:0 0 0 0 0 0;}
	@font-face
		{font-family:Cambria;
		panose-1:2 4 5 3 5 4 6 3 2 4;
		mso-font-charset:0;
		mso-generic-font-family:roman;
		mso-font-pitch:variable;
		mso-font-signature:-536870145 1073743103 0 0 415 0;}
	@font-face
		{font-family:Calibri;
		panose-1:2 15 5 2 2 2 4 3 2 4;
		mso-font-alt:Calibri;
		mso-font-charset:0;
		mso-generic-font-family:swiss;
		mso-font-pitch:variable;
		mso-font-signature:-536859905 -1073732485 9 0 511 0;}
	@font-face
		{font-family:Tahoma;
		panose-1:2 11 6 4 3 5 4 4 2 4;
		mso-font-charset:0;
		mso-generic-font-family:swiss;
		mso-font-pitch:variable;
		mso-font-signature:-520081665 -1073717157 41 0 66047 0;}
	 /* Style Definitions */
	 p.MsoNormal, li.MsoNormal, div.MsoNormal
		{mso-style-unhide:no;
		mso-style-qformat:yes;
		mso-style-parent:"";
		margin-top:0cm;
		margin-right:0cm;
		margin-bottom:10.0pt;
		margin-left:0cm;
		line-height:115%;
		mso-pagination:widow-orphan;
		font-size:11.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	h1
		{mso-style-priority:9;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		mso-style-link:"Título 1 Char";
		margin-top:24.0pt;
		margin-right:0cm;
		margin-bottom:0cm;
		margin-left:0cm;
		margin-bottom:.0001pt;
		line-height:115%;
		mso-pagination:widow-orphan;
		page-break-after:avoid;
		mso-outline-level:1;
		font-size:14.0pt;
		font-family:"Cambria","serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;
		mso-bidi-font-family:"Times New Roman";
		color:#365F91;}
	p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
		{mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-link:"Texto de balão Char";
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:widow-orphan;
		font-size:8.0pt;
		font-family:"Tahoma","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	p.MsoNoSpacing, li.MsoNoSpacing, div.MsoNoSpacing
		{mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:widow-orphan;
		font-size:11.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	span.Ttulo1Char
		{mso-style-name:"Título 1 Char";
		mso-style-priority:9;
		mso-style-unhide:no;
		mso-style-locked:yes;
		mso-style-link:"Título 1";
		font-family:"Cambria","serif";
		mso-ascii-font-family:Cambria;
		mso-hansi-font-family:Cambria;
		color:#365F91;
		font-weight:bold;}
	span.TextodebaloChar
		{mso-style-name:"Texto de balão Char";
		mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-unhide:no;
		mso-style-locked:yes;
		mso-style-link:"Texto de balão";
		font-family:"Tahoma","sans-serif";
		mso-ascii-font-family:Tahoma;
		mso-hansi-font-family:Tahoma;
		mso-bidi-font-family:Tahoma;}
	p.Default, li.Default, div.Default
		{mso-style-name:Default;
		mso-style-unhide:no;
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:widow-orphan;
		text-autospace:none;
		font-size:12.0pt;
		font-family:"Courier New";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;
		color:black;}
	p.msochpdefault, li.msochpdefault, div.msochpdefault
		{mso-style-name:msochpdefault;
		mso-style-unhide:no;
		mso-margin-top-alt:auto;
		margin-right:0cm;
		mso-margin-bottom-alt:auto;
		margin-left:0cm;
		mso-pagination:widow-orphan;
		font-size:12.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	p.msopapdefault, li.msopapdefault, div.msopapdefault
		{mso-style-name:msopapdefault;
		mso-style-unhide:no;
		mso-margin-top-alt:auto;
		margin-right:0cm;
		margin-bottom:10.0pt;
		margin-left:0cm;
		line-height:115%;
		mso-pagination:widow-orphan;
		font-size:12.0pt;
		font-family:"Times New Roman","serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	span.SpellE
		{mso-style-name:"";
		mso-spl-e:yes;}
	.MsoChpDefault
		{mso-style-type:export-only;
		mso-default-props:yes;
		font-size:10.0pt;
		mso-ansi-font-size:10.0pt;
		mso-bidi-font-size:10.0pt;
		font-family:"Calibri","sans-serif";
		mso-ascii-font-family:Calibri;
		mso-hansi-font-family:Calibri;
		mso-bidi-font-family:Calibri;}
	.MsoPapDefault
		{mso-style-type:export-only;
		margin-bottom:10.0pt;
		line-height:115%;}
	@page WordSection1
		{size:595.3pt 841.9pt;
		margin:70.85pt 3.0cm 70.85pt 3.0cm;
		mso-header-margin:35.4pt;
		mso-footer-margin:35.4pt;
		mso-paper-source:0;}
	div.WordSection1
		{page:WordSection1;}
	-->
	</style>
	<!--[if gte mso 10]>
	<style>
	 /* Style Definitions */
	 table.MsoNormalTable
		{mso-style-name:"Tabela normal";
		mso-tstyle-rowband-size:0;
		mso-tstyle-colband-size:0;
		mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-parent:"";
		mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
		mso-para-margin-top:0cm;
		mso-para-margin-right:0cm;
		mso-para-margin-bottom:10.0pt;
		mso-para-margin-left:0cm;
		line-height:115%;
		mso-pagination:widow-orphan;
		font-size:10.0pt;
		font-family:"Calibri","sans-serif";}
	</style>
	<![endif]--><!--[if gte mso 9]><xml>
	 <o:shapedefaults v:ext="edit" spidmax="1026"/>
	</xml><![endif]--><!--[if gte mso 9]><xml>
	 <o:shapelayout v:ext="edit">
	  <o:idmap v:ext="edit" data="1"/>
	 </o:shapelayout></xml><![endif]-->
	</head>

	<body lang=PT-BR style=\'tab-interval:35.4pt\'>

	<div class=WordSection1>

	<p class=MsoNormal align=center style=\'text-align:center\'><span
	style=\'mso-no-proof:yes\'><img width=566 height=311 id="_x0000_i1026"
	src="img_carta/image001.jpg"
	alt="Descrição: C:\Users\Francisco Marcolino\Documents\Engenharia Elétrica\Artigos\PET\site enepet 2017\UNIVERSIDADE FEDERAL DO PIAUÍ_arquivos\image001.jpg"></span></p>

	<p class=Default>&nbsp;</p>

	<p class=Default align=center style=\'text-align:center\'><b><span
	style=\'font-size:14.0pt\'>DECLARAÇÃO</span></b></p>

	<p class=Default align=center style=\'text-align:center\'><b><span
	style=\'font-size:14.0pt\'>&nbsp;</span></b></p>

	<p class=Default style=\'text-align:justify\'><span style=\'font-size:14.0pt\'>&nbsp;</span></p>

	<p class=Default style=\'text-align:justify\'><span style=\'font-size:11.5pt\'>Declaro para os devidos fins que o (a) aluno (a) <b> '.$nome.',</b> do <b> '.$pet.'</b> '.(isset($organizador)? 'fez parte da organização do':'marcou presença no' ).' "XVI ENEPET PIAUÍ 2017",
	'.(isset($organizador)? 'durante o':'realizado no' ).' período de 20 a 23 de ABRIL de 2017, na Universidade Federal do
	Piauí, na cidade de Teresina (PI). <o:p></o:p></span></p>
	
	<p class=Default style=\'text-align:justify\'><span style=\'font-size:11.5pt\'><o:p>Teresina - PI, '. date("d") .'/'.date("m").'/'. date("Y") .'.</o:p></span></p>


	<p class=Default style=\'text-align:justify\'><span style=\'font-size:11.5pt\'>Atenciosamente,<o:p></o:p></span></p>

	<p class=Default style=\'text-align:justify\'><o:p>&nbsp;</o:p></p>

	<p class=MsoNormal align=center style=\'text-align:center\'><u><span
	style=\'mso-no-proof:yes\'><!--[if gte vml 1]><v:shapetype id="_x0000_t75"
	 coordsize="21600,21600" o:spt="75" o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe"
	 filled="f" stroked="f">
	 <v:stroke joinstyle="miter"/>
	 <v:formulas>
	  <v:f eqn="if lineDrawn pixelLineWidth 0"/>
	  <v:f eqn="sum @0 1 0"/>
	  <v:f eqn="sum 0 0 @1"/>
	  <v:f eqn="prod @2 1 2"/>
	  <v:f eqn="prod @3 21600 pixelWidth"/>
	  <v:f eqn="prod @3 21600 pixelHeight"/>
	  <v:f eqn="sum @0 0 1"/>
	  <v:f eqn="prod @6 1 2"/>
	  <v:f eqn="prod @7 21600 pixelWidth"/>
	  <v:f eqn="sum @8 21600 0"/>
	  <v:f eqn="prod @7 21600 pixelHeight"/>
	  <v:f eqn="sum @10 21600 0"/>
	 </v:formulas>
	 <v:path o:extrusionok="f" gradientshapeok="t" o:connecttype="rect"/>
	 <o:lock v:ext="edit" aspectratio="t"/>
	</v:shapetype><v:shape id="Imagem_x0020_2" o:spid="_x0000_i1025" type="#_x0000_t75"
	 alt="Descrição: C:\Users\Francisco Marcolino\Documents\backup pendrive\www\images\ota.png"
	 style=\'width:306pt;height:60.75pt;visibility:visible;mso-wrap-style:square\'>
	 <v:imagedata src="img_carta/image001.png"
	  o:title="ota"/>
	</v:shape><![endif]--><![if !vml]><img width=408 height=81
	src="img_carta/image002.jpg"
	alt="ota.png"><![endif]></span></u></p>

	<p class=MsoNormal align=center style=\'text-align:center\'>Presidente Comissão
	Científica</p>

	<p class=MsoNormal align=center style=\'text-align:center\'>ENEPET PIAUÍ 2017</p>

	<p class=MsoNormal align=center style=\'text-align:center\'><o:p>&nbsp;</o:p></p>

	</div>

	</body>

	</html>
	';
		
		require_once("dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();
		$dompdf->load_html(utf8_decode($html));
		$dompdf->set_paper('A4','portrait');
		$dompdf->render();

		$dompdf->stream("declaracao_".$declaracao.".pdf");
		exit(0);

		}

}

if(!empty($carta))
{
//gerar carta de aceite em pdf

		$rt = $con->prepare("SELECT titulo,cpf,autor2 FROM trabalhos_cientificos WHERE id=? and (aprovado=1 or aprovado=2 or aprovado=3)  limit 1");
		$rt->bindParam(1,$carta);
		 //verificar se o usuário existe na lista dos alunos
		 if($rt->execute()){
			if($rt->rowCount() == 0){
				
				echo '<h1>Trabalho não existe ou não foi aprovado</h1>';
				exit(0);
				
			}else{
			
				$arq = $rt->fetch(PDO::FETCH_OBJ);
				$cpf = $arq->cpf;
				
				
				$titulo = $arq->titulo;
				$autores = json_decode($arq->autor2);
				
				$ru = $con->prepare("SELECT nome,session_id FROM inscricoes WHERE cpf=? limit 1");
				$ru->bindParam(1,$cpf);
				$ru->execute();
				$arq2 = $ru->fetch(PDO::FETCH_OBJ);
				$autor = $arq2->nome;
				
				session_start();
				$SESSAO_CPF = isset($_SESSION['enepet2017_cpf'])?$_SESSION['enepet2017_cpf']:'';
				// verificar se o usuário está logado.
				if($arq2->session_id != session_id() || $cpf != $SESSAO_CPF){
					#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
					 
					echo 'deslogado';
					 session_destroy();
					 exit(0);
				}
				
				$cont = 0;
				if(isset($autores)){
					foreach($autores as $file)
					{
						if(!empty($file)){
							$coautores .= $file . '; ';
							$cont++;
						}
					}
				}
					//AQUI COMEÇA O CERTIFICADO
					$html = '<html xmlns:v="urn:schemas-microsoft-com:vml"
	xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:w="urn:schemas-microsoft-com:office:word"
	xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
	xmlns="http://www.w3.org/TR/REC-html40">

	<head>
	<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
	<meta name=ProgId content=Word.Document>
	<meta name=Generator content="Microsoft Word 14">
	<meta name=Originator content="Microsoft Word 14">
	<link rel=File-List
	href="img_carta/filelist.xml">
	<link rel=Edit-Time-Data
	href="img_carta/editdata.mso">
	<!--[if !mso]>
	<style>
	v\:* {behavior:url(#default#VML);}
	o\:* {behavior:url(#default#VML);}
	w\:* {behavior:url(#default#VML);}
	.shape {behavior:url(#default#VML);}
	</style>
	<![endif]-->
	<link rel=themeData
	href="img_carta/themedata.thmx">
	<link rel=colorSchemeMapping
	href="img_carta/colorschememapping.xml">
	<!--[if gte mso 9]><xml>
	 <w:WordDocument>
	  <w:SpellingState>Clean</w:SpellingState>
	  <w:TrackMoves>false</w:TrackMoves>
	  <w:TrackFormatting/>
	  <w:HyphenationZone>21</w:HyphenationZone>
	  <w:ValidateAgainstSchemas/>
	  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
	  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
	  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
	  <w:DoNotPromoteQF/>
	  <w:LidThemeOther>PT-BR</w:LidThemeOther>
	  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
	  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
	  <w:Compatibility>
	   <w:BreakWrappedTables/>
	   <w:SplitPgBreakAndParaMark/>
	  </w:Compatibility>
	  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
	  <m:mathPr>
	   <m:mathFont m:val="Cambria Math"/>
	   <m:brkBin m:val="before"/>
	   <m:brkBinSub m:val="&#45;-"/>
	   <m:smallFrac m:val="off"/>
	   <m:dispDef/>
	   <m:lMargin m:val="0"/>
	   <m:rMargin m:val="0"/>
	   <m:defJc m:val="centerGroup"/>
	   <m:wrapIndent m:val="1440"/>
	   <m:intLim m:val="subSup"/>
	   <m:naryLim m:val="undOvr"/>
	  </m:mathPr></w:WordDocument>
	</xml><![endif]--><!--[if gte mso 9]><xml>
	 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
	  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
	  LatentStyleCount="267">
	  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
	  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
	  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
	  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
	  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
	  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
	  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
	  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
	  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Table Grid"/>
	  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
	  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 1"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
	  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
	  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
	  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
	  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 2"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 3"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 4"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 5"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 6"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
	  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
	  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
	  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
	  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
	  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
	  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
	  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
	 </w:LatentStyles>
	</xml><![endif]-->
	<style>
	<!--
	 /* Font Definitions */
	 @font-face
		{font-family:"Cambria Math";
		panose-1:2 4 5 3 5 4 6 3 2 4;
		mso-font-charset:1;
		mso-generic-font-family:roman;
		mso-font-format:other;
		mso-font-pitch:variable;
		mso-font-signature:0 0 0 0 0 0;}
	@font-face
		{font-family:Cambria;
		panose-1:2 4 5 3 5 4 6 3 2 4;
		mso-font-charset:0;
		mso-generic-font-family:roman;
		mso-font-pitch:variable;
		mso-font-signature:-536870145 1073743103 0 0 415 0;}
	@font-face
		{font-family:Calibri;
		panose-1:2 15 5 2 2 2 4 3 2 4;
		mso-font-alt:Calibri;
		mso-font-charset:0;
		mso-generic-font-family:swiss;
		mso-font-pitch:variable;
		mso-font-signature:-536859905 -1073732485 9 0 511 0;}
	@font-face
		{font-family:Tahoma;
		panose-1:2 11 6 4 3 5 4 4 2 4;
		mso-font-charset:0;
		mso-generic-font-family:swiss;
		mso-font-pitch:variable;
		mso-font-signature:-520081665 -1073717157 41 0 66047 0;}
	 /* Style Definitions */
	 p.MsoNormal, li.MsoNormal, div.MsoNormal
		{mso-style-unhide:no;
		mso-style-qformat:yes;
		mso-style-parent:"";
		margin-top:0cm;
		margin-right:0cm;
		margin-bottom:10.0pt;
		margin-left:0cm;
		line-height:115%;
		mso-pagination:widow-orphan;
		font-size:11.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	h1
		{mso-style-priority:9;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		mso-style-link:"Título 1 Char";
		margin-top:24.0pt;
		margin-right:0cm;
		margin-bottom:0cm;
		margin-left:0cm;
		margin-bottom:.0001pt;
		line-height:115%;
		mso-pagination:widow-orphan;
		page-break-after:avoid;
		mso-outline-level:1;
		font-size:14.0pt;
		font-family:"Cambria","serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;
		mso-bidi-font-family:"Times New Roman";
		color:#365F91;}
	p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
		{mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-link:"Texto de balão Char";
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:widow-orphan;
		font-size:8.0pt;
		font-family:"Tahoma","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	p.MsoNoSpacing, li.MsoNoSpacing, div.MsoNoSpacing
		{mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:widow-orphan;
		font-size:11.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	span.Ttulo1Char
		{mso-style-name:"Título 1 Char";
		mso-style-priority:9;
		mso-style-unhide:no;
		mso-style-locked:yes;
		mso-style-link:"Título 1";
		font-family:"Cambria","serif";
		mso-ascii-font-family:Cambria;
		mso-hansi-font-family:Cambria;
		color:#365F91;
		font-weight:bold;}
	span.TextodebaloChar
		{mso-style-name:"Texto de balão Char";
		mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-unhide:no;
		mso-style-locked:yes;
		mso-style-link:"Texto de balão";
		font-family:"Tahoma","sans-serif";
		mso-ascii-font-family:Tahoma;
		mso-hansi-font-family:Tahoma;
		mso-bidi-font-family:Tahoma;}
	p.Default, li.Default, div.Default
		{mso-style-name:Default;
		mso-style-unhide:no;
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:widow-orphan;
		text-autospace:none;
		font-size:12.0pt;
		font-family:"Courier New";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;
		color:black;}
	p.msochpdefault, li.msochpdefault, div.msochpdefault
		{mso-style-name:msochpdefault;
		mso-style-unhide:no;
		mso-margin-top-alt:auto;
		margin-right:0cm;
		mso-margin-bottom-alt:auto;
		margin-left:0cm;
		mso-pagination:widow-orphan;
		font-size:12.0pt;
		font-family:"Calibri","sans-serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	p.msopapdefault, li.msopapdefault, div.msopapdefault
		{mso-style-name:msopapdefault;
		mso-style-unhide:no;
		mso-margin-top-alt:auto;
		margin-right:0cm;
		margin-bottom:10.0pt;
		margin-left:0cm;
		line-height:115%;
		mso-pagination:widow-orphan;
		font-size:12.0pt;
		font-family:"Times New Roman","serif";
		mso-fareast-font-family:"Times New Roman";
		mso-fareast-theme-font:minor-fareast;}
	span.SpellE
		{mso-style-name:"";
		mso-spl-e:yes;}
	.MsoChpDefault
		{mso-style-type:export-only;
		mso-default-props:yes;
		font-size:10.0pt;
		mso-ansi-font-size:10.0pt;
		mso-bidi-font-size:10.0pt;
		font-family:"Calibri","sans-serif";
		mso-ascii-font-family:Calibri;
		mso-hansi-font-family:Calibri;
		mso-bidi-font-family:Calibri;}
	.MsoPapDefault
		{mso-style-type:export-only;
		margin-bottom:10.0pt;
		line-height:115%;}
	@page WordSection1
		{size:595.3pt 841.9pt;
		margin:70.85pt 3.0cm 70.85pt 3.0cm;
		mso-header-margin:35.4pt;
		mso-footer-margin:35.4pt;
		mso-paper-source:0;}
	div.WordSection1
		{page:WordSection1;}
	-->
	</style>
	<!--[if gte mso 10]>
	<style>
	 /* Style Definitions */
	 table.MsoNormalTable
		{mso-style-name:"Tabela normal";
		mso-tstyle-rowband-size:0;
		mso-tstyle-colband-size:0;
		mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-parent:"";
		mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
		mso-para-margin-top:0cm;
		mso-para-margin-right:0cm;
		mso-para-margin-bottom:10.0pt;
		mso-para-margin-left:0cm;
		line-height:115%;
		mso-pagination:widow-orphan;
		font-size:10.0pt;
		font-family:"Calibri","sans-serif";}
	</style>
	<![endif]--><!--[if gte mso 9]><xml>
	 <o:shapedefaults v:ext="edit" spidmax="1026"/>
	</xml><![endif]--><!--[if gte mso 9]><xml>
	 <o:shapelayout v:ext="edit">
	  <o:idmap v:ext="edit" data="1"/>
	 </o:shapelayout></xml><![endif]-->
	</head>

	<body lang=PT-BR style=\'tab-interval:35.4pt\'>

	<div class=WordSection1>

	<p class=MsoNormal align=center style=\'text-align:center\'><span
	style=\'mso-no-proof:yes\'><img width=566 height=311 id="_x0000_i1026"
	src="img_carta/image001.jpg"
	alt="Descrição: C:\Users\Francisco Marcolino\Documents\Engenharia Elétrica\Artigos\PET\site enepet 2017\UNIVERSIDADE FEDERAL DO PIAUÍ_arquivos\image001.jpg"></span></p>

	<p class=Default>&nbsp;</p>

	<p class=Default align=center style=\'text-align:center\'><b><span
	style=\'font-size:14.0pt\'>CARTA DE ACEITE</span></b></p>

	<p class=Default align=center style=\'text-align:center\'><b><span
	style=\'font-size:14.0pt\'>&nbsp;</span></b></p>

	<p class=Default style=\'text-align:justify\'><span style=\'font-size:14.0pt\'>&nbsp;</span></p>

	<p class=Default style=\'text-align:justify\'><span style=\'font-size:11.5pt\'>O
	trabalho intitulado "<b>'.$titulo.'</b>", de autoria de <b>'.$autor.'</b>; '.($cont ? 'Co-autores('.$cont.'): '.$coautores:'').' foi aceito para apresentação no "XVI ENEPET PIAUÍ 2017", a ser
	realizado no período de 21 a 23 de ABRIL de 2017, na Universidade Federal do
	Piauí, na cidade de Teresina (PI). <o:p></o:p></span></p>
	
	<p class=Default style=\'text-align:justify\'><span style=\'font-size:11.5pt\'><o:p>Teresina - PI, '. date("d") .'/'.date("m").'/'. date("Y") .'.</o:p></span></p>


	<p class=Default style=\'text-align:justify\'><span style=\'font-size:11.5pt\'>Atenciosamente,<o:p></o:p></span></p>

	<p class=Default style=\'text-align:justify\'><o:p>&nbsp;</o:p></p>

	<p class=MsoNormal align=center style=\'text-align:center\'><u><span
	style=\'mso-no-proof:yes\'><!--[if gte vml 1]><v:shapetype id="_x0000_t75"
	 coordsize="21600,21600" o:spt="75" o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe"
	 filled="f" stroked="f">
	 <v:stroke joinstyle="miter"/>
	 <v:formulas>
	  <v:f eqn="if lineDrawn pixelLineWidth 0"/>
	  <v:f eqn="sum @0 1 0"/>
	  <v:f eqn="sum 0 0 @1"/>
	  <v:f eqn="prod @2 1 2"/>
	  <v:f eqn="prod @3 21600 pixelWidth"/>
	  <v:f eqn="prod @3 21600 pixelHeight"/>
	  <v:f eqn="sum @0 0 1"/>
	  <v:f eqn="prod @6 1 2"/>
	  <v:f eqn="prod @7 21600 pixelWidth"/>
	  <v:f eqn="sum @8 21600 0"/>
	  <v:f eqn="prod @7 21600 pixelHeight"/>
	  <v:f eqn="sum @10 21600 0"/>
	 </v:formulas>
	 <v:path o:extrusionok="f" gradientshapeok="t" o:connecttype="rect"/>
	 <o:lock v:ext="edit" aspectratio="t"/>
	</v:shapetype><v:shape id="Imagem_x0020_2" o:spid="_x0000_i1025" type="#_x0000_t75"
	 alt="Descrição: C:\Users\Francisco Marcolino\Documents\backup pendrive\www\images\ota.png"
	 style=\'width:306pt;height:60.75pt;visibility:visible;mso-wrap-style:square\'>
	 <v:imagedata src="img_carta/image001.png"
	  o:title="ota"/>
	</v:shape><![endif]--><![if !vml]><img width=408 height=81
	src="img_carta/image002.jpg"
	alt="ota.png"><![endif]></span></u></p>

	<p class=MsoNormal align=center style=\'text-align:center\'>Presidente Comissão
	Científica</p>

	<p class=MsoNormal align=center style=\'text-align:center\'>ENEPET PIAUÍ 2017</p>

	<p class=MsoNormal align=center style=\'text-align:center\'><o:p>&nbsp;</o:p></p>

	</div>

	</body>

	</html>
	';
		
		require_once("dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();
		$dompdf->load_html(utf8_decode($html));
		$dompdf->set_paper('A4','portrait');
		$dompdf->render();

		$dompdf->stream("carta_de_aceite_".$carta.".pdf");
		exit(0);

			}
		}

}


$acao = isset($_GET['p'])?$_GET['p']:'';

if($acao == 'add')
{
	/*	
		require_once "recaptchalib.php";
		// sua chave secreta
		$secret = "6LfKBxYUAAAAAJjHGRCk_W_F9MM1rDNAc7NGwc9P";
		// resposta vazia
		$response = null;
		// verifique a chave secreta
		$reCaptcha = new ReCaptcha($secret);
		if ($_POST["g-recaptcha-response"]) {
		$response = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
			);
		}
		if ($response == null || $response->success) {

		}
	*/
		
		// if(!empty($_POST['nome_pet'])){
			// $rt = $con->prepare("SELECT * FROM pets_nordeste WHERE nome_pet LIKE ?");
			// $rt->bindParam(1, $_POST['nome_pet']);
			//verificar se o usuário existe na lista dos alunos
			// if($rt->execute()){
				// if($rt->rowCount() == 1){
					// $pet = 1;
				// }else{
					// $pet = 0;
				// }
				
			// }
		// }else{
			// $pet = 0;
		
		// }
		
		
		if(!empty($_POST['nome']) &&
			!empty($_POST['sobrenome']) &&
			validaCPF($_POST['cpf']) && 
			!empty($_POST['senha']) &&
			!empty($_POST['senhaConf']) &&
			$_POST['senha'] == $_POST['senhaConf'] &&
			filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) &&
			!empty($_POST['telefone']) &&
			(!empty($_POST['instituicao'])) &&
			(!empty($_POST['minicurso'])) &&
			(!empty($_POST['trab'])) &&
			(strtotime(end($dataFimInscricao)) > (strtotime(date("Y-m-d"))))
		)
		{
				
				$nome = mb_strtoupper($_POST['nome'], 'utf-8');
				$sobrenome = mb_strtoupper($_POST['sobrenome'], 'utf-8');
				//SE TIVER ARQUIVO
				$ccpf = retirar_letras_e_pontos($_POST['cpf']);
				$senha = $_POST['senha'];
				$senha_hash = password_hash("$senha", PASSWORD_DEFAULT); //isso criptografa a senha.
				$email = strtolower($_POST['email']);
				$rs = $con->prepare("SELECT * FROM eceel_inscricoes WHERE cpf = ?");
				$rs->bindParam(1, $ccpf);
				//verificar se o usuário existe na lista dos alunos
				if($rs->execute()){
					if($rs->rowCount() == 0){
						//NÃO EXISTE INSCRIÇÃO, FAZER UM NOVO
						if(validaCPF(retirar_letras_e_pontos($_POST['cpf'])))
						{
									$festa = isset($_POST['checkFesta'])?'1':'0';
									$stmt = $con->prepare("INSERT INTO 
									`eceel_inscricoes`(`id`, `nome`, `sobrenome`, `cpf`, `senha`, `email`, `instituicao`, `telefone`, `minicurso`, `trabalho`, `trab_uploaded`, `trab_nome`, `pago`, `session_id`, `justpaid`)
									VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0,' ',0,' ',0)");
								
									$stmt->bindParam(1,$nome);
									$stmt->bindParam(2,$sobrenome);
									$stmt->bindParam(3,$ccpf);
									$stmt->bindParam(4,$senha_hash);
									$stmt->bindParam(5,$email);
									$stmt->bindParam(6,$_POST['instituicao']);
									$stmt->bindParam(7,$_POST['telefone']);
									$stmt->bindParam(8,$_POST['minicurso']);
									$stmt->bindParam(9,$_POST['trab']);
									
									if($stmt->execute())
									{
									
									if($_POST['minicurso'] != 'nada') {
									// adicionar a pessoa à lista de minicursos. só o basico:
									$min = $con->prepare("INSERT INTO 
									`minicurso_inscritos`(`id`, `nome`, `sobrenome`, `cpf`, `minicurso`, `presencas`, `certificado`)
									VALUES (NULL, ?, ?, ?, ?, 0, 0)");
								
									$min->bindParam(1,$nome);
									$min->bindParam(2,$sobrenome);
									$min->bindParam(3,$ccpf);
									$min->bindParam(4,$_POST['minicurso']);
										if($min->execute()){
											echo 
											'<div class="alert alert-success" role="alert">
											<h4 class="alert-heading">'.$nome.', você foi inscrito no minicurso '.nomeMinicurso($_POST['minicurso']).'.</h4>
											<h4> Vá à sala do PET Potência para efetuar o pagamento da taxa de '.$precoComAlojamento.' correspondente.</h4>
											</div>';
										}
										else{
											echo 
												'<div class="alert alert-success" role="alert">
												<h4 class="alert-heading">'.$nome.', sua inscrição no minicurso '.nomeMinicurso($_POST['minicurso']).' 
												foi comprometida. Procure um Petiano.</h4>
												</div>';
										}
									}
									
									if($_POST['trab'] == 1){
											echo 
										'<div class="alert alert-success" role="alert">
										  <h4 class="alert-heading">'.$nome.', você selecionou apresentar um trabalho no nosso evento! Que bom!
										  Você pode nos enviar o trabalho para avaliação através da página de acompanhamento de inscrição.</h4>
										  <h4> É necessário também ir à sala do PET Potência para efetuar o pagamento da taxa de '.$precoSemAlojamento.' correspondente.</h4>
										</div>';
									}
/* $name = $nome;
$email_address = $email;
$phone = $_POST['telefone'];
$nome_pet = $_POST['nome_pet'];
// Create the email and send the message
$to = $email; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = 'ENEPET PIAUÍ 2017 - INSCRIÇÕES: '. $name;
$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
//$name = utf8_decode($name);
$email_body = utf8_decode("<html><body><p>Parabéns! Sua inscrição no XVI ENEPET Teresina 2017 foi realizada com sucesso! <br><br>Aqui estão os detalhes da sua inscrição:<br><br>Nome: $name<br>Email: $email_address<br>Senha: ".$_POST['senha']."<br>Telefone: $phone<br>$nome_pet<br><br>A partir de agora você já pode enviar trabalhos científicos. Fique atento às datas. Todas as informações a respeito da submissão de trabalhos estão <a href=\"http://www.enepet2017.com/?p=submissao\">AQUI</a>: datas importantes, modelos de resumo e banner.<br><br>Submeter trabalhos, pagar a inscrição, verificar o status de seus trabalhos e de sua inscrição, escolher atividades científicas que quer participar. Tudo isso você faz na página de Acompanhamento de Inscrição! <a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para acompanhar a sua inscrição.</a><br><br><b>".($receberComprovante==false?'O pagamento da inscrição ainda não está disponível. Assim que for possível realizá-lo, a organização entrará em contato com maiores informações.':'Informações para depósito também está na página de acompanhamento.')."</b><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
 */ 
 /* //enviar e-mail de confirmação para o inscrito.

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
$mail->addAddress($email_address, $name);
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
if(!$mail->send()){

	echo '
	<div class="alert alert-warning" role="alert">
	  <strong>Opa!</strong> E-mail não enviado.
	</div>';
} */
									echo 
										'<div class="alert alert-success" role="alert">
										  <h4 class="alert-heading">'.$nome.', a sua inscrição foi feita com sucesso!<br>
										  caso tenha optado por minicursos ou apresentação de trabalhos, sua vaga só será validada após o pagamento
										  da taxa correspondente.</h4>
										 
										  <p class="mb-0"><b>Realize o login e cheque seus dados lá na aba <i>Acompanhar Inscrição</i></p>
											
											<div class="btn-group" role="group" aria-label="Basic example">
											
											  <button type="button" onclick="newForm()" class="btn btn-success">Realizar uma nova inscrição</button>
											  <a href="?p=acompanhamento" class="btn btn-success">Acompanhar Inscrição</a>
											
											</div>
										</div>';
										
										//if(!mail($to,$email_subject,$email_body,$headers))
										//	echo 'email nao enviado';
											
									}else{
									
										echo '
										<div class="alert alert-warning" role="alert">
										  <strong>Opa!</strong> Erro no banco de dados. Contate o ADM. <br />
										  ' . print_r($stmt->errorInfo()) . '
										</div>';
									}
						}else{ 	
						
							echo '
							<div class="alert alert-warning" role="alert">
							  <strong>Opa!</strong> Erro. Número CPF inválido. Tente novamente.
							</div>';
						
						echo '
									<div class="col-lg-12">
										<form name="formInscricao" id="formInscricao" novalidate>
											<div class="row">
											  <p>Participante, insira seus dados.</p>
												<div class="col-md-6">
													<div class="form-group">
														<label for="nome">Primeiro Nome</label>
														<input type="text" class="form-control" placeholder="Seu Primeiro Nome *" id="nome" name="nome" required data-validation-required-message="Por favor, digite seu nome." value="" />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="sobrenome">Sobrenome</label>
														<input type="text" class="form-control" placeholder="Sobrenome *" id="sobrenome" name="sobrenome" required data-validation-required-message="Por favor, digite seu Sobrenome." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="cpf">CPF</label>
														<input type="text" class="form-control" placeholder="CPF *" id="cpf" name="cpf" required data-validation-required-message="Por favor, digite um número de CPF." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="senha">Senha</label>
														<input type="password" class="form-control" placeholder="Digite uma senha *" id="senha" name="senha" required data-validation-required-message="Por favor, digite uma senha." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="senhaConf">Confirmar Senha</label>
														<input type="password" class="form-control" placeholder="Confirme Sua senha" id="senhaConf" name="senhaConf" required data-validation-required-message="Por favor, confirme sua senha." />
														<p class="help-block text-danger"></p>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="email">e-mail</label>
														<input type="email" class="form-control" placeholder="Seu E-mail *" id="email" name="email" required data-validation-required-message="Por favor, digite um endereço de e-mail." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="telefone">Número de celular</label>
														<input type="phone" class="form-control" placeholder="Seu número de telefone *" id="telefone" name="telefone" required data-validation-required-message="Por favor, digite um número de telefone." />
														<p class="help-block text-danger"></p>
													</div>
													
													<div class="form-group">
													<label for="nome_pet">Sua Universidade / Faculdade</label>
													<select id="instituicao" class="form-control" name="instituicao" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="UFPI">Universidade Federal do Piauí</option>
														<option value="FSA">Faculdade Santo Agostinho</option>
													</select><p class="help-block text-danger"></p>
													<p id="pet" class="text-danger"></p>
													</div>
													
													<div class="form-group">
													<label for="minicurso">Você deseja Participar de algum Minicurso?</label>
													<select id="minicurso" class="form-control" name="minicurso" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="CLP">Minicurso de CLP - '.$c[0][2].' vagas, '.$c[0][3].' reservadas</option>
														<option value="EnergiasRenovaveis">Minicurso de Energias Renováveis - '.$c[1][2].' vagas, '.$c[1][3].' reservadas</option>
														<option value="OficinaEletronica">Oficina de Eletrônica - '.$c[2][2].' vagas, '.$c[2][3].' reservadas</option>
														<option value="PCB">Minicurso Prototipagem - '.$c[3][2].' vagas, '.$c[3][3].' reservadas</option>
														<option value="nada">Não desejo participar de nenhum minicurso.</option>
													</select><p class="help-block text-danger"></p>
													</div>
													
													<div class="form-group">
													<label for="trab">Você deseja Apresentar um Trabalho Científico?</label>
													<select id="trab" class="form-control" name="trab" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="1">Sim, desejo apresentar.</option>
														<option value="2">Não, não irei apresentar trabalho científico</option>
													</select><p class="help-block text-danger"></p>
													</div>
												  
												</div>
												<div class="clearfix"></div>
												<div class="col-lg-12 text-center">
												  
													<div id="success"></div>
													<button type="submit" onclick="enviarForm()" name="submit" class="btn btn-xl">Realizar a inscrição! <span class="glyphicon glyphicon-chevron-right"></span></button>
												</div>
											</div>
										</form>
									</div>';
						}
						
					}else if($rs->rowCount() == 1){
						//USUÁRIO EXISTE E ESTÁ CADASTRADO
						//EXIBIR DADOS DO USUÁRIO
						$arquivos = $rs->fetch(PDO::FETCH_OBJ);
							echo '
								<h2>Cadastro</h2>
								<h3>Nome: ' . $arquivos->nome. '</h3>
								<h3>CPF: ' . $arquivos->cpf. '</h3>
								<h3>minicurso: ' . $arquivos->minicurso. '</h3>
								<p>Para alterar algum dado pessoal, ou caso tenha esquecido sua senha, envie-nos uma mensagem ou nos procure na sala do PET.</p>
								<p><a href="?p=acompanhamento" class="btn btn-success">Acompanhar Inscrição</a></p>
								<p>iv ECEEL</p>
							';
							
							
					}
				}
				
			
		}else
		{
		
				if((strtotime(date("Y-m-d"))) > strtotime(end($dataFimInscricao)))
				{
					//fora do prazo
							echo '
							<div class="alert alert-danger" role="alert">
							  <strong>Opa!</strong> As inscrições já encerraram.
							</div>';
							
							exit(0);
				
				}
		//alguns campos não foram preenchidos.
							echo '
							<div class="alert alert-warning" role="alert">
							  <strong>Opa!</strong> Algum campo deixou de ser preenchido. Favor, digite em todos os campos.
							</div>';
							if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
								echo '
								<div class="alert alert-warning" role="alert">
								  <strong>Opa!</strong> Email inválido.
								</div>';
							}
							if(!validaCPF(retirar_letras_e_pontos($_POST['cpf']))){
								echo '
								<div class="alert alert-warning" role="alert">
								  <strong>Opa!</strong> CPF inválido.
								</div>';
							}
							if($_POST['senha'] != $_POST['senhaConf'] ){
								echo '
								<div class="alert alert-warning" role="alert">
								  <strong>Opa!</strong> senha e confirmação não conferem.
								</div>';
							}
						echo '
									<div class="col-lg-12">
										<form name="formInscricao" id="formInscricao" novalidate>
											<div class="row">
													<p>Participante, insira seus dados.</p>
												<div class="col-md-6">
													<div class="form-group '.(empty($_POST['nome'])?'has-warning':'').'">
														<label for="nome">Primeiro Nome</label>
														<input type="text" class="form-control" placeholder="Seu Primeiro Nome *" id="nome" name="nome" required data-validation-required-message="Por favor, digite seu nome." value="" />
														<p class="help-block text-danger">'.(empty($_POST['nome'])?'Preencha com seu nome completo.':'').'</p>
													</div>
													<div class="form-group '.(empty($_POST['sobrenome'])?'has-warning':'').'">
														<label for="sobrenome">Sobrenome</label>
														<input type="text" class="form-control" placeholder="Sobrenome *" id="sobrenome" name="sobrenome" required data-validation-required-message="Por favor, digite seu Sobrenome." />
														<p class="help-block text-danger">'.(empty($_POST['sobrenome'])?'Preencha com seu sobrenome.':'').'</p>
													</div>
													<div class="form-group '.(empty($_POST['cpf'])?'has-warning':'').'">
														<label for="cpf">CPF</label>
														<input type="text" class="form-control" placeholder="CPF *" id="cpf" name="cpf" required data-validation-required-message="Por favor, digite um número de CPF." value="'.(isset($_POST['cpf'])?$_POST['cpf']:'').'" />
														<p class="help-block text-danger">'.(empty($_POST['cpf'])?'Preencha com seu número do CPF.':'').'</p>
													</div>
													<div class="form-group '.(empty($_POST['senha'])?'has-warning':'').'">
														<label for="senha">Senha</label>
														<input type="password" class="form-control" placeholder="Digite uma senha *" id="senha" name="senha" required data-validation-required-message="Por favor, digite uma senha." value="'.(isset($_POST['senha'])?$_POST['senha']:'').'" />
														<p class="help-block text-danger">'.(empty($_POST['senha'])?'Preencha com uma senha.':'').'</p>
													</div>
													<div class="form-group '.(empty($_POST['senhaConf'])?'has-warning':'').'">
														<label for="senhaConf">Confirmar Senha</label>
														<input type="password" class="form-control" placeholder="Confirme Sua senha" id="senhaConf" name="senhaConf" required data-validation-required-message="Por favor, confirme sua senha." />
														<p class="help-block text-danger">'.(empty($_POST['senhaConf'])?'Confirme sua senha.':'').'</p>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group '.(empty($_POST['email'])?'has-warning':'').'">
														<label for="email">e-mail</label>
														<input type="email" class="form-control" placeholder="Seu E-mail *" id="email" name="email" required data-validation-required-message="Por favor, digite um endereço de e-mail." value="'.(isset($_POST['email'])?$_POST['email']:'').'" />
														<p class="help-block text-danger">'.(empty($_POST['email'])?'Preencha com seu e-mail.':'').'</p>
													</div>
													<div class="form-group '.(empty($_POST['telefone'])?'has-warning':'').'">
														<label for="telefone">Número de celular</label>
														<input type="number" class="form-control" placeholder="Seu número de telefone *" id="telefone" name="telefone" required data-validation-required-message="Por favor, digite um número de telefone." value="'.(isset($_POST['telefone'])?$_POST['telefone']:'').'" />
														<p class="help-block text-danger">'.(empty($_POST['telefone'])?'Preencha com seu telefone.':'').'</p>
													</div>
													
													<div class="form-group '.(empty($_POST['instituicao'])?'has-warning':'').'">
													<label for="nome_pet">Sua Universidade / Faculdade</label>
													<select id="instituicao" class="form-control" name="instituicao" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="UFPI">Universidade Federal do Piauí</option>
														<option value="FSA">Faculdade Santo Agostinho</option>
													</select><p class="help-block text-danger">'.(empty($_POST['instituicao'])?'Selecione sua universidade / faculdade.':'').'</p>
													</div>
													
													<div class="form-group '.(empty($_POST['minicurso'])?'has-warning':'').'">
													<label for="minicurso">Você deseja Participar de algum Minicurso?</label>
													<select id="minicurso" class="form-control" name="minicurso" size="1">
													<option value="0">Escolha uma opção:</option>
														<option value="CLP">Minicurso de CLP - '.$c[0][2].' vagas, '.$c[0][3].' reservadas</option>
														<option value="EnergiasRenovaveis">Minicurso de Energias Renováveis - '.$c[1][2].' vagas, '.$c[1][3].' reservadas</option>
														<option value="OficinaEletronica">Oficina de Eletrônica - '.$c[2][2].' vagas, '.$c[2][3].' reservadas</option>
														<option value="PCB">Minicurso Prototipagem - '.$c[3][2].' vagas, '.$c[3][3].' reservadas</option>
														<option value="nada">Não desejo participar de nenhum minicurso.</option>
													</select><p class="help-block text-danger">'.(empty($_POST['minicurso'])?'Selecione uma opção válida.':'').'</p>
													</div>
													
													<div class="form-group '.(empty($_POST['trab'])?'has-warning':'').'">
													<label for="trab">Você deseja Apresentar um Trabalho Científico?</label>
													<select id="trab" class="form-control" name="trab" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="1">Sim, desejo apresentar.</option>
														<option value="2">Não, não irei apresentar trabalho científico</option>
													</select><p class="help-block text-danger">'.(empty($_POST['trab'])?'Selecione uma opção válida.':'').'</p>
													</div>
												</div>
												
												<div class="clearfix"></div>
												<div class="col-lg-12 text-center">
												  
													<div id="success"></div>
													<button type="submit" onclick="enviarForm()" class="btn btn-xl">Realizar a inscrição! <span class="glyphicon glyphicon-chevron-right"></span></button>
												</div>
											</div>
										</form>
									</div>';
		
		
		}
}
else if($acao == 'new') // falta barrar minicursos cheios.
{

				if((strtotime(date("Y-m-d"))) > strtotime(end($dataFimInscricao)))
				{
					//fora do prazo
							echo '
							<a href="?p=acompanhamento" class="btn btn-success">Acompanhar a minha inscrição</a>';
							
							exit(0);
				
				} 
				?>
						
									<div class="col-lg-12">
										<form name="formInscricao" id="formInscricao" novalidate>
											<div class="row">
													<p>Participante, insira seus dados.</p>
												<div class="col-md-6">
													<div class="form-group">
														<label for="nome">Primeiro Nome</label>
														<input type="text" class="form-control" placeholder="Seu Primeiro Nome *" id="nome" name="nome" required data-validation-required-message="Por favor, digite seu nome." value="" />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="sobrenome">Sobrenome</label>
														<input type="text" class="form-control" placeholder="Sobrenome *" id="sobrenome" name="sobrenome" required data-validation-required-message="Por favor, digite seu Sobrenome." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="cpf">CPF</label>
														<input type="text" class="form-control" placeholder="CPF *" id="cpf" name="cpf" required data-validation-required-message="Por favor, digite um número de CPF." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="senha">Senha</label>
														<input type="password" class="form-control" placeholder="Digite uma senha *" id="senha" name="senha" required data-validation-required-message="Por favor, digite uma senha." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="senhaConf">Confirmar Senha</label>
														<input type="password" class="form-control" placeholder="Confirme Sua senha" id="senhaConf" name="senhaConf" required data-validation-required-message="Por favor, confirme sua senha." />
														<p class="help-block text-danger"></p>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="email">e-mail</label>
														<input type="email" class="form-control" placeholder="Seu E-mail *" id="email" name="email" required data-validation-required-message="Por favor, digite um endereço de e-mail." />
														<p class="help-block text-danger"></p>
													</div>
													<div class="form-group">
														<label for="telefone">Número de celular</label>
														<input type="phone" class="form-control" placeholder="Seu número de telefone *" id="telefone" name="telefone" required data-validation-required-message="Por favor, digite um número de telefone." />
														<p class="help-block text-danger"></p>
													</div>
													
													<div class="form-group">
													<label for="nome_pet">Sua Universidade / Faculdade</label>
													<select id="instituicao" class="form-control" name="instituicao" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="UFPI">Universidade Federal do Piauí</option>
														<option value="FSA">Faculdade Santo Agostinho</option>
													</select><p class="help-block text-danger"></p>
													<p id="pet" class="text-danger"></p>
													</div>
													
													<div class="form-group">
													<label for="minicurso">Você deseja Participar de algum Minicurso?</label>
													<select id="minicurso" class="form-control" name="minicurso" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="CLP">Minicurso de CLP - <?php echo ''.$c[0][2].' vagas, '.$c[0][3].' reservadas'; ?></option>
														<option value="EnergiasRenovaveis">Minicurso de Energias Renováveis - <?php echo ''.$c[1][2].' vagas, '.$c[1][3].' reservadas'; ?></option>
														<option value="OficinaEletronica">Oficina de Eletrônica - <?php echo ''.$c[2][2].' vagas, '.$c[2][3].' reservadas'; ?></option>
														<option value="PCB">Minicurso Prototipagem - <?php echo ''.$c[3][2].' vagas, '.$c[3][3].' reservadas'; ?></option>
														<option value="nada">Não desejo participar de nenhum minicurso.</option>
													</select><p class="help-block text-danger"></p>
													</div>
													
													<div class="form-group">
													<label for="trab">Você deseja Apresentar um Trabalho Científico?</label>
													<select id="trab" class="form-control" name="trab" size="1">
														<option value="0">Escolha uma opção:</option>
														<option value="1">Sim, desejo apresentar.</option>
														<option value="2">Não, não irei apresentar trabalho científico</option>
													</select><p class="help-block text-danger"></p>
													</div>
												  
												</div>
												<div class="clearfix"></div>
												<div class="col-lg-12 text-center">
												  
													<div id="success"></div>
													<button type="submit" onclick="enviarForm()" name="submit" class="btn btn-xl">Realizar a inscrição! <span class="glyphicon glyphicon-chevron-right"></span></button>
												</div>
											</div>
										</form>
									</div>
<?php
}

else if($acao == 'acomp') //falta editar os links dos certificados u.u
{
	//SE TIVER ARQUIVO
	$ccpf = validaCPF(retirar_letras_e_pontos($_POST['cpf']))?retirar_letras_e_pontos($_POST['cpf']):'';
	$rs = $con->prepare("SELECT * FROM eceel_inscricoes WHERE cpf = ?");
	$rs->bindParam(1, $ccpf);
	//$rs->bindParam(2, $_POST['senha']);

	//verificar se o usuário existe na lista dos alunos
	if($rs->execute()){
		if($rs->rowCount() == 0 || $rs->rowCount() > 1){
			echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Cpf digitado incorretamente. Tente novamente.
				</div>';
				exit(0);
		}else{
		
			//USUÁRIO EXISTE E ESTÁ CADASTRADO
			//EXIBIR DADOS DO USUÁRIO
			$arquivos = $rs->fetch(PDO::FETCH_OBJ);
			
			
			//if(strcmp($_POST['senha'],$arquivos->senha)!=0)
			if(!password_verify($_POST['senha'],$arquivos->senha))
			{
				//verificar senha com o banco de dados
				
				echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Senha inválida. Tente novamente.
				</div>';
				exit(0);
			
			}
			session_start();
			session_regenerate_id();
			$_SESSION['enepet2017_cpf'] = $arquivos->cpf;
			$ID_SESSAO = session_id();
			
			$stmt = $con->prepare("UPDATE `eceel_inscricoes` SET session_id=? WHERE cpf=?");
			$stmt->bindParam(1,$ID_SESSAO);
			$stmt->bindParam(2,$arquivos->cpf);
			if(!$stmt->execute())
			{			
			
				session_destroy();
				echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Erro ao logar. Tente novamente.
				</div>';
				exit(0);
			}
				
				echo '
				<div class="row">
				
					<div class="modal-body">
						<div class="panel panel-default">
							<div class="panel-heading">
								Meus dados - <a href="?p=acompanhar&acao=sair">Sair</a>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<!-- Nav tabs -->
								<ul class="nav nav-pills">
									<li class="active"><a href="#dados-pessoais" data-toggle="tab">Dados pessoais</a>
									</li>
									<li><a href="#trabalho-cientifico" onclick="limparTrabalho(\'' . $arquivos->cpf. '\')" data-toggle="tab">Trabalho científico</a>
									</li>
									<li><a href="#pagamento" data-toggle="tab">Status Pagamento</a>
									</li>
									<li><a href="#download-certificado" data-toggle="tab">Certificados e Declarações</a>
									</li>
								</ul>

								<!-- Tab panes -->
								<div class="tab-content">
									<div class="tab-pane fade in active" id="dados-pessoais">
										<h4>Meus dados pessoais</h4>
										
											<p>Nome: ' . $arquivos->nome. ' '.$arquivos->sobrenome.'</p>
											<p>CPF: ' . $arquivos->cpf. '</p>
											<p>Instituição: ' . $arquivos->instituicao. '</p>
											<p>Minicurso: ' .nomeMinicurso($arquivos->minicurso). '</p>
											<p>Apresentar Trabalho: '.(($arquivos->trabalho==1)?'Sim':'Não').'</p>';
								
								echo '
									</div>
									<div class="tab-pane fade" id="trabalho-cientifico">
										<div class="panel panel-success">
											<div class="panel-heading">
												Normas de Submissão dos Trabalhos
											</div>
											<div class="panel-body">
												<p align="justify">
												O arquivo do resumo deve ser enviado no formato PDF. Confira os prazos de envio e as regras para submissão em <a href="?p=submissao" target="_blank">SUBMISSÃO DE TRABALHOS</a>.
												Você pode enviar apenas um resumo. Caso seja enviado mais de um, o arquivo será sobrescrito.</p>
											</div>
											<div class="panel-footer">
												
											</div>
										</div>
										
										<h4>Trabalhos enviados</h4>
										<button type="button" onclick="campoTrabalho(\'' . $arquivos->cpf. '\')" class="btn btn-primary start"><i class="fa fa-upload"></i> Enviar Trabalho Científico</button>
										<!-- /.panel-heading -->
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>#</th>
															<th>Título</th>
															<th>Status</th>
															<th>Status Banner</th>
														</tr>
													</thead>
													<tbody id="atualizaTrab">
														<tr>
															<td colspan="4">Nenhum trabalho enviado. Clique no botão acima para submeter um trabalho.</td>
														</tr>
													</tbody>
												</table>
											</div>
											<!-- /.table-responsive -->
										</div>
										<!-- /.panel-body -->
										<div id="enviarTrabalho">
										</div>
									</div>
									<div class="tab-pane fade" id="pagamento">
										<h4>Status pagamento</h4>
										
											<div class="panel panel-success">
												<div class="panel-heading">
													Status Pagamento
												</div>
												<div class="panel-body">
												
													<p align="justify">Olá, caro (a) '.$arquivos->nome.'. No momento da inscrição, você selecionou 
													'.(($arquivos->minicurso=='nada')?'</b>não participar de minicurso</b>':'participar do minicurso <b>'.nomeMinicurso($arquivos->minicurso).'</b> ('.$precoComAlojamento.')' ).'.
													Você também escolheu '.(($arquivos->trabalho==1)?'<b>Apresentar</b>':'<b>Não Apresentar</b>').' seu trabalho científico na nossa exposição de banners ('.$precoSemAlojamento.').</p>
													<p align="center">Você tem até o dia '.(date_format(date_create($dataFinalDeposito),"d/m/Y")).' para ir à sala do PET Potencia - Bloco 8, CT UFPI - e efetuar o pagamento.</p>
													'.(($arquivos->minicurso=='nada')?'':'<p align="center"> Gostariamos de lembrar que as vagas dos minicursos serão reservados por ordem de pagamento, então garanta logo a sua!</p>').'
												</div>
												<div class="panel-footer">';
								if($arquivos->justpaid == 1)
								{
									echo '
												<p align="justify"> 
												<i class="fa fa-check"></i>
												O seu pagamento foi confirmado! Que bom! </p>';
									
									if($arquivos->minicurso != 'nada'){
										
										$vagas = $con->prepare("UPDATE `vagas_minicursos` SET ocupadas=ocupadas+? WHERE nome=?");
										$vagas->bindParam(1,$arquivos->pago);
										$vagas->bindParam(2,$arquivos->minicurso);
										$dummy = $vagas->execute();
										if($dummy)
										{
											echo '
													<p align="justify"> 
													<i class="fa fa-check"></i>
													Sua Vaga no '.nomeMinicurso($arquivos->minicurso).' foi reservada.</p>';
											$first = $con->prepare("UPDATE `eceel_inscricoes` SET justpaid=0 WHERE cpf=?");
											$first->bindParam(1,$arquivos->cpf);
											$first->execute();
											
											$status_min = $con->prepare("UPDATE `minicurso_inscritos` SET is_pago=1 WHERE cpf=?");
											$status_min->bindParam(1,$arquivos->cpf);
											$status_min->execute();
											
										}			
									}	
								}else{
									if($arquivos->pago){
										echo '
												<p align="justify"> 
												<i class="fa fa-check"></i>
												O seu pagamento para o minicurso foi confirmado! Que bom! </p>';
									}
									else{
										echo '
													<p align="justify"> 
													<i class="fa fa-times"></i>
													O seu pagamento para minicursos ainda não foi confirmado! Caso tenha realizado o pagamento, por favor nos contate.</p>';
										}
								}
								if($arquivos->trabalho == 1){
									if($arquivos->pago_trab){
										echo '
												<p align="justify"> 
												<i class="fa fa-check"></i>
												O seu pagamento para a apresentação de banners foi confirmado! Que bom! </p>';
									}
									else{
										echo '
													<p align="justify"> 
													<i class="fa fa-times"></i>
													O seu pagamento para apresentação de banners ainda não foi confirmado! Caso tenha realizado o pagamento, por favor nos contate.</p>';
									}
								}
								
								
								echo '
												</div>
											</div>
									</div>
									<div class="tab-pane fade" id="download-certificado">
										<h4>Certificados e Declarações</h4>';
										
										//pegar dados dos minicursos e flag da apresentação:
										$min = $con->prepare("SELECT * FROM `minicurso_inscritos` WHERE cpf=?");
										$min->bindParam(1,$arquivos->cpf);
										$min->execute();
										$naotenhomaiscriatividadeparatags = $min->fetch(PDO::FETCH_OBJ);
										
										if( (strtotime(date("Y-m-d"))) > strtotime($dataEv) ) {
										echo'
										<p><a href="server.php?decl='.$arquivos->cpf.'" target="_blank"><i class="fa fa-file-pdf-o"></i> Declaração de presença no evento</a></p>
										'.(($arquivos->pago==1 && $naotenhomaiscriatividadeparatags->certificado==1)? '
										<p><a href="certificado.php?cpf='.$arquivos->cpf.'" target="_blank"><i class="fa fa-file-pdf-o"></i> Certificado de participação em minicurso (Aleluia!)</a></p>':'<p>Certificado não disponível (minicurso não pago ou frequências insuficientes.)</p>' ).
										''.(($arquivos->pago_trab==1 && $arquivos->trab_apresentado==1)? '
										<p><a href="certificado.php?cpf='.$arquivos->cpf.'" target="_blank"><i class="fa fa-file-pdf-o"></i> Certificado de apresentação de trabalho cientifico (Aleluia!)</a></p>':'<p>Certificado não disponível (trabalho não apresentado ou taxa não paga).</p>').'';
										}
										else{
											echo '
												<p align="center"> 
												<i class="fa fa-times"></i>
												Os certificados ainda não estão disponíveis.</p>';
										}
										
										//<p><i class="fa fa-file-pdf-o"></i> Recibo de pagamento sai até quarta-feira.</p>
								echo '	
										<p><i class="fa fa-info"></i> Confira o Certificado de apresentação na aba TRABALHOS CIENTÍFICOS.</p>
									</div>
									</div>
								</div>
								<p>iv ECEEL</p>
							</div>
							<!-- /.panel-body -->
						</div>
					</div>
				</div>
				';
		}		
	}

}
else if($acao == 'addTrabalho')
{


	if((strtotime(date("Y-m-d"))) > strtotime(end($dataSubmissaoFim)))
	{
		//fora do prazo
				echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Venceu o prazo para submissão de trabalhos.
				</div>';
				
				exit(0);
	}
	//SE TIVER ARQUIVO
	$ccpf = validaCPF(retirar_letras_e_pontos($_POST['cpf']))?retirar_letras_e_pontos($_POST['cpf']):'';
	$rs = $con->prepare("SELECT * FROM eceel_inscricoes WHERE cpf = ?");
	$rs->bindParam(1, $ccpf);

	//verificar se o usuário existe na lista dos alunos
	if($rs->execute()){
		if($rs->rowCount() == 0){
			echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Cpf inválido. Tente novamente.
				</div>';
		}else{
			
			//USUÁRIO EXISTE E ESTÁ CADASTRADO
			//EXIBIR DADOS DO USUÁRIO
			$arquivos = $rs->fetch(PDO::FETCH_OBJ);
			session_start();
			$SESSAO_CPF = isset($_SESSION['enepet2017_cpf'])?$_SESSION['enepet2017_cpf']:'';
			// verificar se o usuário está logado.
			if($arquivos->session_id != session_id() || $arquivos->cpf != $SESSAO_CPF){
				#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
				
				echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Você foi deslogado. <a href="?p=acompanhar">Clique aqui para Entrar</a>.
				</div>';
				 session_destroy();
				 exit(0);
			}
			
			echo '

			<h4>Nova Submissão</h4>
			<form id="FormTrabalho" enctype="multipart/form-data">
			  <div class="form-group">
				<label for="titulo">Título do trabalho</label>
				<input type="text" class="form-control" name="titulo" id="titulo" placeholder="Digite o título do trabalho">
				
			  </div>
			  <div class="form-group">
				<label for="autor">Autor ('.$arquivos->nome.')</label>
				<input type="text" class="form-control" name="cpf" id="cpf" value="'.$arquivos->cpf.'" readonly>
				
			  </div>
			  <div class="form-group" id="coautores">
				<label for="coautor">Co-autores</label>
				
			  </div>
			  <div class="form-group">
				<button type="button" onclick="addCoautor()" class="btn btn-primary start"><i class="fa fa-plus"></i> Adicionar campo</button><br>
				<small id="fileHelp" class="form-text text-muted">Você poderá colocar vários co-autores. 1) Clique em ADICIONAR CAMPO; 2) Digite o nome do co-autor. Voltar para o passo 1 para adicionar mais um co-autor (se houver) </small>
				
			  </div>
			  <div class="form-group">
				<label for="exampleSelect1">Área de conhecimento</label>
				<select class="form-control" name="area" id="exampleSelect1">
				  <option value="0">Selecione uma Opção</option>
				  <option value="Potência">Potência</option>
				  <option value="Automação e Controle">Automação e Controle</option>
				  <option value="Microeletrônica">Microeletrônica</option>
				  <option value="Computação">Computação</option>
				  <option value="Eletrônica de Potência">Eletrônica de Potência</option>
				  <option value="Energias Renováveis">Energias Renováveis</option>
				  <option value="Telecomunicações">Telecomunicações</option>
				  <option value="Aplicações da Engenharia Elétrica em Outros Setores">Aplicações da Engenharia Elétrica em Outros Setores</option>
				  <option value="Ensino e Extensão">Ensino e Extensão</option>
				</select>
			  </div>
				
			  <div class="form-group" style="display:block; margin:auto;">
				<label for="artigoPDF">Selecionar o RESUMO em PDF</label>
				<input type="file" class="form-control-file" name="arquivoPDF" id="artigoPDF" aria-describedby="fileHelp" style="margin: 0 auto !important;">
				<small id="fileHelp" class="form-text text-muted">Selecione o resumo do seu trabalho científico em PDF.</small>
			  </div>
			
			  <div class="form-check">
				<label class="form-check-label">
				  <input type="checkbox" name="checkAutoriza" class="form-check-input">
				  Eu, '.$arquivos->nome.', aceito ceder ao iv ECEEL o direito de reprodução do trabalho enviado das maneiras que forem convenientes ao evento.
				</label>
			  </div>
			  <button type="submit" onclick="uploadTrabalho()" class="btn btn-success">Iniciar upload</button>
			</form>';
		}
	}
}
else if($acao == 'statusTrabalho')
{

	//SE TIVER ARQUIVO
	$id = retirar_letras_e_pontos($_POST['id']);
	$rs = $con->prepare("SELECT * FROM `trabalhos_cientificos` WHERE id = ?");
	$rs->bindParam(1, $id);

	//verificar se o trabalho existe na lista dos trabalhos científicos
	if($rs->execute()){
		if($rs->rowCount() == 0){
			echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> ID inválido. Tente novamente.
				</div>';
		}else{
			
			
			//USUÁRIO EXISTE E ESTÁ CADASTRADO
			//EXIBIR DADOS DO USUÁRIO
			$arquivos = $rs->fetch(PDO::FETCH_OBJ);
			session_start();
			$SESSAO_CPF = isset($_SESSION['enepet2017_cpf'])?$_SESSION['enepet2017_cpf']:'';
			// verificar se o usuário está logado.
			if($arquivos->cpf != $SESSAO_CPF){
				#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
				 
				echo '<div class="alert alert-danger" role="alert">
				  <strong>Opa!</strong> Você foi deslogado. <a href="?p=acompanhar">Clique aqui para Entrar</a>.
				</div>';
				 session_destroy();
				 exit(0);
			}
			
			$coautores = json_decode($arquivos->autor2);
			echo '

			<h4>Status Submissão</h4>
			<form id="FormTrabalho" enctype="multipart/form-data">
			  <div class="form-group">
				<label for="titulo">Título do trabalho</label>
				<input type="text" class="form-control" value="'.$arquivos->titulo.'" readonly>
				
			  </div>
			  <div class="form-group">
				<label for="autor">Autor</label>
				<input type="text" class="form-control" name="cpf" value="'.$arquivos->cpf.'" readonly>
				
			  </div>';
			  $cont = 1;
			  foreach($coautores as $file)
			  {
			  echo '
			  <div class="form-group">
				<label for="autor2">Co-autor '.$cont.'</label>
				<input type="text" class="form-control" value="'.mb_strtoupper($file,'utf-8').'" readonly>
				
			  </div>';
			  $cont++;
			  }
			  echo '
			  <div class="form-group">
				<label for="area">Área de conhecimento</label>
				<input type="text" class="form-control" value="'.$arquivos->area.'" readonly>
				
			  </div>';
			  
			  
			  if($arquivos->aprovado == 1)
			  {
			  
			  echo '
			  <div class="alert alert-success" role="alert">
			  <h4 class="alert-heading"><i class="fa fa-smile-o"></i> Parabéns. Seu resumo foi aprovado pela nossa Comissão Científica.</h4>
			  <p>Comentário da banca: "'. $arquivos->comentario .'"</p>';
			  
				if($arquivos->aprovado_banner == 2 || $arquivos->aprovado_banner == 0)
				{
					echo '
					<div class="alert alert-warning" role="alert">
					  <p class="mb-0">Aproveite e envie logo o arquivo PDF do banner.</p>
					  
					  <div class="form-group">
						<label for="arquivoPDF">Selecionar o banner em PDF</label>
						<input type="file" class="form-control-file" name="arquivoPDF" id="arquivoPDF" aria-describedby="fileHelp">
						<small id="fileHelp" class="form-text text-muted">Selecione o banner do seu trabalho científico em PDF.</small>
					  </div>
					</div>
					  <div class="form-check">
						<label class="form-check-label">
						  <input type="checkbox" name="checkAutoriza" class="form-check-input">
						  Eu, portador do CPF n. '.$arquivos->cpf.', aceito ceder ao XVI ENEPET o direito de reprodução do trabalho enviado das maneiras que forem convenientes ao evento.
						</label>
					  </div>
					  <button type="submit" onclick="uploadTrabalho2(\''.$arquivos->id.'\')" class="btn btn-success">Iniciar upload do banner</button>
					</div>';
				}else if($arquivos->aprovado_banner == 1){
				
					echo '
						<div class="alert alert-success" role="alert">
						  <strong><i class="fa fa-check"></i> O banner foi aprovado pela comissão.</strong> Já que está tudo ok, realize a impressão do seu banner e guarde em sua bagagem ;).
						</div>';
				
				}else{
				
					echo '
						<div class="alert alert-warning" role="alert">
						  <strong><i class="fa fa-clock-o"></i> Aguarde a avaliação do seu banner.</strong>
						</div>';
				
				}
			echo '</div>';
			
			}else if($arquivos->aprovado == 2)
			{
			  echo '
			  
				<div class="alert alert-warning" role="alert">
				  <h4 class="alert-heading">O seu resumo foi aprovado (com restrições) pela nossa Comissão Científica.</h4>
				  <p>Comentário da banca: "'.$arquivos->comentario.'"</p>
				  <p class="mb-0">Aproveite e envie logo o arquivo PDF do resumo e/ou termo corrigido.</p>
				  
				  <div class="form-group">
					<label for="arquivoPDF">Selecionar o resumo em PDF</label>
					<input type="file" class="form-control-file" name="arquivoPDF" id="arquivoPDF" aria-describedby="fileHelp">
					<small id="fileHelp" class="form-text text-muted">Selecione o resumo do seu trabalho científico em PDF.</small>
				  </div>
				  <div class="form-group">
					<label for="arquivoPDF">Selecionar o termo em PDF</label>
					<input type="file" class="form-control-file" name="arquivoPDF2" id="arquivoPDF2" aria-describedby="fileHelp">
					<small id="fileHelp" class="form-text text-muted">Selecione o termo do seu trabalho científico em PDF.</small>
				  </div>
				</div>';
			
			echo '
			  <div class="form-check">
				<label class="form-check-label">
				  <input type="checkbox" name="checkAutoriza" class="form-check-input">
				  Eu, portador do CPF n. '.$arquivos->cpf.', aceito ceder ao XVI ENEPET o direito de reprodução do trabalho enviado das maneiras que forem convenientes ao evento.
				</label>
			  </div>
			  <button type="submit" onclick="uploadTrabalho2(\''.$arquivos->id.'\')" class="btn btn-success">Iniciar upload corrigido</button>';
			
			
			}else if($arquivos->aprovado == 4)
			{
			
			  echo '
			  
				<div class="alert alert-danger" role="alert">
				  <h4 class="alert-heading"><i class="fa fa-frown-o"></i> O seu resumo NÃO foi aprovado pela nossa Comissão Científica.</h4>
				  <p>Comentário da banca: "'.$arquivos->comentario.'"</p>
				  <p class="mb-0">Você não seguiu as regras de submissão de trabalho para o ENEPET 2017.</p>
				  
				</div>';
			
			}else{
			
			echo '
				<div class="alert alert-success" role="alert">
				  <strong><i class="fa fa-clock-o"></i> O seu trabalho está em fase de avaliação.</strong> Aguarde e-mail da comissão científica do ENEPET 2017.
				</div>';
			
			}
			echo '
			</form>';
		}
	}
}
else if($acao == 'uploadTrabalhoCorrig')
{

	if(!empty($_POST['id']) && 
		isset($_POST['cpf']) && 
		isset($_POST['checkAutoriza']) && 
		!empty($_FILES['arquivoPDF']['tmp_name']))
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
				echo '<div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Id inválido. Tente novamente.
					</div>';
			}else{
		
				if($_FILES['arquivoPDF']['type'] != 'application/pdf'){
					echo 'Opa! O arquivo não é pdf.';
					exit(0);
				}
					
				//USUÁRIO EXISTE E ESTÁ CADASTRADO
				//EXIBIR DADOS DO USUÁRIO
				$arquivos = $rs->fetch(PDO::FETCH_OBJ);
				$links = json_decode($arquivos->link);
				
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
				$caminho = $_FILES['arquivoPDF']['tmp_name'];
				$caminho2 = $_FILES['arquivoPDF']['name'];
				$infos = pathinfo($caminho2);
				
				if($arquivos->aprovado==2)
				{
				//Trabalho corrigido
					
					$nome_arquivo = 'resumo_'. $novo_nome.'_v2.'.$infos['extension'];
					//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
					//Aguardando uma nova análise
					$aprovado = 3;
					//Aguardando envio do banner
					$aprovado_banner = 0;
					$links['corrigido'] = $nome_arquivo;
				
				}else if($arquivos->aprovado==1)
				{
				//trabalho aprovado
					$aprovado = 1;
					if($arquivos->aprovado_banner==0 || $arquivos->aprovado_banner==2)
					{
						$nome_arquivo = 'banner_'. $novo_nome.'.'.$infos['extension'];
						//$nome_arquivo = str_replace(" ", "_", $nome_arquivo);
						//Aguardando análise
						$aprovado_banner = 3;
						$links['banner'] = $nome_arquivo;
						//Status corrigir banner
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
				$arquivo = $diretorio.$nome_arquivo;
				//move_uploaded_file($value, $nome_original)
				if(!move_uploaded_file($caminho, $arquivo)){echo 'Erro ao mover arquivo.'; exit(0);}
				//$autor = $usuario->getNome();
				$infos = pathinfo($arquivo);
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
}
else if($acao == 'updateTrabalho')
{
//SE TIVER ARQUIVO
	$ccpf = retirar_letras_e_pontos($_POST['cpf']);
	
	session_start();
	// verificar se o usuário está logado.
	if($ccpf != $_SESSION['enepet2017_cpf']){
		#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
		 
		echo '
				<tr>
					<td colspan="4"><div class="alert alert-danger" role="alert">
					  <strong>Opa!</strong> Você foi deslogado. <a href="?p=acompanhamento">Clique aqui para Entrar novamente</a>.
					</div></td>
				</tr>';
		 session_destroy();
		 exit(0);
	}
	$rs = $con->prepare("SELECT * FROM `trabalhos_cientificos` WHERE cpf = ?");
	$rs->bindParam(1, $ccpf);

	//verificar se o usuário existe na lista dos alunos
	if($rs->execute()){
		if($rs->rowCount() == 0){
			echo '
				<tr>
					<td colspan="4">Nenhum trabalho enviado. Clique no botão acima para submeter um trabalho.</td>
				</tr>';
		}else{
			
			
			//USUÁRIO EXISTE E ESTÁ CADASTRADO
			//EXIBIR DADOS DO USUÁRIO
			$cont = 1;
			while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
			{
				echo '
				<tr'.(($arquivos->aprovado == 1)?' class="success"':(($arquivos->aprovado == 2)?' class="warning"':(($arquivos->aprovado == 4)?' class="danger"':''))).'>
					<td>#'.$cont.'</td>
					<td><a href="#" onclick="statusTrabalho(\''.$arquivos->id.'\')">'.$arquivos->titulo.'</a><br/><a href="server.php?carta='.$arquivos->id.'" target="_blank">Gerar carta de Aceite</a></td>
					<td>'.(($arquivos->aprovado == 1)?'<i class="fa fa-smile-o"></i> Aprovado':(($arquivos->aprovado == 2)?'<i class="fa fa-meh-o"></i> Corrigir trabalho':(($arquivos->aprovado == 3)?'<i class="fa fa-clock-o"></i> Aguardando nova avaliação':(($arquivos->aprovado == 4)?'<i class="fa fa-frown-o"></i> Não aprovado':'<i class="fa fa-clock-o"></i> Aguardando avaliação')))).'</td>
					<td>'.(($arquivos->aprovado_banner == 1)?'<i class="fa fa-smile-o"></i> Aprovado':(($arquivos->aprovado_banner == 2)?'<i class="fa fa-meh-o"></i> Corrigir trabalho':(($arquivos->aprovado_banner == 3)?'<i class="fa fa-clock-o"></i> Aguardando análise':(($arquivos->aprovado ==1)? '<i class="fa fa-clock-o"></i> Aguardando envio':'')))).'</td>
				</tr>';
				if ($arquivos->aprovado == true)
				{
				
					echo '
					<tr>
						<td colspan="4"><a href="cert_trabalho.php?idtrab='.$arquivos->id.'" target="_blank"><i class="fa fa-file-pdf-o"></i> BAIXAR CERTIFICADO DE APRESENTAÇÃO #'.$cont.' <i class="fa fa-trophy"></i> (Glóriaa!)</a></td>
					</tr>';
				
				}
				$cont = $cont + 1;
			}
			echo '
				<tr>
					<td colspan="4">Clique sobre o título do trabalho para consultar mais detalhes do mesmo <br />(e também enviar banner ou reenviar trabalho corrigido)</td>
				</tr>';
			
		}
	}

}
else if($acao == 'autocomp')
{


	//SE TIVER ARQUIVO
	$termo = isset($_GET['term'])?$_GET['term']:'';
	$termo = '%'.$termo.'%';
	$rs = $con->prepare("SELECT * FROM `pets_nordeste` WHERE `nome_pet` LIKE :keyword");
	$rs->bindParam(':keyword',$termo, PDO::PARAM_STR);

	//verificar se o usuário existe na lista dos alunos
	if($rs->execute()){
			
		//USUÁRIO EXISTE E ESTÁ CADASTRADO
		//EXIBIR DADOS DO USUÁRIO
		while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
		{
		
			$data[] = $arquivos->nome_pet;
		
		}
		echo json_encode($data);
	}
}
else if($acao == 'uploadComprovante')
{

	if(!empty($_POST['cpf']) && 
		!empty($_FILES['comprovante']['tmp_name']))
	{

		//SE TIVER ARQUIVO
		$ccpf = validaCPF(retirar_letras_e_pontos($_POST['cpf']))?retirar_letras_e_pontos($_POST['cpf']):'';
		$rs = $con->prepare("SELECT * FROM inscricoes WHERE cpf = ? limit 1");
		$rs->bindParam(1, $ccpf);

		//verificar se o usuário existe na lista dos alunos
		if($rs->execute()){
			if($rs->rowCount() == 0){
				echo 'cpf';
			}else{
		
				//USUÁRIO EXISTE E ESTÁ CADASTRADO
				//EXIBIR DADOS DO USUÁRIO
				$arquivos = $rs->fetch(PDO::FETCH_OBJ);
				$rs = null;
				session_start();
				// verificar se o usuário está logado.
				if($arquivos->session_id != session_id() || $arquivos->cpf != $_SESSION['enepet2017_cpf']){
					#abaixo, criamos uma variavel que terá como conteúdo o endereço para onde haverá o redirecionamento:  
					 
					echo 'erro';
					 session_destroy();
					 exit(0);
				}
				if($arquivos->pago){ echo 'Opa! Comprovante já enviado.'; exit(0);}
				
				if($_FILES['comprovante']['type'] != 'image/png' && $_FILES['comprovante']['type'] != 'image/jpeg'&& $_FILES['comprovante']['type'] != 'application/pdf'){
					//print_r($_FILES);
					echo 'naoeimg';
					exit(0);
				}
				
				//$links = json_decode($arquivos->link);
				
				$diretorio = 'admin/pages/ComprovantesPagamento/'.$arquivos->cpf.'/';
				$lista_comprov = glob("$diretorio{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG}", GLOB_BRACE);
				if(!is_dir($diretorio))mkdir($diretorio);
				$novo_nome = $arquivos->cpf;
				$caminho = $_FILES['comprovante']['tmp_name'];
				$caminho2 = $_FILES['comprovante']['name'];
				$infos = pathinfo($caminho2);
				$nome_arquivo = 'comprovante_'. $novo_nome.'_'.(count($lista_comprov)+1).'.'.$infos['extension'];
				//$link = $nome_arquivo;
				$arquivo = $diretorio.$nome_arquivo;
				//move_uploaded_file($value, $nome_original)
				if(!move_uploaded_file($caminho, $arquivo)){echo 'naoinserido'; exit(0);}
				//$links['comprovante'] = $nome_arquivo;
				//$autor = $usuario->getNome();
				$infos = pathinfo($arquivo);
				
				//$link = json_encode($links);
				// $stmt = $con->prepare("INSERT INTO `trabalhos_cientificos` VALUES ('',?,?,?,?,?,0,0,CURRENT_TIMESTAMP,'',?,?)");
				// $stmt->bindParam(1,$titulo);
				// $stmt->bindParam(2,$arquivos->cpf); 
				// $stmt->bindParam(3,$autor2);
				// $stmt->bindParam(4,$autor3);
				// $stmt->bindParam(5,$autor4);
				// $stmt->bindParam(6,$_POST['area']);
				// $stmt->bindParam(7,$link);
				$stmt = $con->prepare("UPDATE `inscricoes` SET pago=1 WHERE cpf=?");
				$stmt->bindParam(1,$arquivos->cpf);
				
				if($stmt->execute())
				{
					echo 'sucesso';
		
					$name = $arquivos->nome;
					$email_address = $arquivos->email;
					$nome_pet = $arquivos->pet;
					// Create the email and send the message
					//$to = $email_address; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
					$email_subject = $name.', seu comprovante de pagamento foi enviado.';
					$email_subject = '=?UTF-8?B?'.base64_encode($email_subject).'?=';
					//$name = utf8_decode($name);
					$email_body = utf8_decode("<html><body><p>Olá! Acabamos de receber seu comprovante de pagamento. <br><br>Aqui estão os detalhes da sua inscrição:<br><br>Nome: $name<br>Email: $email_address<br>$nome_pet<br><br>Logo em breve enviaremos a confirmação do seu pagamento. Fique atento às datas. Todas as informações a respeito da submissão de trabalhos estão <a href=\"http://www.enepet2017.com/?p=submissao\">AQUI</a>: datas importantes, modelos de resumo e banner.<br><br>Submeter trabalhos, pagar a inscrição, verificar o status de seus trabalhos e de sua inscrição, escolher atividades científicas que quer participar. Tudo isso você faz na página de Acompanhamento de Inscrição! <a href=\"http://www.enepet2017.com/?p=acompanhamento\">Clique aqui para acompanhar a sua inscrição.</a><br><br><br></a><br><br><br>Comissão Organizadora<br>XVI Encontro Nordestino dos Grupos PETs - ENEPET 2017.<br><br><a href=\"http://enepet2017.com\"><img src=\"http://enepet2017.com/img/header-bg2.jpg\" width=\"100%\" border=\"0\"></p></body></html>");
 
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
					$mail->addAddress($email_address, $name);
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
					$mail->send();
				
				}else{
				
					echo 'naoinserido';
					
					@unlink($arquivo);
				}
			}
		}
		
	}else{
		echo 'vazio';
	}

}
else if($acao == 'listaPets')
{
		header('Content-Type: text/html; charset=utf-8');
		$rs = $con->prepare("SELECT * FROM pets_nordeste order by nome_pet asc");
		if($rs->execute())
		{
			if($rs->rowCount()){
				while($arquivos = $rs->fetch(PDO::FETCH_OBJ))
				{
					echo $arquivos->nome_pet .'<br>';
				}
			}
		
		}

}
?>
