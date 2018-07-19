<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    /** PETUGAS */
    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $data   = "

                <h4><b>DAFTAR ASSET</b></h4>

                <hr style='border:1px solid;'>

            ";
            
        $sql	= "SELECT 

                        no_asset, 

                        asset,

                        kategori,

                        nilai_asset

                    FROM view_326 

                    WHERE 

                        kode_daerah = '$kode_daerah' AND status='Aktif' ORDER BY kategori";

        $hasil	        = $konek->query($sql);

        $data .= "
                <table class='table'>

                    <tr>
                        
                            <td><b>No Asset</b></td>

                            <td><b>Asset</b></td>

                            <td><b>Kategori</b></td>

                            <td><b>Nilai Perolehan</b></td>

                            <td><b>Nilai Buku</b></td>

                    </tr>";

        while($row = $hasil->fetch_assoc()){

            $no_asset = $row['no_asset'];

            $sqlFind 	= "SELECT 

                    SUM(nilai_penyusutan) AS nilai_penyusutan

                FROM view_326b 

                WHERE no_asset='$no_asset' 
                
                AND status='Aktif'";
            
            $get_nb = $konek->query($sqlFind);

            $row_nb = $get_nb->fetch_assoc();

            $nilai_buku = intval($row['nilai_asset']) - intval($row_nb['nilai_penyusutan']);
    
            $data .="
                
                <tr>
                    
                    <td>". $row['no_asset'] ."</td>
    
                    <td>". $row['asset'] ."</td>

                    <td>". $row['kategori'] ."</td>

                    <td>". number_format($row['nilai_asset'], 0, ',', '.') ."</td>
    
                    <td>". number_format($nilai_buku, 0, ',', '.') ."</td>
    
                </tr>";
    
        }

    $data .= "</table>";

    echo $data;

}