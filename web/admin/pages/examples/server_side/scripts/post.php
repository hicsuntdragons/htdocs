<?php

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
$table = 'trabalhos_cientificos';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'titulo', 'dt' => 'titulo' ),
    array( 'db' => 'pet',  'dt' => 'pet' ),
    array( 'db' => 'estado',   'dt' => 'estado' ),
    array( 'db' => 'comentario',   'dt' => 'comentario' ),
    array(
        'db'        => 'data_envio',
        'dt'        => 'data_envio',
        'formatter' => function( $d, $row ) {
            return date_format(date_create($d),"d/m/Y");
        }
    ),
    array(
        'db'        => 'link',
        'dt'        => 'link',
        'formatter' => function( $d, $row ) {
			$msg .= '
				  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				  ';
				  
				$itens = json_decode($d);

				if ($itens !== false) {

					foreach ($itens as $item) {
						$urlArray = explode('_', $item);
						$cpf = $urlArray[1];
						$msg .= '<a class="dropdown-item" href="SubmissaoTrabalhos/'.$cpf.'/'.$item.'" target="_blank"><i class="fa fa-file-pdf-o"></i> '.$urlArray[0].'</a> <br />';
					}
				}else{
				
					$msg .= '<a class="dropdown-item" href="#">Problema: nenhum trabalho localizado. Falar com ADMIN.</a>';
				
				}
			$msg .= '
				  </div>
				</div>';
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
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);

