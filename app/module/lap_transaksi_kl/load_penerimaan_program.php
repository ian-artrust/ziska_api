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

    $dari_prog_tgl      = $_POST['dari_prog_tgl'];

    $sampai_prog_tgl    = $_POST['sampai_prog_tgl'];

    $program            = $_POST['program'];

    if($kode_daerah==''){

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

        FROM view_233
        
        WHERE tgl_donasi BETWEEN '$dari_prog_tgl' AND '$sampai_prog_tgl' 
        
        AND kode_prg_pnr='$program' AND kode_daerah='$kode_daerah' AND status!='REJECT'";
    
    $sqlTotal = "SELECT 
                            
            SUM(jml_donasi) AS total_donasi

        FROM view_233

        WHERE tgl_donasi BETWEEN '$dari_prog_tgl' AND '$sampai_prog_tgl' 
        
        AND kode_prg_pnr='$program' AND status!='REJECT'";

    } else {

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

        FROM view_233
        
        WHERE tgl_donasi BETWEEN '$dari_prog_tgl' AND '$sampai_prog_tgl' 
        
        AND kode_prg_pnr='$program' AND no_kantor='$no_kantor' AND status !='REJECT'";

        $sqlTotal = "SELECT 
                                    
            SUM(jml_donasi) AS total_donasi

        FROM view_233

        WHERE tgl_donasi BETWEEN '$dari_prog_tgl' AND '$sampai_prog_tgl' 
        
        AND no_kantor='$no_kantor' AND kode_prg_pnr='$program' AND status!='REJECT'";

    }

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_233 WHERE createdby='$kode_petugas'";
    
    $hasil	        = $konek->query($sql);

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $hasilTotal = $konek->query($sqlTotal);

    $row_total = $hasilTotal->fetch_assoc();

    $total_dns = $row_total['total_donasi'];

    $data   = "

                <h4><b>Laporan Penerimaan Petugas</b></h4>

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

    $data .= "<tr>
        
                <td colspan='6' align='right'><b>Jumlah: </b></td>

                <td align='right'><b>".number_format($total_dns, 0, ',', '.')."</b></td>

            </tr>";

    $data .= "</table>";

    echo $data;

}