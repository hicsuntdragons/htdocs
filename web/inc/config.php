<?php
$dataFimInscricao = array('2017-11-13','2017-11-13');
$dataEv = '2017-11-17';
//$dataSubmissaoInicio = '2017-02-15';
$dataSubmissaoFim = array('2017-11-17','2017-11-17','2017-11-17');
$dataFinalAprovaBanca = array('2017-11-17','2017-11-17','2017-11-17');
$dataFinalReenvio = array('2017-11-17','2017-11-17','2017-11-17');
$dataFinalAprovaReenvio = array('2017-11-17', '2017-11-17', '2017-11-17');
$dataFinalDeposito = '2017-11-17';
$dataFinalBanner = '2017-11-17';
$emailOuvidoria = "ouvidoria.enepet2017@outlook.com";
$precoComAlojamento = 'R$ 10,00';
$precoSemAlojamento = 'R$ 15,00';

$PrecoComAlojamento = array('R$ 120,00 (cento e vinte reais)','R$ 140,00 (cento e quarenta reais)');
$PrecoSemAlojamento = array('R$ 100,00 (cem reais)','R$ 120,00 (cento e vinte reais)');
function precoRecibo($alojamento, $lote)
{
global $PrecoComAlojamento;
global $PrecoSemAlojamento;

	switch($lote)
	{
		case 0:
		//lote 1
			if($alojamento == 1)
			{
				return $PrecoComAlojamento[0];
			}else{
			
				return $PrecoSemAlojamento[0];
			}
		case 1:
		//lote 2
			if($alojamento == 1)
			{
				return $PrecoComAlojamento[1];
			}else{
			
				return $PrecoSemAlojamento[1];
			}
	}
				
}
function nomeMinicurso($minicurso){
	switch($minicurso){
		case 'CLP':
			return 'Minicurso de CLP';
		case 'EnergiasRenovaveis':
			return 'Minicurso de Energias Renováveis';
		case 'OficinaEletronica':
			return 'Oficina de Eletrônica';
		case 'PCB':
			return 'Minicurso Prototipagem';
		default:
			return 'Nenhum';
	}
}

$receberComprovante = false;
$informacoesConta = 'Banco do Brasil - Agência: 3791-5, Conta: 10055-2, Favorecido: FADEX XVI ENEPET';
?>