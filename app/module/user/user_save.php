<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /** Variabel From Post */
    $kode_daerah    = strip_tags($_POST['kode_daerah']);

     /*
	* Auto Number Untuk Code Buku 
	*/
	function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT kode_petugas FROM tm_petugas WHERE kode_daerah='$kode_daerah' ORDER BY kode_petugas DESC";
        
        $hasil= $konek->query($sqlcount);
        
        $jumlahrecord = mysqli_num_rows($hasil);

		if($jumlahrecord == 0)
			$nomor=1;
		else {
			$nomor = $jumlahrecord+1;
		}

		if($lebar>0)
			$angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
		else
			$angka = $awalan.$nomor;
		return $angka;
    }

    $kode_petugas	= autonum(4,$kode_daerah."55", $kode_daerah);

    $nama_petugas 	= strip_tags($_POST['nama_petugas']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $level 			= strip_tags($_POST['level']);

    $email 			= strip_tags($_POST['email']);
    
    $username 		= strip_tags($_POST['username']);
    
    $password 		= base64_encode($_POST['password']);
    
    $api_key 		= sha1($_POST['password']);
  
    $status 		= strip_tags($_POST['status']);
    
    /* Validasi Kode */
    $sqlCekPetugas = "SELECT kode_petugas FROM sys_users WHERE kode_petugas='$kode_petugas'";
    
    $sqlCekUser = "SELECT username FROM sys_users WHERE username='$username'";
    
    $exe_sqlPetugas = $konek->query($sqlCekPetugas);
    
    $exe_sqlUser = $konek->query($sqlCekUser);
    
    $cekPetugas	= mysqli_num_rows($exe_sqlPetugas);
    
    $cekUser =  mysqli_num_rows($exe_sqlUser);

    /* SQL Query Simpan */
    $sqlPetugas = "INSERT INTO 
        tm_petugas (
            kode_petugas,
            nama_petugas,
            alamat,
            no_hp,
            email,
            username,
            password,
            level,
            kode_daerah,
            status
        )VALUES(
            '$kode_petugas',
            '$nama_petugas',
            '$alamat',
            '$no_hp',
            '$email',
            '$username',
            '$password',
            '$level',
            '$kode_daerah',
            '$status'
        )";

    $sqlUser = "INSERT INTO 
        sys_users (
            username,
            password,
            level,
            active,
            kode_petugas,
            api_key,
            kode_daerah
        )VALUES(
            '$username',
            '$password',
            '$level',
            'Yes',
            '$kode_petugas',
            '$api_key',
            '$kode_daerah'
        )";
    
    if($cekPetugas > 0 AND $cekUser > 0 ){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else if($email=='' || $username=='' || $password=='' || $kode_daerah=='' ){
    
        $pesan 		= "Data Tidak Boleh Kosong";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $insertPetugas = $konek->query($sqlPetugas);
        
            $insertUser = $konek->query($sqlUser);

        $konek->commit();        

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}