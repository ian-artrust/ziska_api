<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_petugas	= $_POST['kode_petugas'];

    $nama_petugas 	= strip_tags($_POST['nama_petugas']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $level 			= strip_tags($_POST['level']);

    $email 			= strip_tags($_POST['email']);
    
    $username 		= strip_tags($_POST['username']);
    
    $password 		= $_POST['password'];

    $password_decode = base64_decode($password);

    $password_encode = base64_encode($password_decode);
    
    $api_key 		= sha1($_POST['password']);
    
    $kode_daerah 	= strip_tags($_POST['kode_daerah']);
    
    $status 		= strip_tags($_POST['status']);

    if($status='Aktif'){

        $set_active = 'Yes';
    
    } else {

        $set_active = 'No';

    }

    /* SQL Query Update */
    $sqlPetugas = "UPDATE tm_petugas SET

            nama_petugas='$nama_petugas',
    
            alamat='$alamat',
    
            no_hp='$no_hp',

            email='$email',
    
            username='$username',
    
            password='$password_encode',
    
            level='$level',
    
            kode_daerah='$kode_daerah',
    
            status='$status'
    
        WHERE kode_petugas='$kode_petugas' ";

    $sqlUser = "UPDATE sys_users SET
    
            username = '$username',
    
            password='$password_encode',
    
            level='$level',
    
            kode_daerah='$kode_daerah',
    
            active='$set_active'
    
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