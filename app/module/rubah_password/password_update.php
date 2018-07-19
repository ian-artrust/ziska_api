<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_petugas	= $_POST['kode_petugas'];

    $nama_petugas	= $_POST['nama_petugas'];

    $username	    = $_POST['username'];
    
    $password 		= $_POST['new_password'];

    $password_encode = base64_encode($password);
    
    $api_key 		= sha1($password);

    /* SQL Query Update */
    $sqlPetugas = "UPDATE tm_petugas SET

            nama_petugas='$nama_petugas',

            username='$username',
    
            password='$password_encode'
    
        WHERE kode_petugas='$kode_petugas' ";

    $sqlUser = "UPDATE sys_users SET

            username='$username',
    
            password='$password_encode'
    
        WHERE kode_petugas='$kode_petugas' ";

    if($kode_petugas!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $updatePetugas = $konek->query($sqlPetugas);
        
            $updatePetugas = $konek->query($sqlUser);

        $konek->commit();        

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}