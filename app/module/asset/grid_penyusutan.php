<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_asset_pny     = $_POST['no_asset_pny'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    $sql	= "SELECT 
                
                * 
                
            FROM view_326b 
            
            WHERE no_asset='$no_asset_pny'
            
            AND status='Aktif'";
    
    $result	= $konek->query($sql);

    $data   = '';

    while($row = $result->fetch_assoc()){

        $data .="
            <tr>
                <td>". $row['no_penyusutan'] ."</td>    
            
                <td>". $row['no_asset'] ."</td>

                <td>". $row['asset'] ."</td>

                <td>". $row['kategori'] ."</td>

                <td>". $row['tgl_penyusutan'] ."</td>

                <td>". number_format($row['nilai_penyusutan'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    echo $data;
}