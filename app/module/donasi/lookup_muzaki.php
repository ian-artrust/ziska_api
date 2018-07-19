<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $no_kantor = $_SESSION['no_kantor'];

    $kode_daerah = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
        
        $sql	= "SELECT npwz, nama_donatur, alamat FROM tm_donatur WHERE status='Aktif'";

    } else {
       
        $sql	= "SELECT npwz, nama_donatur, alamat FROM tm_donatur WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }    

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['alamat'] = $row['alamat'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}