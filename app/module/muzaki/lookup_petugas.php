<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if($kode_daerah==''){

        $sql	= "SELECT kode_petugas, nama_petugas, no_hp FROM tm_petugas";

    } else {

        $sql	= "SELECT kode_petugas, nama_petugas, no_hp FROM tm_petugas WHERE kode_daerah='$kode_daerah'";       

    }

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_petugas'] = $row['kode_petugas'];

        $r['nama_petugas'] = $row['nama_petugas'];

        $r['no_hp'] = $row['no_hp'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}