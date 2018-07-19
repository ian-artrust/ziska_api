<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah        = $_SESSION['kode_daerah'];

    $kode_petugas       = $_SESSION['kode_petugas'];

    $no_kantor          = $_SESSION['no_kantor'];

    $sql	= "SELECT

                no_kantor,
    
                nama_kantor,
                
                SUM(jml_donasi) AS total_penghimpunan
    
            FROM view_233 WHERE kode_daerah='$kode_daerah' AND status!='REJECT' GROUP BY no_kantor";

    $sqlPetugas = "SELECT kode_petugas, nama_petugas FROM tm_petugas WHERE kode_petugas='$kode_petugas'";
    
    $hasil	        = $konek->query($sql);

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $no = 1;

    $data   = "

                <h4><b>Rekap Penghimpunan Kantor Layanan</b></h4>

                <hr style='border:1px solid;'>

                <table cellspacing='15' cellpadding='15'>
                    
                    <tr>
                        <td width='150'><b>KODE PETUGAS</b></td>
                        <td width=25><b>:</b></td>
                        <td>".$kode_petugas."</td>
                    </tr>
                    <tr>
                        <td><b>PETUGAS</b></td>
                        <td><b>:</b></td>
                        <td>".$baris['nama_petugas']."</td>
                    </tr>

                </table>
            
                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                        <td><b>No</b></td>
                        
                        <td><b>No Kantor</b></td>

                        <td><b>Nama Kantor</b></td>

                        <td  align='right'><b>Jumlah</td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $class = ' class="normal"';

        $data .="
            <tr ". $class .">

                <td>". $no ."</td>
                
                <td>". $row['no_kantor'] ."</td>

                <td>". $row['nama_kantor'] ."</td>

                <td  align='right' id='jml_donasi'>". number_format($row['total_penghimpunan'], 0, ',', '.') ."</td>

            </tr>
        ";

        $no++;

    }

    $data .= "</table>";

    echo $data;

}