<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $no_kantor      = $_SESSION['no_kantor'];

    $dari_tgl       = $_POST['dari_tgl'];

    $sampai_tgl     = $_POST['sampai_tgl'];

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_233 WHERE createdby='$kode_petugas'";

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $sqlHeader      = "SELECT kategori FROM view_233 
                    
                    WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' 
        
                    AND no_kantor='$no_kantor' AND kode_daerah='$kode_daerah'
                    
                    GROUP BY kategori";
    
    $hasilHeader    = $konek->query($sqlHeader);

    $data   = "<h4><b>Laporan Penerimaan Petugas</b></h4>

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
                        <td>".$_SESSION['nama_petugas']."</td>
                    </tr>

                </table>

                <hr style='border:1px solid;'>";

    while ($row_hdr = $hasilHeader->fetch_assoc()) {
        
        $data .= "<table class='table'>

                    <tr>
                        <td><b>".$row_hdr['kategori']."</b></td>
                    </tr>
                    <tr>

                        <td><b>No Donasi</b></td>

                        <td><b>Muzaki</b></td>

                        <td><b>Tanggal</b></td>

                        <td><b>Program</b></td>

                        <td  align='right'><b>Jumlah</td>

                    </tr>";

        $rwh = $row_hdr['kategori'];

        if($kode_daerah==''){

            $sql	= "SELECT 
                
                no_donasi,
                
                nama_donatur,
                
                tgl_donasi,
                
                program,
    
                createdby,

                jml_donasi,
    
                status
    
            FROM view_233
            
            WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' AND kategori='$rwh'";

            $sqlTotal = "SELECT 
                            
                        SUM(jml_donasi) AS total_donasi

            FROM view_233
            
            WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' 
            
            AND kategori='$rwh'";
    
        } else {
    
            $sql	= "SELECT 
                
                no_donasi,
                
                nama_donatur,
                
                tgl_donasi,
                
                program,
    
                createdby,

                jml_donasi,
    
                status 
    
            FROM view_233
            
            WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' AND kategori='$rwh' 
            
            AND no_kantor='$no_kantor' AND kode_daerah='$kode_daerah' AND status !='REJECT'";

            $sqlTotal = "SELECT 
                                        
                    SUM(jml_donasi) AS total_donasi

            FROM view_233

            WHERE tgl_donasi BETWEEN '$dari_tgl' AND '$sampai_tgl' 
            
            AND kategori='$rwh' AND status !='REJECT' 
            
            AND kode_daerah='$kode_daerah'  AND no_kantor='$no_kantor'";
    
        }
        
        $hasil = $konek->query($sql);

        $hasilTotal = $konek->query($sqlTotal);

        $row_total = $hasilTotal->fetch_assoc();

        $total_dns = $row_total['total_donasi'];
    
        while($row = $hasil->fetch_assoc()){
    
            $data .="
                <tr>
    
                    <td>". $row['no_donasi'] ."</td>
    
                    <td>". $row['nama_donatur'] ."</td>
    
                    <td>". $row['tgl_donasi'] ."</td>
    
                    <td>". $row['program'] ."</td>

                    <td align='right'>". number_format($row['jml_donasi'], 0, ',', '.') ."</td>
    
                </tr>
            ";
    
        }

        $data .= "<tr>
        
            <td colspan='4' align='right'><b>Jumlah: </b></td>

            <td align='right'><b>".number_format($total_dns, 0, ',', '.')."</b></td>
        
        </tr>";

    }

    $data .= "</table>";

    echo $data;

}