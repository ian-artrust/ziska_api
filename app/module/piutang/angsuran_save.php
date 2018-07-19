<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_angsuran FROM trs_ang_piutang WHERE kode_daerah='$kode_daerah' ORDER BY no_angsuran DESC";
        
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
    $kode_daerah            = $_SESSION['kode_daerah'];

    $no_angsuran            = autonum(6,date('y')."122".$kode_daerah, $kode_daerah);

    $no_jurnal	            = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $no_piutang_ang         = $_POST['no_piutang_ang'];

    $nama_kreditur_ang      = $_POST['nama_kreditur_ang'];
    
    $jml_angsuran           = strip_tags($_POST['jml_angsuran']);
    
    $periode_angsuran 	    = strip_tags($_POST['periode_angsuran']);
    
    $tgl_angsuran	        = strip_tags($_POST['tgl_angsuran']);

    $kode_debit_angsuran 	= strip_tags($_POST['kode_debit_angsuran']);
    
    $akun_debit_angsuran	= strip_tags($_POST['akun_debit_angsuran']);

    $kode_kredit_angsuran   = strip_tags($_POST['kode_kredit_angsuran']);

    $akun_kredit_angsuran   = $_POST['akun_kredit_angsuran'];

    $no_rekening            = strip_tags($_POST['no_rekening']);

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $keterangan     = "Angsuran ".$no_piutang_ang." | ".$nama_kreditur_ang; 

     /* Validasi Kode */
     $sqlCekAngsuran = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
     $exe_sqlAngsuran = $konek->query($sqlCekAngsuran);
     
     $cekAngsuran	= mysqli_num_rows($exe_sqlAngsuran);
     
     if($cekAngsuran > 0 ){
         
         $pesan 		= "Data Sudah Terdaftar";
     
         $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
     
         echo json_encode($response);
 
     }elseif($kode_daerah==''){
 
         $pesan 	= "Hanya Account Entitas Daerah Yang Bisa melakukan Transaksi";
     
         $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
     
         echo json_encode($response);
         
     }else{
         
         /* SQL Query Simpan */
        $sqlTrsAngsuran = "INSERT INTO 
            trs_ang_piutang(
                no_angsuran,
                no_piutang,
                periode,
                tgl_angsuran,
                jml_angsuran,
                kode_daerah,
                status,
                ref_number
            )VALUES(
                '$no_angsuran',
                '$no_piutang_ang',
                '$periode_angsuran',
                '$tgl_angsuran',
                '$jml_angsuran',
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
                 '$periode_angsuran',
                 '$tgl_angsuran',
                 '$keterangan',
                 'Trial',
                 'JU',
                 '$kode_daerah',
                 '$created',
                 '$createdby',
                 '$no_angsuran'
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
                 '$kode_debit_angsuran',
                 '$jml_angsuran',
                 '0',
                 '$periode_angsuran',
                 '$tgl_angsuran',
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
                 '$kode_kredit_angsuran',
                 '0',
                 '$jml_angsuran',
                 '$periode_angsuran',
                 '$tgl_angsuran',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial',
                 '$no_rekening'
             )";
         
         if($kode_debit_angsuran=='' OR $kode_kredit_angsuran=='' OR $jml_angsuran==''){
             
             $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
     
             $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);
     
             echo json_encode($response);
 
         }else{
 
             /** Menggunakan Transaction Mysql */
             $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    
                $insertAngsuran     = $konek->query($sqlTrsAngsuran); 
                    
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