<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_piutang_ang     = $_POST['no_piutang_ang'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    $sql	= "SELECT 
                
                * 
                
            FROM trs_ang_piutang 
            
            WHERE no_piutang='$no_piutang_ang' 
            
            AND kode_daerah='$kode_daerah' 
            
            AND status='Aktif'";
    
    $result	= $konek->query($sql);

    $data   = '';

    while($row = $result->fetch_assoc()){

        $data .="
            <tr>
                <td>". $row['no_angsuran'] ."</td>

                <td>". $row['tgl_angsuran'] ."</td>

                <td>". number_format($row['jml_angsuran'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    echo $data;
}