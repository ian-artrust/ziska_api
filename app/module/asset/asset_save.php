<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_asset FROM tm_asset WHERE kode_daerah='$kode_daerah' ORDER BY no_asset DESC";
        
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

    $kode_kat_asset = strip_tags($_POST['kode_kat_asset']);

    $no_asset       = autonum(5,$kode_kat_asset."-".$kode_daerah, $kode_daerah);

    $no_jurnal	    = autoJurnal(6,"JU".date('y').$kode_daerah, $kode_daerah);

    $asset          = $_POST['asset'];
    
    $kat_asset 	    = strip_tags($_POST['kat_asset']);

    $periode	    = strip_tags($_POST['periode']);
    
    $tgl_perolehan	= strip_tags($_POST['tgl_perolehan']);

    $sumber_dana 	= strip_tags($_POST['sumber_dana']);
    
    $pos_dana	    = strip_tags($_POST['pos_dana']);

    $umur_ekonomis	= strip_tags($_POST['umur_ekonomis']);

    $satuan 	    = strip_tags($_POST['satuan']);
    
    $harga_perolehan = strip_tags($_POST['harga_perolehan']);

    $nilai_residu	    = strip_tags($_POST['nilai_residu']);

    // $nilai_asset	= strip_tags($_POST['nilai_asset']);

    $nilai_penyusutan 	    = strip_tags($_POST['nilai_penyusutan']);
    
    $kode_akun_asset = strip_tags($_POST['kode_akun_asset']);

    $akun_asset = strip_tags($_POST['akun_asset']);

    $kode_akun_debit = strip_tags($_POST['kode_akun_debit']);

    $akun_debit      = $_POST['akun_debit'];

    $kode_akun_kredit = strip_tags($_POST['kode_akun_kredit']);

    $akun_kredit     = $_POST['akun_kredit'];

    $no_rekening	= strip_tags($_POST['no_rekening']);

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $keterangan     = "Pengadaan ".$kat_asset." | ".$no_asset; 

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
        $sqlTrsAsset = "INSERT INTO 
            tm_asset(
                no_asset,
                asset,
                kode_kat_asset,
                periode,
                tgl_perolehan,
                sumber_dana,
                umur_ekonomis,
                satuan,
                akun_satu,
                akun_dua,
                akun_tiga,
                nilai_asset,
                nilai_residu,
                nilai_penyusutan,
                kode_daerah,
                status
            )VALUES(
                '$no_asset',
                '$asset',
                '$kode_kat_asset',
                '$periode',
                '$tgl_perolehan',
                '$sumber_dana',
                '$umur_ekonomis',
                '$satuan',
                '$kode_akun_asset',
                '$kode_akun_debit',
                '$kode_akun_kredit',
                '$harga_perolehan',
                '$nilai_residu',
                '$nilai_penyusutan',
                '$kode_daerah',
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
                 '$periode',
                 '$tgl_perolehan',
                 '$keterangan',
                 'Trial',
                 'JU',
                 '$kode_daerah',
                 '$created',
                 '$createdby',
                 '$no_asset'
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
                 '$kode_akun_asset',
                 '$harga_perolehan',
                 '0',
                 '$periode',
                 '$tgl_perolehan',
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
                 '$pos_dana',
                 '0',
                 '$harga_perolehan',
                 '$periode',
                 '$tgl_perolehan',
                 '$kode_daerah',
                 '$keterangan',
                 'Trial',
                 '$no_rekening'
             )";
         
         if($kode_akun_debit=='' OR $kode_akun_kredit=='' OR $harga_perolehan==''){
             
             $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
     
             $response 	= array('pesan'=>$pesan, 'no_asset'=>$no_asset);
     
             echo json_encode($response);
 
         }else{
 
             /** Menggunakan Transaction Mysql */
             $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                    
                $insertAsset     = $konek->query($sqlTrsAsset); 
                    
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