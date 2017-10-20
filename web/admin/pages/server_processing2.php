<?php
require_once 'usuario.php';
require_once 'sessao.php';
require_once 'autenticador.php';
//include_once '../../inc/conexao.php';
include_once '../../inc/config.php';
$aut = Autenticador::instanciar();

$usuario = null;
if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
	$area = '';
}
else {
    $aut->expulsar();
}


/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'inscricoes';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id', 'dt' => 'id1' ),
    array( 'db' => 'nome', 'dt' => 'nome' ),
    array( 'db' => 'pet', 'dt' => 'pet' ),
    array(
        'db'        => 'alojamento',
        'dt'        => 'preco',
        'formatter' => function( $d, $row ) {
		
			switch($d)
			{
				case 0:
				//	Comprovante não enviado
					return 'contate o ADM';
				break;
				case 1:
				//Com alojamento
					return 'Com Aloj.';
				break;
				case 2:
				//Sem alojamento
					return 'Sem Aloj.';
				break;
			
			}
        }
    ),
    array(
        'db'        => 'lote',
        'dt'        => 'lote',
        'formatter' => function( $d, $row ) {
			return ($d + 1);
        }
    ),
    array( 'db' => 'estado',   'dt' => 'estado' ),
    array(
        'db'        => 'pago',
        'dt'        => 'aprovado',
        'formatter' => function( $d, $row ) {
		
			switch($d)
			{
				case 0:
				//	Comprovante não enviado
					return 'Não enviado';
				break;
				case 1:
				//Analisando comprovante
					return 'Não analisado';
				break;
				case 2:
				//aprovado
					return 'Aprovado';
				break;
			
			}
        }
    ),
    array(
        'db'        => 'cpf',
        'dt'        => 'link',
        'formatter' => function( $d, $row ) {
		
		
			$msg = '';
			$msg .= '<div class="dropdown show">
				  ';
				  
				$itens = glob('ComprovantesPagamento/'.$d.'/{*.JPG,*.jpg,*.JPEG,*.jpeg,*.PNG,*.png,*.PDF,*.pdf}', GLOB_BRACE);

				if ($itens !== false) {

					foreach ($itens as $item) {
						$urlArray = explode('/', $item);
						$msg .= '<a class="dropdown-item" href="ComprovantesPagamento/'.$d.'/'.end($urlArray).'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.end($urlArray).'</a> <br />';
					}
				}else{
				
					$msg .= '<a class="dropdown-item" href="#">Nenhum comprovante enviado. Falar com ADMIN.</a>';
				
				}
			$msg .= '
				</div>';
            return $msg;
        }
    ),
	
	
    array(
        'db'        => 'cpf',
        'dt'        => 'id',
        'formatter' => function( $d, $row ) {
		
		
			$msg = '<a href="#avaliacao" class="btn btn-xl" onclick="avaliar(\''.$d.'\')" data-toggle="modal"><i class="fa fa-edit"></i> Avaliar cpf #'.$d.'</a>';
            return $msg;
        }
    )
);

// SQL server connection information
$sql_details = array(
    'user' => 'u426573602_root',
    'pass' => '@p0sitiv02014',
    'db'   => 'u426573602_ene',
    'host' => 'u426573602-ene.mysql.uhserver.com'
    // 'user' => 'root',
    // 'pass' => '',
    // 'db'   => 'enepet2017',
    // 'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $area)
);