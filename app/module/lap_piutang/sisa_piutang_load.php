<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_piutang_ang = $_GET['no_piutang_ang'];

    $kode_daerah = $_SESSION['kode_daerah'];

    $sqlFind 	= "SELECT 

                    jml_piutang,

                    SUM(jml_angsuran) AS total_angsuran,

                    jml_piutang - SUM(jml_angsuran) AS sisa_piutang

                FROM view_325b 

                WHERE no_piutang='$no_piutang_ang' 
                
                AND status='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}