<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /** Variabel From Post */
    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_rekening 	= strip_tags($_POST['no_rekening']);
    
    $nama_bank	    = strip_tags($_POST['nama_bank']);
    
    $status 	    = strip_tags($_POST['status']);
    
    $no_kantor 		= strip_tags($_POST['no_kantor']);
    
    /* Validasi Kode */
    $sqlCekNoRekening   = "SELECT no_rekening FROM tm_bank WHERE no_rekening='$no_rekening'";
    
    $exe_sqlNoRekening  = $konek->query($sqlCekNoRekening);
   
    $cekNoRekening	    = mysqli_num_rows($exe_sqlNoRekening);

    /* SQL Query Simpan */
    $sqlBank = "INSERT INTO 
        tm_bank_kl(
            no_rekening,
            nama_bank,
            kode_daerah,
            kode_akun,
            status,
            no_kantor
        )VALUES(
            '$no_rekening',
            '$nama_bank',
            '$kode_daerah',
            '102001',
            '$status',
            '$no_kantor'
        )";
    
    if($cekNoRekening > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($kode_daerah=='') {
        
        $pesan 		= "Hanya Account Yang Memiliki Entitas Daerah Yang Bisa Melakukan Transaksi";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        $insertBank = $konek->query($sqlBank);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}