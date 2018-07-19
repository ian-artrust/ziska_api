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
	function autoJurnal($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_jurnal FROM trs_juhdr WHERE kode_daerah='$kode_daerah' ORDER BY no_jurnal DESC";
        
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
    $kode_daerah        = $_SESSION['kode_daerah'];

    $no_jurnal          = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $kode_akun_debit 	= strip_tags($_POST['kode_akun_debit']);

    $akun_kas_debit    = $_POST['akun_kas_debit'];

    $saldo_debit 		= strip_tags($_POST['saldo_debit']);

    $kode_akun_kredit 	= strip_tags($_POST['kode_akun_kredit']);

    $akun_kas_kredit   = $_POST['akun_kas_kredit'];
    
    $saldo_kredit		= strip_tags($_POST['saldo_kredit']);

    $jml_mutasi	        = strip_tags($_POST['jml_mutasi']);
    
    $keterangan         = $_POST['keterangan'];
    
    $periode 	        = strip_tags($_POST['periode']);
    
    $tgl_transaksi	    = strip_tags($_POST['tgl_transaksi']);
    
    $created            = date('Y-m-d');

    $createdby          = $_SESSION['kode_petugas'];

    /* Validasi Kode */
    $sqlCekKas = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
    $exe_sqlKas = $konek->query($sqlCekKas);
    
    $cekKas	= mysqli_num_rows($exe_sqlKas);
    
    if($cekKas > 0 ){
        
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        
    }elseif($kode_akun_debit == $kode_akun_kredit){

        $pesan 		= "Mutasi Kas Tidak Boleh Sama";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($jml_mutasi > $saldo_debit){

        $pesan 		= "Jumlah Mutasi Tidak Boleh Melebihi Saldo Debit";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }else{
        
        /* SQL Query Simpan */
        $sqlJuHdr = "INSERT INTO 
            trs_juhdr(
                no_jurnal,
                periode,
                tgl_jurnal,
                keterangan,
                status,
                jenis,
                kode_daerah,
                created,
                createdby
            )VALUES(
                '$no_jurnal',
                '$periode',
                '$tgl_transaksi',
                '$keterangan',
                'Trial',
                'JU',
                '$kode_daerah',
                '$created',
                '$createdby'
            )";
        
        $sqlJuDebit = "INSERT INTO 
            trs_judtl(
                no_jurnal,
                kode_akun,
                debit,
                kredit,
                periode,
                tgl_jurnal,
                kode_daerah,
                keterangan,
                status
            )VALUES(
                '$no_jurnal',
                '$kode_akun_debit',
                '$jml_mutasi',
                '0',
                '$periode',
                '$tgl_transaksi',
                '$kode_daerah',
                '$keterangan',
                'Trial'
            )";
        
        $sqlJuKredit = "INSERT INTO 
            trs_judtl(
                no_jurnal,
                kode_akun,
                debit,
                kredit,
                periode,
                tgl_jurnal,
                kode_daerah,
                keterangan,
                status
            )VALUES(
                '$no_jurnal',
                '$kode_akun_kredit',
                '0',
                '$jml_mutasi',
                '$periode',
                '$tgl_transaksi',
                '$kode_daerah',
                '$keterangan',
                'Trial'
            )";
        
        if($kode_akun_debit=='' OR $kode_akun_kredit==''){
            
            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'kode_akun_debit'=>$kode_akun_debit);
    
            echo json_encode($response);
        
        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                
                $insertJuHeader    = $konek->query($sqlJuHdr);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'kode_akun_debit'=>$kode_akun_debit);

            echo json_encode($response);

        }

    }

}