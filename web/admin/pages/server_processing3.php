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
$table = 'tarefas';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'funcao', 'dt' => 'funcao' ),
    array( 'db' => 'subfuncao',  'dt' => 'subfuncao' ),
    array( 'db' => 'nome', 'dt' => 'nome' ),
    array( 'db' => 'pet', 'dt' => 'pet' ),
    array( 'db' => 'wpp', 'dt' => 'wpp' )
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
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns)
);