<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
        
        $sql	= "SELECT 
                    
                    kode_akun, 
                    
                    akun, 
                    
                    periode 
                    
                FROM view_321b 
                
                WHERE debit>0 
                
                AND keterangan = 'Setup Saldo Awal Periode'

                AND status='Trial'";

    } else {
        
        $sql	= "SELECT 
        
                    kode_akun, 
                    
                    akun, 
                    
                    periode 
                
                FROM view_321b 
                
                WHERE debit>0  

                AND keterangan = 'Setup Saldo Awal Periode'
                
                AND status='Trial' 
                
                AND kode_daerah='$kode_daerah'";

    }
    
    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_akun'] = $row['kode_akun'];

        $r['akun'] = $row['akun'];

        $r['periode'] = $row['periode'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}