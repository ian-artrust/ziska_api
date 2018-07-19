<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    if($kode_petugas=='' AND $kode_daerah==''){

        $sql	= "SELECT 
        
                    * 
                    
                FROM view_222 
                
                WHERE status='Aktif' AND jenis='Penerimaan Zakat Non Tunai'
                
                OR status='Aktif' AND jenis='Penerimaan Infak Terikat Non Tunai'
                
                OR status='Aktif' AND jenis='Penerimaan Infak Tidak Terikat Non Tunai'";        

    }elseif(!empty($kode_daerah)) {
        
        $sql	= "SELECT 
        
                    * 
                FROM view_222 
                
                WHERE status='Aktif' AND kode_daerah='$kode_daerah' AND jenis='Penerimaan Zakat Non Tunai'
                
                OR status='Aktif' AND kode_daerah='$kode_daerah' AND jenis='Penerimaan Infak Terikat Non Tunai'
                
                OR status='Aktif' AND kode_daerah='$kode_daerah' AND jenis='Penerimaan Infak Tidak Terikat Non Tunai'";

    }elseif(!empty($kode_petugas)){

        $sql	= "SELECT 
        
                    * 
                    
                FROM view_222 
                
                WHERE status='Aktif' AND createdby='$kode_petugas' AND kode_daerah='$kode_daerah' AND jenis='Penerimaan Zakat Non Tunai'
                
                OR status='Aktif' AND createdby='$kode_petugas' AND kode_daerah='$kode_daerah' AND jenis='Penerimaan Infak Terikat Non Tunai'
                
                OR status='Aktif' AND createdby='$kode_petugas' AND kode_daerah='$kode_daerah' AND jenis='Penerimaan Infak Tidak Terikat Non Tunai'";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_donasi'] = $row['no_donasi'];

        $r['npwz'] = $row['npwz'];

        $r['nama_donatur'] = $row['nama_donatur'];

        $r['tgl_donasi'] = $row['tgl_donasi'];

        $r['norek_bank'] = $row['norek_bank'];

        $r['program'] = $row['program'];

        $r['jml_donasi'] = number_format($row['jml_donasi'], 0, ',', '.');

        array_push($response["data"], $r);
    }
    echo json_encode($response);
}