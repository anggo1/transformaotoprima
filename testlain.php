<?php

$serverName = "192.168.0.1";
$connectionInfo = array( "Database"=>"pembelian_baru", "UID"=>"sa", "PWD"=>"sjml2003");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
?>