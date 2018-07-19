<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    $tgl_donasi = $_POST['tgl_donasi'];

    $kode_petugas =  $_SESSION['kode_petugas'];

    $sql	= "SELECT 
    
                kategori, 
                
                SUM(jml_donasi) AS jumlah 
                
                FROM view_221

                WHERE tgl_donasi='$tgl_donasi' AND metode='CASH' AND kode_daerah='$kode_daerah' AND createdby='$kode_petugas' AND status='Aktif'

                OR tgl_donasi='$tgl_donasi' AND metode='MUTASI BANK' AND kode_daerah='$kode_daerah' AND createdby='$kode_petugas' AND status='Aktif'

                GROUP BY kode_kategori";

    $sqlNT	= "SELECT 
        
                kategori, 

                SUM(jml_donasi) AS jumlah 

                FROM view_221

                WHERE tgl_donasi='$tgl_donasi' AND metode='NON TUNAI' AND kode_daerah='$kode_daerah' AND createdby='$kode_petugas' AND status='Aktif'

                GROUP BY kode_kategori";
    
    $hasil	    = $konek->query($sql);

    $hasilNT	= $konek->query($sqlNT);

    while($row = $hasil->fetch_assoc()){

        $data ="
                <tr>

                    <td>". $row['kategori'] ."</td>

                    <td id='jumlah' align='right'>". number_format($row['jumlah'], 0, ',', '.') ."</td>

                </tr>
            ";

        echo $data;
    }

}