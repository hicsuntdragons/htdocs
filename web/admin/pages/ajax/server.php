<?php

//server.php para admin

include_once '../../../inc/conexao.php';
include_once '../../../inc/config.php';
$acao = isset($_GET['p'])?$_GET['p']:'';

if($acao == 'porEstado')
{
	
	$rs = $con->prepare("SELECT estado,count(*) FROM inscricoes group by estado order by count(*) asc");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs->execute();
	while($arquivo = $rs->fetch(PDO::FETCH_NUM))
	{
		//echo 'Estado: '. $arquivo[0].' - n. '.$arquivo[1].'<br>';

		$data[] = ['label'=>$arquivo[0],'data'=>$arquivo[1]];
	}
	
	echo json_encode($data);
}
elseif($acao == 'porAlojamento')
{
	
	$rs = $con->prepare("SELECT estado,alojamento,pago,count(*) FROM inscricoes group by estado,alojamento,pago order by estado asc");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs->execute();
	
	
	while($arquivo = $rs->fetch(PDO::FETCH_NUM))
	{
		//echo 'Estado: '. $arquivo[0].' - n. '.$arquivo[1].'<br>';
		// if($arquivo[1] == 1)
			// $data[] = ['y'=>$arquivo[0],'a'=>$arquivo[2],'b'=>0];
		// else{
			// $data[count($data)-1]['b'] = $arquivo[2];
		
		// }
		if($arquivo[1] == 1)
		{
			if(!isset($data))
					$data[] = ['y' => $arquivo[0], 'a' => 0,'b' =>0, 'c' => 0];
				
			if(isset($data))	
				if($data[count($data)-1]['y']!=$arquivo[0])
					$data[] = ['y' => $arquivo[0], 'a' => 0,'b' =>0, 'c' => 0];
			if($arquivo[2] == 0)
			{
				$data[count($data)-1]['a'] = $arquivo[3];
			}elseif($arquivo[2] == 1)
			{
				$data[count($data)-1]['b'] = $arquivo[3];
			}elseif($arquivo[2] == 2)
			{
				$data[count($data)-1]['c'] = $arquivo[3];
			}
		}
	}
	
	echo json_encode($data);
	
	
	// [{
				// y: '2006',
				// a: 100,
				// b: 90
			// }, {
				// y: '2007',
				// a: 75,
				// b: 65
			// }, {
				// y: '2008',
				// a: 50,
				// b: 40
			// }, {
				// y: '2009',
				// a: 75,
				// b: 65
			// }, {
				// y: '2010',
				// a: 50,
				// b: 40
			// }, {
				// y: '2011',
				// a: 75,
				// b: 65
			// }, {
				// y: '2012',
				// a: 100,
				// b: 90
			// }]
}
elseif($acao == 'trabPorEstado')
{
	
	$rs = $con->prepare("SELECT estado,count(*) FROM `trabalhos_cientificos` group by estado order by estado asc");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs->execute();
	
	
	while($arquivo = $rs->fetch(PDO::FETCH_NUM))
	{
		//echo 'Estado: '. $arquivo[0].' - n. '.$arquivo[1].'<br>';
		//if($arquivo[1] == 1)
			$data[] = ['y'=>$arquivo[0],'a'=>$arquivo[1]];
		// else{
			// $data[count($data)-1]['b'] = $arquivo[2];
		
		// }
	}
	
	echo json_encode($data);
	
	
	// [{
				// y: '2006',
				// a: 100,
				// b: 90
			// }, {
				// y: '2007',
				// a: 75,
				// b: 65
			// }, {
				// y: '2008',
				// a: 50,
				// b: 40
			// }, {
				// y: '2009',
				// a: 75,
				// b: 65
			// }, {
				// y: '2010',
				// a: 50,
				// b: 40
			// }, {
				// y: '2011',
				// a: 75,
				// b: 65
			// }, {
				// y: '2012',
				// a: 100,
				// b: 90
			// }]
}
elseif($acao == 'area')
{

	$rs = $con->prepare("SELECT area,count(*) FROM `trabalhos_cientificos` group by area order by count(*) asc");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs->execute();
	while($arquivo = $rs->fetch(PDO::FETCH_NUM))
	{
		//echo 'Estado: '. $arquivo[0].' - n. '.$arquivo[1].'<br>';

		$data[] = ['label'=>$arquivo[0],'data'=>$arquivo[1]];
	}
	echo json_encode($data);
}
elseif($acao == 'pag')
{

	$rs = $con->prepare("SELECT pago,count(*) FROM inscricoes group by pago");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs->execute();
	while($arquivo = $rs->fetch(PDO::FETCH_NUM))
	{
		//echo 'Estado: '. $arquivo[0].' - n. '.$arquivo[1].'<br>';

		$data[] = ['label'=>($arquivo[0]==0)?('Não Pago'):(($arquivo[0]==1)?('Comprovante enviado'):('Pago')),'data'=>$arquivo[1]];
	}
	echo json_encode($data);
}
elseif($acao == 'numeros')
{

	//pegar a quantidade de inscritos
	$rs1 = $con->prepare("SELECT count(*) FROM inscricoes");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs1->execute();
	$data['num_inscricoes'] = $rs1->fetchColumn();
	
	//pegar a quantidade de pagamentos (comprovante)
	$rs2 = $con->prepare("SELECT count(*) FROM inscricoes where pago=1");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs2->execute();
	$data['num_pagamentos'] = $rs2->fetchColumn();
	
	//pegar a quantidade de pagamentos confirmados
	$rs4 = $con->prepare("SELECT count(*) FROM inscricoes where pago=2");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs4->execute();
	$data['confirmados'] = $rs4->fetchColumn();
	
	//pegar a quantidade de trabalhos submetidos
	$rs3 = $con->prepare("SELECT count(*) FROM `trabalhos_cientificos` where aprovado=0 or aprovado=3");
	//$rs->bindParam(1, $ccpf);
	//verificar se o usuário existe na lista dos alunos
	$rs3->execute();
	$data['num_submissoes'] = $rs3->fetchColumn();
	
	
	
	echo json_encode($data);

}

?>