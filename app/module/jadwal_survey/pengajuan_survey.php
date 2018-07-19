<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_pengajuan	= $_POST['no_pengajuan'];

    $tgl_survey     = $_POST['tgl_survey'];

    /* SQL Query Update */
    $sqlNoPengajuan= "UPDATE trs_pengajuan SET tgl_survey='$tgl_survey', status='PROSES SURVEY' WHERE no_pengajuan='$no_pengajuan' ";

    $sqlCekData = "SELECT * FROM trs_pengajuan WHERE no_pengajuan='$no_pengajuan' AND status='PROSES SURVEY'";

    $exe_sqlCekData = $konek->query($sqlCekData);
    
    $cekPengajuan	= mysqli_num_rows($exe_sqlCekData);

    if($tgl_survey=='' OR $no_pengajuan==''){

        $pesan 		= "Tanggal Survey dan No Pengajuan Harus Diisi";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        
    }elseif($cekPengajuan==0){

        $deletePengajuan = $konek->query($sqlNoPengajuan);    

        $pesan 		= "Jadwal Survey Sukses";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Sudah Masuk Proses Survey";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}