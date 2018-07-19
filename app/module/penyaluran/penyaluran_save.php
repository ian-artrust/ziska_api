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
    $kode_daerah    = $_SESSION['kode_daerah'];

    $no_jurnal	    = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $no_pengajuan   = $_POST['no_pengajuan'];

    $periode 	    = strip_tags($_POST['periode']);

    $tgl_penyaluran	= strip_tags($_POST['tgl_penyaluran']);
    
    $angka          = strip_tags($_POST['jml_realisasi']);

    $keterangan     = strip_tags($_POST['keterangan']); 
    
    $kode_akun_debit 	= strip_tags($_POST['kode_akun_debit']);
    
    $kode_akun_kredit	= strip_tags($_POST['kode_akun_kredit']);

    $no_rekening 	    = strip_tags($_POST['no_rekening']);

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $pesan          = "Penyaluran ".$no_pengajuan." | ".$keterangan; 

    $jml_realisasi  = str_replace(',','',$angka);    

     /* Validasi Kode */
     $sqlCekAngsuran = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
     $exe_sqlAngsuran = $konek->query($sqlCekAngsuran);
     
     $cekAngsuran	= mysqli_num_rows($exe_sqlAngsuran);
     
     if($cekAngsuran > 0 ){
         
         $pesan 		= "Data Sudah Terdaftar";
     
         $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
     
         echo json_encode($response);
 
     }elseif($kode_daerah==''){
 
         $pesan 	= "Hanya Account Entitas Daerah Yang Dapat Melakukan Transaksi";
     
         $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
     
         echo json_encode($response);
         
     }else{
         
         /* SQL Query Simpan */
        $sqlCompletePengajuan = "UPDATE 
        
                                    trs_pengajuan 
                                    
                                    SET status='COMPLETE' 
                                    
                                    WHERE no_pengajuan='$no_pengajuan'";

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
                 '$tgl_penyaluran',
                 '$pesan',
                 'Trial',
                 'JU',
                 '$kode_daerah',
                 '$created',
                 '$createdby',
                 '$no_pengajuan'
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
                 '$jml_realisasi',
                 '0',
                 '$periode',
                 '$tgl_penyaluran',
                 '$kode_daerah',
                 '$pesan',
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
                 '$jml_realisasi',
                 '$periode',
                 '$tgl_penyaluran',
                 '$kode_daerah',
                 '$pesan',
                 'Trial',
                 '$no_rekening'
             )";
         
         if($kode_akun_debit=='' OR $kode_akun_kredit=='' OR $tgl_penyaluran==''){
             
             $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
     
             $response 	= array('pesan'=>$pesan, 'no_jurnal'=>$no_jurnal);
     
             echo json_encode($response);
        
 
         }else{
 
             /** Menggunakan Transaction Mysql */
             $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    
                $insertPengajuan   = $konek->query($sqlCompletePengajuan); 
                    
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