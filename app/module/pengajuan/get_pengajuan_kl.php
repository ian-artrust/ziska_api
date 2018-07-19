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

        $sql	= "SELECT * FROM view_224 WHERE status !='REJECT' ORDER BY no_pengajuan DESC";

    }elseif($kode_daerah !=''){

        $sql	= "SELECT 
                    
                    * 
                    
                FROM view_224 
                
                WHERE kode_daerah='$kode_daerah' AND nama_mustahik!='' AND no_kantor='$no_kantor' AND status !='REJECT' 
                
                ORDER BY no_pengajuan DESC";

    }elseif($no_kantor !=''){

        $sql	= "SELECT 
                    
                    * 
        
                FROM view_224 
                
                WHERE no_kantor='$no_kantor' AND nama_mustahik!='' AND status !='REJECT' 
                
                ORDER BY no_pengajuan DESC";

    }else{

        $sql	= "SELECT 
                    
                    * 
                    
                FROM view_224 
                
                WHERE kode_daerah='$kode_daerah' AND nama_mustahik!='' AND no_kantor='$no_kantor' AND status !='ZONK' 
                
                ORDER BY no_pengajuan DESC";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_pengajuan'] = $row['no_pengajuan'];

        $r['nama_mustahik'] = $row['nama_mustahik'];

        $r['nama_master'] = $row['nama_master'];

        $r['tgl_pengajuan'] = $row['tgl_pengajuan'];

        $r['jml_pengajuan'] = number_format($row['jml_pengajuan']);

        $r['status'] = $row['status'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}