<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $nama_daerah    = $_SESSION['nama_daerah'];

    $kode_petugas   = $_SESSION['kode_petugas'];

    $dari           = $_POST['dari'];

    $sampai         = $_POST['sampai'];

    /** PETUGAS */
    $sqlPetugas = "SELECT kode_petugas, nama_petugas FROM tm_petugas WHERE kode_petugas='$kode_petugas'";

    $hasilPetugas   = $konek->query($sqlPetugas);

    $baris          = $hasilPetugas->fetch_assoc();

    $no=1;

    $sqlPenerimaan = "SELECT

                        nama_daerah, 

                        

                        SUM(jml_donasi) AS zakat_mal

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 
                    
                    AND status !='REJECT' 
                    
                    GROUP BY kode_daerah";

    $data   = "

                <h4><b>LAPORAN PENERIMAAN RAMADHAN</b></h4>

                <h5><b>Periode Tanggal: ".$dari." s/d ".$sampai."</b></h5>

                <hr style='border:1px solid;'>

            ";

        $data .= "
            <table class='table' width='100%' border='1' cellspacing='2' cellpadding='2'>

                <tr>
                    <td valign='middle' rowspan='3'><strong><br><br>NO</strong></td>
                    <td valign='middle' rowspan='3'><strong><br><br>KANTOR LAZISMU DAERAH</strong></td>
                    <td align='center' colspan='10'><strong>PENERIMAAN</strong></td>
                </tr>
                <tr>
                    <td align='center' colspan='3'><strong>ZAKAT</strong></td>
                    <td align='center' colspan='5'><strong>INFAK DAN SEDEKAH </strong></td>
                    <td align='center' colspan='2'><strong>SOSIAL KEAGAMAAN </strong></td>
                </tr>
                <tr>
                    <td align='right'><strong>Zakat Mal </strong></td>
                    <td align='right'><strong>Zakat Profesi </strong></td>
                    <td align='right'><strong>Zakat Fitrah </strong></td>
                    <td align='right'><strong>Umum</strong></td>
                    <td align='right'><strong>Kado Ramadhan </strong></td>
                    <td align='right'><strong>Filantropi Cilik </strong></td>
                    <td align='right'><strong>Back to Masjid </strong></td>
                    <td align='right'><strong>Mudikmu</strong></td>
                    <td align='right'><strong>Wakaf</strong></td>
                    <td align='right'><strong>Lainnya</strong></td>
                </tr>";
                
            $data .= "<tr>
                    <td>".$no."</td>
                    <td>".$nama_daerah."</td>
                    <td align='right'>".number_format($barisZakatMal['zakat_mal'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisZakatProfesi['zakat_profesi'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisZakatFitrah['zakat_fitrah'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisInfakSedekah['infak_sedekah'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisKadoRamadhan['kado_ramadhan'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisFilantropi['filantorpi_cilik'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisBackToMasjid['backto_masjid'], 0, ',', '.')."</td>
                    <td align='right'>".number_format($barisMudikmu['mudikmu'], 0, ',', '.')."</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            ";
            
        $data .="<tr>
                    <td colspan='2'><strong>Donatur Individu </strong></td>
                    <td align='right'>".$barisIndividuZakatMal['individu_zakat_mal']."</td>
                    <td align='right'>".$barisIndividuZakatProfesi['individu_zakat_profesi']."</td>
                    <td align='right'>".$barisIndividuZakatFitrah['individu_zakat_fitrah']."</td>
                    <td align='right'>".$barisIndividuInfakSedekah['individu_infak_sedekah']."</td>
                    <td align='right'>".$barisIndividuKadoRamadhan['individu_kado_ramadhan']."</td>
                    <td align='right'>".$barisIndividuFilantropi['individu_filantropi']."</td>
                    <td align='right'>".$barisIndividuBack['individu_back']."</td>
                    <td align='right'>".$barisIndividuMudikmu['individu_mudikmu']."</td>
                    <td align='right'>&nbsp;</td>
                    <td align='right'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2'><strong>Donatur Entitas </strong></td>
                    <td align='right'>".$barisEntitasZakatMal['entitas_zakat_mal']."</td>
                    <td align='right'>".$barisEntitasZakatProfesi['entitas_zakat_profesi']."</td>
                    <td align='right'>".$barisEntitasZakatFitrah['entitas_zakat_fitrah']."</td>
                    <td align='right'>".$barisEntitasInfakSedekah['entitas_infak_sedekah']."</td>
                    <td align='right'>".$barisEntitasKadoRamadhan['entitas_kado_ramadhan']."</td>
                    <td align='right'>".$barisEntitasFilantropi['entitas_filantropi']."</td>
                    <td align='right'>".$barisEntitasBack['entitas_back']."</td>
                    <td align='right'>".$barisEntitasMudikmu['entitas_mudikmu']."</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>";
   
    $data .= "</table>";

    echo $data;

}