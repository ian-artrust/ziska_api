<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $sql	= "SELECT kode_kat_asset, kategori FROM tm_kat_asset WHERE status !='REJECT'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_kat_asset'] = $row['kode_kat_asset'];

        $r['kategori'] = $row['kategori'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}