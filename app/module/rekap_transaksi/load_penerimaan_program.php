<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $dari_prog      = $_POST['dari_prog'];

    $sampai_prog    = $_POST['sampai_prog'];

    $program        = $_POST['program'];

    $sql	= "SELECT 
        kategori,
        
        no_donasi,
        
        nama_donatur,
        
        tgl_donasi,
        
        program,

        norek_bank,

        createdby,

        status,
    
        jml_donasi 

    FROM view_221
    
    WHERE tgl_donasi BETWEEN '$dari_prog' AND '$sampai_prog' 
    
    AND kode_daerah='$kode_daerah' AND kode_prg_pnr='$program' 
    
    AND status='Aktif'";

    $sqlTotal = "SELECT SUM(jml_donasi) AS total_donasi FROM view_221

                    WHERE tgl_donasi BETWEEN '$dari_prog' AND '$sampai_prog' 

                    AND kode_daerah='$kode_daerah' AND kode_prg_pnr='$program' 

                    AND status='Aktif'";

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";
    
    $hasil	        = $konek->query($sql);

    $hasilTotal     = $konek->query($sqlTotal);

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $barisTotal     = $hasilTotal->fetch_assoc();

    $data   = "

                <h4><b>Laporan Program Penerimaan</b></h4>
            
                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                    
                        <td><b>Kategori</b></td>

                        <td><b>No Donasi</b></td>

                        <td><b>Muzaki</b></td>

                        <td><b>No Rek</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Program</b></td>

                        <td  align='right'><b>Jumlah</td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $class = ' class="normal"';

        $data .="
            <tr ". $class .">
                
                <td>". $row['kategori'] ."</td>

                <td>". $row['no_donasi'] ."</td>

                <td>". $row['nama_donatur'] ."</td>

                <td>". $row['norek_bank'] ."</td>

                <td>". $row['tgl_donasi'] ."</td>

                <td>". $row['program'] ."</td>

                <td  align='right' id='jml_donasi'>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "
            <tr>

                <td colspan='6' align='right'><b>Total Donasi:</b> </td>

                <td align='right'><b>".number_format($barisTotal['total_donasi'], 0, ',', '.')."</b></td>

            </tr>
    ";

    $data .= "</table>";

    echo $data;

}