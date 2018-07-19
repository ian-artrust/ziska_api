<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../bin/koneksi.php";

    $prov = $_GET['prov'];

    $sql	= "SELECT id_kab, kab_kota FROM tm_kab WHERE id_prov='$prov'";

    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}