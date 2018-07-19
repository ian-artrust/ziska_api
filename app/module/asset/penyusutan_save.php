<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    function autonum($lebar=0, $awalan=''){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_penyusutan FROM trs_pny_asset ORDER BY no_penyusutan DESC";
        
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

    $no_penyusutan  = autonum(6,"600".date('y'));

    $no_jurnal	    = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $no_asset_pny   = $_POST['no_asset_pny'];
    
    $asset_pny 	    = strip_tags($_POST['asset_pny']);

    $kat_asset_pny	= strip_tags($_POST['kat_asset_pny']);
    
    $periode_pny	= strip_tags($_POST['periode_pny']);

    $tgl_penyusutan = strip_tags($_POST['tgl_penyusutan']);
    
    $nilai_pny	    = str_replace('.','',strip_tags($_POST['nilai_pny']));

    $akun_dua	    = strip_tags($_POST['akun_dua']);

    $akun_tiga 	    = strip_tags($_POST['akun_tiga']);
    
    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $keterangan     = "Beban Penyusutan ".$asset_pny." | ".$no_penyusutan; 

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
        $sqlTrsPenyusutan = "INSERT INTO 
            trs_pny_asset(
                no_penyusutan,
                no_asset,
                periode,
                tgl_penyusutan,
                nilai_penyusutan,
                status
            )VALUES(
                '$no_penyusutan',
                '$no_asset_pny',
                '$periode_pny',
                '$tgl_penyusutan',
                '$nilai_pny',
                'Aktif'
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
                 '$periode_pny',
                 '$tgl_penyusutan',
                 '$keterangan',
                 'Trial',
                 'JP',
                 '$kode_daerah',
                 '$created',
                 '$createdby',
                 '$no_penyusutan'
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
                 '$akun_dua',
                 '$nilai_pny',
                 '0',
                 '$periode_pny',
                 '$tgl_penyusutan',
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
                 '$akun_tiga',
                 '0',
                 '$nilai_pny',
                 '$periode_pny',
                 '$tgl_penyusutan',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial'
             )";
         
         if($akun_dua=='' OR $akun_tiga=='' OR $nilai_pny==''){
             
             $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
     
             $response 	= array('pesan'=>$pesan, 'no_asset'=>$no_asset);
     
             echo json_encode($response);
 
         }else{
 
             /** Menggunakan Transaction Mysql */
             $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    
                $insertPenyusutan     = $konek->query($sqlTrsPenyusutan); 
                    
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