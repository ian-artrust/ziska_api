<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    
    include "../../../bin/koneksi.php";

    $kode_daerah =  $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
        
        $sql	= "SELECT no_asset, asset, kategori, nilai_asset, umur_ekonomis, nilai_penyusutan FROM view_326 WHERE status='Aktif'";

    } else {
        
        $sql	= "SELECT no_asset, asset, kategori, nilai_asset, umur_ekonomis, nilai_penyusutan FROM view_326 WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_asset'] = $row['no_asset'];

        $r['asset'] = $row['asset'];

        $r['kategori'] = $row['kategori'];

        $r['nilai_asset'] = number_format($row['nilai_asset'], 0, ',', '.');

        $r['umur_ekonomis'] = $row['umur_ekonomis'];

        $r['nilai_penyusutan'] = number_format($row['nilai_penyusutan'], 0, ',', '.');

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}