<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    /*
	* Auto Number Untuk Code Buku 
	*/
	function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT kode_kreditur FROM tm_kreditur WHERE kode_daerah='$kode_daerah' ORDER BY kode_kreditur DESC";
        
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
    
    /** Variabel From Post */
    $kode_kreditur	= autonum(4,"14".$kode_daerah, $kode_daerah);

    $nama_kreditur 	= strip_tags($_POST['nama_kreditur']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    /* Validasi Kode */
    $sqlCekKreditur = "SELECT kode_kreditur FROM tm_kreditur WHERE kode_kreditur='$kode_kreditur'";
    
    $exe_sqlKreditur = $konek->query($sqlCekKreditur);
    
    $cekKreditur	= mysqli_num_rows($exe_sqlKreditur);

    /* SQL Query Simpan */
    $sqlKreditur = "INSERT INTO 
        tm_kreditur (
            kode_kreditur,
            nama_kreditur,
            alamat,
            no_hp,
            kode_daerah
        )VALUES(
            '$kode_kreditur',
            '$nama_kreditur',
            '$alamat',
            '$no_hp',
            '$kode_daerah'
        )";
    
    if($cekKreditur > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($kode_daerah==''){

        $pesan 		= "Hanya Account Yang Memiliki Entitas Daerah";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }else {

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $insertKreditur = $konek->query($sqlKreditur);

        $konek->commit();        

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}