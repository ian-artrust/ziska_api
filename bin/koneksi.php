<?php

$server 	= "localhost";
// $server 	= "185.201.9.247";
$user 		= "root";
$password 	= "dbadmin";
$db 		= "dbziska";

$konek = mysqli_connect($server,$user, $password,$db);
if($konek){
	//echo "Koneksi Database Sukses";
} else {
	die('Koneksi Gagal');
	mysql_error();
}
?>