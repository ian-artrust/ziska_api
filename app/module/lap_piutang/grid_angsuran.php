<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_piutang_ang     = $_POST['no_piutang_ang'];

    $nama_kreditur_ang     = $_POST['nama_kreditur_ang'];

    $jumlah_piutang     = $_POST['jumlah_piutang'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    $sql	= "SELECT 
                
                * 
                
            FROM trs_ang_piutang 
            
            WHERE no_piutang='$no_piutang_ang' 
            
            AND kode_daerah='$kode_daerah' 
            
            AND status='Aktif'";

    $sqlFind 	= "SELECT 

                jml_piutang,

                SUM(jml_angsuran) AS total_angsuran,

                jml_piutang - SUM(jml_angsuran) AS sisa_piutang

            FROM view_325b 

            WHERE no_piutang='$no_piutang_ang' 

            AND status='Aktif'";

    $get_sisa = $konek->query($sqlFind);

    $row = $get_sisa->fetch_assoc();
    
    $result	= $konek->query($sql);

    $sisa_piutang = $row['sisa_piutang'];

    $data   = "

                <h4><b>LAPORAN ANGSURAN KREDITUR</b></h4>

                    <hr style='border:1px solid;'>

                    <table border='0'>

                        <tr>
                            
                            <td><b>Kreditur</b></td>

                            <td>:</td>

                            <td><b>".$nama_kreditur_ang."</b></td>

                        </tr>

                        <tr>

                            <td><b>Jml Piutang</b></td>

                            <td>:</td>

                            <td><b>".number_format($jumlah_piutang, 0, ',', '.')."</b></td>

                        </tr>

                        <tr>

                            <td><b>Sisa Piutang</b></td>

                            <td>:</td>

                            <td><b>".number_format($sisa_piutang, 0, ',', '.')."</b></td>

                        </tr>

                    </table>

                <hr style='border:1px solid;'>

            ";
    
    $data .= "
            <table class='table'>

                <tr>
                    
                        <td><b>No Angsuran</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Jml Angsuran</b></td>

                </tr>";

    while($row = $result->fetch_assoc()){

        $data .="

            <tr>
                <td>". $row['no_angsuran'] ."</td>

                <td>". $row['tgl_angsuran'] ."</td>

                <td>". number_format($row['jml_angsuran'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    $data .= "</table>";

    echo $data;
}