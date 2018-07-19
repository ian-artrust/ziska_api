<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_daerah    = $_SESSION['kode_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $periode        = $_POST['periode'];

    if($kode_daerah==''){

        $sql	= "SELECT 
        
                    kode_akun,
                    
                    akun,

                    saldo_normal,

                    SUM(debit) AS debit,

                    SUM(kredit) AS kredit,

                    IF(SUM(debit) > SUM(kredit), SUM(debit)-SUM(kredit), SUM(kredit)- SUM(debit)) AS saldo
    
                FROM view_631 

                WHERE periode='$periode' AND status !='REJECT'";

    } else {

        $sql	= "SELECT 
                    kode_akun,
        
                    akun,
        
                    saldo_normal,
        
                    SUM(debit) AS debit,
        
                    SUM(kredit) AS kredit,
        
                    IF(SUM(debit) > SUM(kredit), SUM(debit)-SUM(kredit), SUM(kredit)- SUM(debit)) AS saldo
                
                FROM view_631 
        
        WHERE periode='$periode' AND kode_daerah='$kode_daerah' AND status !='REJECT'
        
        GROUP BY kode_akun";

    }

    $sqlPetugas = "SELECT createdby, nama_petugas FROM view_221 WHERE createdby='$kode_petugas'";

    
    $sqlTotal	= "SELECT 

                SUM(debit) AS debit,
            
                SUM(kredit) AS kredit,

				SUM(debit) - SUM(kredit) AS saldo

            FROM view_631

             WHERE periode='$periode' AND jenis='JU'
            
            AND kode_daerah='$kode_daerah' AND status !='REJECT' 
            
            GROUP BY kode_akun";

    
    $hasil	        = $konek->query($sql);

    $hasilPetugas   = $konek->query($sqlPetugas);

    // $hasilTotal     = $konek->query($sqlTotal);

    $baris          = $hasilPetugas->fetch_assoc();

    // $barisTotal     = $hasilTotal->fetch_assoc();

    $data   = "

                <h4><b>NERACA SALDO</b></h4>

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
                    <tr>
                        <td><b>PERIODE</b></td>
                        <td><b>:</b></td>
                        <td>".$periode."</td>
                    </tr>

                </table>
            
                <hr style='border:1px solid;'>

                <table class='table'>

                    <tr>
                    
                        <td><b>Kode Akun</b></td>

                        <td><b>Akun</b></td>

                        <td><b>Posisi</b></td>

                        <td><b>Debit</b></td>

                        <td><b>Kredit</b></td>

                        <td><b>Saldo</b></td>

                    </tr>

            ";

    while($row = $hasil->fetch_assoc()){

        $class = ' class="normal"';

        $data .="
            <tr ". $class .">
                
                <td>". $row['kode_akun'] ."</td>

                <td>". $row['akun'] ."</td>

                <td>". $row['saldo_normal'] ."</td>

                <td>". number_format($row['debit'], 0, ',', '.') ."</td>

                <td>". number_format($row['kredit'], 0, ',', '.') ."</td>

                <td>". number_format($row['saldo'], 0, ',', '.') ."</td>

            </tr>
            
        ";

    }

    $data .= "</table>";

    echo $data;

}