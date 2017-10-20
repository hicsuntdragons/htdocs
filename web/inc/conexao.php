<?php
 date_default_timezone_set('America/Sao_Paulo');
 $con = new PDO('mysql:host=localhost;dbname=ene', 'root','')or print (mysql_error());
 //$con = new PDO('mysql:host=localhost;dbname=enepet2017', 'root','')or print (mysql_error());
 
 $a = $con->prepare("SET NAMES 'utf8'"); 
 $a->execute();
 $b = $con->prepare('SET character_set_connection=utf8'); 
 $b->execute();
 $c = $con->prepare('SET character_set_client=utf8'); 
 $c->execute();
 $d = $con->prepare('SET character_set_results=utf8'); 
 $d->execute();

//DATA PARA ENCERRAMENTO DA INSCRIÇÃO
//DIAS A MAIS PARA EMISSÃO DE CERTIFICADO
$DIAS = 5;
 function sanitizeString($str) {
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    return $str;
}
function PET($str)
{
	switch($str)
	{
		case 1:	return 'PET POTÊNCIA UFPI';
		case 2:	return 'PET SERVIÇO SOCIAL UFPI';
		case 3: return 'PET PEDAGOGIA UFPI';
		case 4: return 'PET TURISMO UFPI';
		case 5: return 'PET CONEXÃO DE SABERES UFPI';
		case 6: return 'PET BOM JESUS UFPI';
		case 7: return 'PET INTEGRAÇÃO UFPI';
		case 8: return 'PET HISTÓRIA UFPI';
		case 9: return 'PET FILOSOFIA UFPI';
		case 10: return 'PET FÍSICA UESPI';
		
	}
}
function limpar_string($str){

    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
	$str = preg_replace('/[^a-zA-Z0-9@.\s]/', '', $str);
	return $str;
}

function limpar_numeros($str){
	$str = preg_replace('/[^0-9\s]/', '', $str);
	return $str;
}
 function retirar_letras_e_pontos($str) {
    //$str = preg_replace('/[a-zA-Z]/', '', $str);
    $str = preg_replace('/[.-]/i', '', $str); // ideia do Bacco :)
    return $str;
}
function validaCPF($cpf = null) {
 
    // Verifica se um número foi informado
    if(empty($cpf)) {
        return false;
    }
 
    // Elimina possivel mascara
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
     
    // Verifica se o numero de digitos informados é igual a 11 
    if (strlen($cpf) != 11) {
        return false;
    }
    // Verifica se nenhuma das sequências invalidas abaixo 
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' || 
        $cpf == '11111111111' || 
        $cpf == '22222222222' || 
        $cpf == '33333333333' || 
        $cpf == '44444444444' || 
        $cpf == '55555555555' || 
        $cpf == '66666666666' || 
        $cpf == '77777777777' || 
        $cpf == '88888888888' || 
        $cpf == '99999999999') {
        return false;
     // Calcula os digitos verificadores para verificar se o
     // CPF é válido
     } else {   
         
        for ($t = 9; $t < 11; $t++) {
             
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}
 ?>