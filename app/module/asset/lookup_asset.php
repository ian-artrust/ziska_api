<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $sql	= "SELECT 
    
                kode_akun, 
                
                akun, 
                
                kategori 
                
                FROM view_321 
                
                WHERE jenis='Asset' AND status='Aktif'
                
                OR jenis='Asset Kelolaan' AND status='Aktif'";

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_akun'] = $row['kode_akun'];

        $r['akun'] = $row['akun'];

        $r['kategori'] = $row['kategori'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}