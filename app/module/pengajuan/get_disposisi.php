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

        $sql	= "SELECT * FROM trs_disposisi WHERE status !='REJECT' ORDER BY no_disposisi DESC";

    }elseif($kode_daerah !=''){

        $sql	= "SELECT 
                    
                    * 
                    
                FROM trs_disposisi 
                
                WHERE kode_daerah='$kode_daerah' AND status !='REJECT' 
                
                ORDER BY no_disposisi DESC";

    }elseif($no_kantor !=''){

        $sql	= "SELECT 
                    
                    * 
        
                FROM trs_disposisi 
                
                WHERE no_kantor='$no_kantor' AND status !='REJECT' 
                
                ORDER BY no_disposisi DESC";

    }else{

        $sql	= "SELECT 
                    
                    * 
                    
                FROM trs_disposisi 
                
                WHERE kode_daerah='$kode_daerah' AND status !='ZONK' 
                
                ORDER BY no_disposisi DESC";

    }

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_disposisi'] = $row['no_disposisi'];

        $r['pengirim'] = $row['pengirim'];

        $r['perihal'] = $row['perihal'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);
}