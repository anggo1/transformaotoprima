<?php
$hostname_conn="192.168.0.1";
$username_conn="sa";
$password_conn="sjml2003";
$db_conn="pembelian_baru";
$ok=mssql_connect($hostname_conn,$username_conn,$password_conn,$db_conn) or die ("Sorry, there's a problem with our database.");

if($ok) {
    echo "GOKIL";
}
?>