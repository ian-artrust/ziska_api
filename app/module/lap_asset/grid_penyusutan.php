<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_asset_pny     = $_POST['no_asset_pny'];

    $asset_pny     = $_POST['asset_pny'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    $sql	= "SELECT 
                
                * 
                
            FROM view_326b 
            
            WHERE no_asset='$no_asset_pny'
            
            AND status='Aktif'";
    
    $result	= $konek->query($sql);

    $data   = "

                <h4><b>LAPORAN PENYUSUTAN ASSET</b></h4>

                    <hr style='border:1px solid;'>

                    <table border='0'>

                        <tr>
                            
                            <td><b>No Asset</b></td>

                            <td>:</td>

                            <td><b>".$no_asset_pny."</b></td>

                        </tr>

                        <tr>

                            <td><b>Nama Asset</b></td>

                            <td>:</td>

                            <td><b>".$asset_pny."</b></td>

                        </tr>

                    </table>

                <hr style='border:1px solid;'>

            ";
    
    $data .= "</table>";

    $data .= "
            <table class='table'>

                <tr>
                    
                        <td><b>No Penyusutan</b></td>

                        <td><b>Kategori</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Nilai Penyusutan</b></td>

                </tr>";


    while($row = $result->fetch_assoc()){

        $data .="
            <tr>
                <td>". $row['no_penyusutan'] ."</td>    

                <td>". $row['kategori'] ."</td>

                <td>". $row['tgl_penyusutan'] ."</td>

                <td>". number_format($row['nilai_penyusutan'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "</table>";

    echo $data;
}