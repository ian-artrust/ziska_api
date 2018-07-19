<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /*
	* Auto Number Untuk Code Buku 
	*/
	function autonum($lebar=0, $awalan=''){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT kode_kat_asset FROM tm_kat_asset ORDER BY kode_kat_asset DESC";
        
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
    $kode_kat_asset	= autonum(2,"44");

    $kategori 	    = strip_tags($_POST['kategori']);
    
    /* Validasi Kode */
    $sqlCekKatAsset = "SELECT kode_kat_asset FROM tm_kat_asset WHERE kode_kat_asset='$kode_kat_asset'";
    
    $exe_sqlKatAsset = $konek->query($sqlCekKatAsset);
    
    $cekKatAsset	= mysqli_num_rows($exe_sqlKatAsset);

    /* SQL Query Simpan */
    $sqlKatAsset = "INSERT INTO 
        tm_kat_asset (
            kode_kat_asset,
            kategori,
            status
        )VALUES(
            '$kode_kat_asset',
            '$kategori',
            'Aktif'
        )";
    
    if($cekKatAsset > 0 ){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $insertKatAsset = $konek->query($sqlKatAsset);

        $konek->commit();        

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}