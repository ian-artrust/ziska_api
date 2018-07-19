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

    function autoJurnal2($lebar=0, $awalan='', $kode_daerah){
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

    $jml_alokasi    = intval($_POST['jml_alokasi']);

    $no_jurnal	    = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $periode	    = strip_tags($_POST['periode']);
    
    $tgl_alokasi	= strip_tags($_POST['tgl_alokasi']);

    $jenis 	        = strip_tags($_POST['jenis']);
    
    $kode_akun_debit = strip_tags($_POST['kode_akun_debit']);

    $akun_debit      = $_POST['akun_debit'];

    $kode_akun_kredit = strip_tags($_POST['kode_akun_kredit']);

    $akun_kredit    = $_POST['akun_kredit'];

    $kda_debit      = strip_tags($_POST['kda_debit']);

    $a_debit        = $_POST['a_debit'];

    $kda_kredit     = strip_tags($_POST['kda_kredit']);

    $a_kredit       = $_POST['a_kredit'];

    $no_rekening    = strip_tags($_POST['no_rekening']);

    $no_rekening_dua  = strip_tags($_POST['no_rekening_dua']);

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $keterangan     = $akun_debit." | ".$periode; 

    $keterangan2    = $a_kredit." | ".$periode;
    
    function jurnalDua($no_rekening_dua){

        include "../../../bin/koneksi.php";

        $kode_daerah    = $_SESSION['kode_daerah'];

        $no_jurnal2	= autoJurnal2(6,"JU".date('y').$kode_daerah, $kode_daerah);

        $jml_alokasi    = intval($_POST['jml_alokasi']);

        $periode	    = strip_tags($_POST['periode']);
    
        $tgl_alokasi	= strip_tags($_POST['tgl_alokasi']);
    
        $jenis 	        = strip_tags($_POST['jenis']);
    
        $kda_debit      = strip_tags($_POST['kda_debit']);
    
        $a_debit        = $_POST['a_debit'];
    
        $kda_kredit     = strip_tags($_POST['kda_kredit']);
    
        $a_kredit       = $_POST['a_kredit'];
    
        $created        = date('Y-m-d');
    
        $createdby      = $_SESSION['kode_petugas'];
    
        $keterangan2    = $a_kredit." | ".$periode;

        $sqlJuHdr2 = "INSERT INTO 
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
                    '$no_jurnal2',
                    '$periode',
                    '$tgl_alokasi',
                    '$keterangan2',
                    'Trial',
                    'JU',
                    '$kode_daerah',
                    '$created',
                    '$createdby'
                )";

        $sqlJuDebit2 = "INSERT INTO 
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
                    '$no_jurnal2',
                    '$kda_debit',
                    '$jml_alokasi',
                    '0',
                    '$periode',
                    '$tgl_alokasi',
                    '$kode_daerah',
                    '$keterangan2',
                    'Trial',
                    '$no_rekening_dua'
                )";

        $sqlJuKredit2 = "INSERT INTO 
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
                    '$no_jurnal2',
                    '$kda_kredit',
                    '0',
                    '$jml_alokasi',
                    '$periode',
                    '$tgl_alokasi',
                    '$kode_daerah',
                    '$keterangan2',
                    'Trial',
                    '$no_rekening_dua'
                )";
        
        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $insertJuHeader2    = $konek->query($sqlJuHdr2);
            
            $insertJuDebit2     = $konek->query($sqlJuDebit2);
            
            $insertJuKredit2    = $konek->query($sqlJuKredit2); 
            
        $konek->commit();
    }

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
                 '$tgl_alokasi',
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
                 status,
                 no_rekening
             )VALUES(
                 '$no_jurnal',
                 '$kode_akun_debit',
                 '$jml_alokasi',
                 '0',
                 '$periode',
                 '$tgl_alokasi',
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
                 '$jml_alokasi',
                 '$periode',
                 '$tgl_alokasi',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial',
                 '$no_rekening'
             )";
         
         if($kode_akun_debit=='' OR $kode_akun_kredit=='' OR $kda_debit=='' OR $kda_kredit=='' OR $jml_alokasi==''){
             
             $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
     
             $response 	= array('pesan'=>$pesan, 'no_asset'=>$no_asset);
     
             echo json_encode($response);
 
         }else{
 
             /** Menggunakan Transaction Mysql */
             $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    
                $insertJuHeader    = $konek->query($sqlJuHdr);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);  
                 
            $konek->commit();
            
            jurnalDua($no_rekening_dua);
             
             $pesan 		= "Data Berhasil Disimpan";
 
             $response 	= array('pesan'=>$pesan, 'no_jurnal'=>$no_jurnal);
 
             echo json_encode($response);
 
         }
 
     }
}