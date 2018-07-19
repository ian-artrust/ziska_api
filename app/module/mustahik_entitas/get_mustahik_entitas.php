<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_kantor      = $_SESSION['no_kantor'];

    if($kode_daerah=='') {
        
        $sql	= "SELECT 
        
                    no_registrasi, 
                    
                    nama_lembaga, 
                    
                    alamat,

                    no_hp

                FROM tm_mustahik_entitas 
                
                WHERE status='Aktif'";

    } elseif($no_kantor=='') {
        
        $sql	= "SELECT 
        
                    no_registrasi, 
                    
                    nama_lembaga, 
                    
                    alamat,

                    no_hp

                FROM tm_mustahik_entitas 
    
                WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    } else {

        $sql	= "SELECT 
        
                    no_registrasi, 
                    
                    nama_lembaga, 
                    
                    alamat,

                    no_hp

                FROM tm_mustahik_entitas 
    
                WHERE no_kantor='$no_kantor' AND status='Aktif'";

    } 

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_registrasi'] = $row['no_registrasi'];

        $r['nama_lembaga'] = $row['nama_lembaga'];

        $r['alamat'] = $row['alamat'];

        $r['no_hp'] = $row['no_hp'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}