<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    function autonum($lebar=0, $awalan='', $kode_daerah, $kode_kategori){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT 
        
                    kode_prg_pnr 
                    
                    FROM tm_prg_pnr 
                    
                    WHERE kode_daerah='$kode_daerah' AND kode_kategori='$kode_kategori' 
                    
                    ORDER BY kode_prg_pnr DESC";
        
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
    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_kategori  = strip_tags($_POST['kode_kategori']);

    $kode_prg_pnr 	= autonum("3",$kode_kategori."-".$kode_daerah, $kode_daerah, $kode_kategori);

    $program        = strip_tags($_POST['program']);
    
    $status 	    = strip_tags($_POST['status']);
    
    $akun_debit     = strip_tags($_POST['akun_debit']);

    $akun_kredit    = strip_tags($_POST['akun_kredit']);

    $akun_debit_bank     = strip_tags($_POST['akun_debit_bank']);

    $akun_kredit_bank    = strip_tags($_POST['akun_kredit_bank']);    
    
    /* Validasi Kode */
    $sqlCekNoProgram    = "SELECT kode_prg_pnr FROM tm_prg_pnr WHERE kode_prg_pnr='$kode_prg_pnr'";
    
    $exe_sqlNoProgram   = $konek->query($sqlCekNoProgram);
   
    $cekNoProgram	    = mysqli_num_rows($exe_sqlNoProgram);

    /* SQL Query Simpan */
    $sqlProgram = "INSERT INTO 
        tm_prg_pnr(
            kode_prg_pnr,
            program,
            kode_kategori,
            akun_debit,
            akun_kredit,
            akun_debit_bank,
            akun_kredit_bank,
            kode_daerah,
            status
        )VALUES(
            '$kode_prg_pnr',
            '$program',
            '$kode_kategori',
            '$akun_debit',
            '$akun_kredit',
            '$akun_debit_bank',
            '$akun_kredit_bank',
            '$kode_daerah',
            '$status'
        )";
    
    if($cekNoProgram> 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif ($kode_daerah=='') {

        $pesan 		= "Hanya Account Yang Meiliki Entitas Daerah Yang Bisa Melakukan Transaksi...!";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {

        $insertProgram = $konek->query($sqlProgram);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}