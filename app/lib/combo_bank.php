<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {

        $sql	= "SELECT kode_akun, no_rekening, nama_bank FROM tm_bank WHERE status='Aktif'";
        
    } else {

        $sql	= "SELECT 
        
                    kode_akun, 
                    
                    no_rekening, 
                    
                    nama_bank 
                
                FROM tm_bank 
                
                WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }
   
    $hasil	= $konek->query($sql);

    $list   = array();

    while($row 	= $hasil->fetch_assoc()){

        array_push($list, $row);
    }

    echo json_encode($list);

}