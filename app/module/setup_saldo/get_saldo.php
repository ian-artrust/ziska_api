<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    
    include "../../../bin/koneksi.php";

    $kode_daerah =  $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
        
        $sql	= "SELECT 

                    ref_number,
                    
                    kode_akun, 
                    
                    akun, 

                    periode,

                    tgl_setup,
                    
                    jml_setup,

                    no_rekening  
                    
                FROM view_321c WHERE status='Aktif'";

    } else {
        
        $sql	= "SELECT 

                    ref_number,
        
                    kode_akun, 
                    
                    akun,

                    periode,

                    tgl_setup, 
                    
                    jml_setup,

                    no_rekening 
                    
                FROM view_321c 
                
                WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['ref_number'] = $row['ref_number'];

        $r['kode_akun'] = $row['kode_akun'];

        $r['periode'] = $row['periode'];

        $r['tgl_setup'] = $row['tgl_setup'];

        $r['akun'] = $row['akun'];

        $r['jml_setup'] =  number_format($row['jml_setup'], 0, ',', '.');

        $r['no_rekening'] = $row['no_rekening'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}