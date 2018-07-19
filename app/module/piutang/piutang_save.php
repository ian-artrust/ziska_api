<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_piutang FROM trs_piutang WHERE kode_daerah='$kode_daerah' ORDER BY no_piutang DESC";
        
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
    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_piutang     = autonum(6,date('y')."104".$kode_daerah, $kode_daerah);

    $no_jurnal	    = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $kode_kreditur  = $_POST['kode_kreditur'];
    
    $nama_kreditur  = strip_tags($_POST['nama_kreditur']);
    
    $periode 	    = strip_tags($_POST['periode']);
    
    $tgl_piutang	= strip_tags($_POST['tgl_piutang']);

    $sumber_dana 	= strip_tags($_POST['sumber_dana']);
    
    $jml_piutang	= strip_tags($_POST['jml_piutang']);

    $kode_akun_debit = strip_tags($_POST['kode_akun_debit']);

    $akun_debit      = $_POST['akun_debit'];

    $kode_akun_kredit = strip_tags($_POST['kode_akun_kredit']);

    $akun_kredit     = $_POST['akun_kredit'];

    $no_rekening 	    = strip_tags($_POST['no_rekening']);

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $keterangan     = "Piutang ".$kode_kreditur." | ".$nama_kreditur; 

     /* Validasi Kode */
     $sqlCekKas = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
     $exe_sqlKas = $konek->query($sqlCekKas);
     
     $cekKas	= mysqli_num_rows($exe_sqlKas);
     
     if($cekKas > 0 ){
         
         $pesan 		= "Data Sudah Terdaftar";
     
         $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
     
         echo json_encode($response);
 
     }elseif($kode_daerah==''){
 
         $pesan 		= "Hanya Account Entitas Daerah Yang Bisa melakukan Transaksi";
     
         $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
     
         echo json_encode($response);
         
     }else{
         
         /* SQL Query Simpan */
        $sqlTrsPiutang = "INSERT INTO 
            trs_piutang(
                no_piutang,
                kode_kreditur,
                periode,
                tgl_piutang,
                jml_piutang,
                sumber_dana,
                kode_akun,
                kode_daerah,
                status,
                ref_number
            )VALUES(
                '$no_piutang',
                '$kode_kreditur',
                '$periode',
                '$tgl_piutang',
                '$jml_piutang',
                '$sumber_dana',
                '$kode_akun_kredit',
                '$kode_daerah',
                'Aktif',
                '$no_jurnal'
            )";

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
                 createdby,
                 ref_number
             )VALUES(
                 '$no_jurnal',
                 '$periode',
                 '$tgl_piutang',
                 '$keterangan',
                 'Trial',
                 'JU',
                 '$kode_daerah',
                 '$created',
                 '$createdby',
                 '$no_piutang'
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
                 status,
                 no_rekening
             )VALUES(
                 '$no_jurnal',
                 '$kode_akun_debit',
                 '$jml_piutang',
                 '0',
                 '$periode',
                 '$tgl_piutang',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial',
                 '$no_rekening'
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
                 status,
                 no_rekening
             )VALUES(
                 '$no_jurnal',
                 '$kode_akun_kredit',
                 '0',
                 '$jml_piutang',
                 '$periode',
                 '$tgl_piutang',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial',
                 '$no_rekening'
             )";
         
         if($kode_akun_debit=='' OR $kode_akun_kredit=='' OR $jml_piutang==''){
             
             $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
     
             $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);
     
             echo json_encode($response);
         
         }elseif($jml_piutang < 0){
 
             $pesan 		= "Saldo Tidak Boleh Minus";
             
             $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);
     
             echo json_encode($response);
 
         }else{
 
             /** Menggunakan Transaction Mysql */
             $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    
                $insertPiutang     = $konek->query($sqlTrsPiutang); 
                    
                $insertJuHeader    = $konek->query($sqlJuHdr);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);     
                 
             $konek->commit();  
             
             $pesan 		= "Data Berhasil Disimpan";
 
             $response 	= array('pesan'=>$pesan, 'no_jurnal'=>$no_jurnal);
 
             echo json_encode($response);
 
         }
 
     }
}