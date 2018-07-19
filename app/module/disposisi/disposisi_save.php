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
	function autonum($lebar=0, $awalan='', $kode_daerah){
        include "../../../bin/koneksi.php";

        $tahun = date('y');

		$sqlcount= "SELECT no_disposisi 
        
                        FROM trs_disposisi

                        WHERE kode_daerah='$kode_daerah' AND MID(no_disposisi,5,2)='$tahun' 
                        
                        ORDER BY no_disposisi DESC";
        
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
    
    $no_disposisi	    = autonum(6,"DSP-".date('y').$kode_daerah, $kode_daerah);

    $no_agenda 		    = strip_tags($_POST['no_agenda']);

    $pengirim 	        = strip_tags($_POST['pengirim']);
    
    $no_surat 		    = strip_tags($_POST['no_surat']);
    
    $tgl_surat 			= strip_tags($_POST['tgl_surat']);
    
    $tertanggal_surat 	= strip_tags($_POST['tertanggal_surat']);

    $deliver_to 		= strip_tags($_POST['deliver_to']);

    $perihal 		    = strip_tags($_POST['perihal']);

    $catatan_penerima   = strip_tags($_POST['catatan_penerima']);

    $createdby          = $_SESSION['kode_petugas'];
     
    /* Validasi Kode */
    $sqlCekNoDisposisi     = "SELECT no_disposisi FROM trs_disposisi WHERE no_disposisi='$no_disposisi'";
    
    $exe_sqlCekNoDisposisi = $konek->query($sqlCekNoDisposisi);

    $cekNoDisposisi	    = mysqli_num_rows($exe_sqlCekNoDisposisi);

    /* SQL Query Simpan */
    $sqlDisposisi = "INSERT INTO 
        trs_disposisi(
            no_disposisi,
            no_agenda,
            pengirim,
            no_surat,
            tgl_surat,
            tertanggal_surat,
            deliver_to,
            perihal,
            catatan_penerima,
            kode_daerah,
            status,
            createdby
        )VALUES(
            '$no_disposisi',
            '$no_agenda',
            '$pengirim',
            '$no_surat',
            '$tgl_surat',
            '$tertanggal_surat',
            '$deliver_to',
            '$perihal',
            '$catatan_penerima',
            '$kode_daerah',
            'COMPLETE',
            '$createdby'
        )";
    
    if($cekNoDisposisi > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        
    } else {

        $insertNoDisposisi = $konek->query($sqlDisposisi); 

        $pesan 		= $no_disposisi." Telah Didisposisikan";

        $response 	= array('pesan'=>$pesan, 'no_disposisi'=>$no_disposisi);

        echo json_encode($response);
    }

}