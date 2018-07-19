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

                <h4><b>DAFTAR PIUTANG</b></h4>

                <hr style='border:1px solid;'>

            ";
            
        $sql	= "SELECT 

                        no_piutang, 

                        nama_kreditur,

                        jml_piutang

                    FROM view_325 

                    WHERE 

                        kode_daerah = '$kode_daerah' AND status='Aktif'";

        $hasil	        = $konek->query($sql);

        $data .= "
                <table class='table'>

                    <tr>
                        
                            <td><b>No Piutang</b></td>

                            <td><b>Kreditur</b></td>

                            <td><b>Jml Piutang</b></td>

                            <td><b>Sisa Piutang</b></td>

                    </tr>";

        while($row = $hasil->fetch_assoc()){

            $no_piutang_ang = $row['no_piutang'];

            $sqlFind 	= "SELECT 

                    jml_piutang,

                    SUM(jml_angsuran) AS total_angsuran,

                    jml_piutang - SUM(jml_angsuran) AS sisa_piutang

                FROM view_325b 

                WHERE no_piutang='$no_piutang_ang' 
                
                AND status='Aktif'";
            
            $get_sisa = $konek->query($sqlFind);

            $row_sisa = $get_sisa->fetch_assoc();

            $class = ' class="normal"';
    
            $data .="
                
                <tr ". $class .">
                    
                    <td>". $row['no_piutang'] ."</td>
    
                    <td>". $row['nama_kreditur'] ."</td>

                    <td>". number_format($row['jml_piutang'], 0, ',', '.') ."</td>
    
                    <td>". number_format($row_sisa['sisa_piutang'], 0, ',', '.') ."</td>
    
                </tr>";
    
        }

    $data .= "</table>";

    echo $data;

}