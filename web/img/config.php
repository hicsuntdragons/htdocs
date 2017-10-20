<?php
$dataFimInscricao = array('2017-04-03','2017-04-14');
//$dataSubmissaoInicio = '2017-02-15';
$dataSubmissaoFim = array('2017-03-08','2017-03-13','2017-03-15');
$dataFinalAprovaBanca = array('2017-03-15','2017-03-20','2017-03-23');
$dataFinalReenvio = array('2017-03-20','2017-03-24','2017-03-29');
$dataFinalAprovaReenvio = array('2017-03-27', '2017-03-31', '2017-03-31');
$dataFinalDeposito = '2017-04-17';
$dataFinalBanner = '2017-04-21';
$emailOuvidoria = "ouvidoria.enepet2017@outlook.com";
$precoComAlojamento = 'R$ 140,00 (2º Lote)';
$precoSemAlojamento = 'R$ 120,00 (2º Lote)';

$PrecoComAlojamento = array('R$ 120,00','R$ 140,00');
$PrecoSemAlojamento = array('R$ 100,00','R$ 120,00');
function precoRecibo ($alojamento, $lote)
{

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

$receberComprovante = false;
$informacoesConta = 'Banco do Brasil - Agência: 3791-5, Conta: 10055-2, Favorecido: FADEX XVI ENEPET';
?>