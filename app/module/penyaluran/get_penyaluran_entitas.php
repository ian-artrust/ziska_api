<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {
    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $no_kantor = $_SESSION['no_kantor'];

    if($kode_daerah == ''){

        $sql	= "SELECT * FROM view_224b WHERE status ='REALISASI' AND nama_lembaga !='' ORDER BY no_pengajuan DESC";

    }elseif($no_kantor=='' AND $kode_daerah !=''){

        $sql	= "SELECT 
                    
                    * 
                    
                FROM view_224b 
                
                WHERE kode_daerah='$kode_daerah' AND nama_lembaga !='' AND status ='REALISASI'
                
                ORDER BY no_pengajuan DESC";

    }else{
        $sql	= "SELECT 
                    
                    * 
        
                FROM view_224b 
                
                WHERE no_kantor='$no_kantor' AND nama_lembaga !='' AND status ='REALISASI' 
                
                ORDER BY no_pengajuan DESC";
    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_pengajuan'] = $row['no_pengajuan'];

        $r['nama_lembaga'] = $row['nama_lembaga'];

        $r['nama_master'] = $row['nama_master'];

        $r['keterangan'] = $row['keterangan'];

        $r['asnaf'] = $row['asnaf'];

        $r['sumber_dana'] = $row['sumber_dana'];

        $r['tgl_realisasi'] = $row['tgl_realisasi'];

        $r['jml_realisasi'] = number_format($row['jml_realisasi']);

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}