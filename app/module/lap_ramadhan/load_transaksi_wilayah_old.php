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

    /** ZAKAT */
    $sqlZakatMal = "SELECT

                            nama_daerah, 
    
                            SUM(jml_donasi) AS zakat_mal

                        FROM view_541

                        WHERE 

                        tgl_donasi BETWEEN '$dari' AND '$sampai'

                        AND kode_prg_pnr='101-3302001' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlZakatProfesi = "SELECT 

                        nama_daerah, 
        
                        SUM(jml_donasi) AS zakat_profesi

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='101-3302002' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlZakatFitrah = "SELECT

                        nama_daerah,  
            
                        SUM(jml_donasi) AS zakat_fitrah

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai'

                    AND kode_prg_pnr='101-3302004' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMuzakiIndividuZakatMal = "SELECT 
                
                        COUNT(npwz) AS individu_zakat_mal

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' AND

                    AND kode_prg_pnr='101-3302001' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMuzakiIndividuZakatProfesi = "SELECT 
                    
                        COUNT(npwz) AS individu_zakat_profesi

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai'

                    AND kode_prg_pnr='101-3302002' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMuzakiIndividuZakatFitrah = "SELECT 
                        
                        COUNT(npwz) AS individu_zakat_fitrah

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai'

                    AND kode_prg_pnr='101-3302004' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMuzakiEntitasZakatMal = "SELECT 
                    
                        COUNT(npwz) AS entitas_zakat_mal

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='101-3302001' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMuzakiEntitasZakatProfesi = "SELECT 
                        
                        COUNT(npwz) AS entitas_zakat_profesi

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai'

                    AND kode_prg_pnr='101-3302002' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMuzakiEntitasZakatFitrah = "SELECT 

                        COUNT(npwz) AS entitas_zakat_fitrah

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai'

                    AND kode_prg_pnr='101-3302004' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    /** INFAK SEDEKAH */
    $sqlInfakSedekah = "SELECT 
            
                        SUM(jml_donasi) AS infak_sedekah

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302001' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlKadoRamadhan = "SELECT 
                
                        SUM(jml_donasi) AS kado_ramadhan

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302005' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlFilantropiCilik = "SELECT 
                
                        SUM(jml_donasi) AS filantropi_cilik

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302008' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlBackToMasjid = "SELECT 
                    
                        SUM(jml_donasi) AS backto_masjid

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302004' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlMudikmu = "SELECT 
                        
                        SUM(jml_donasi) AS mudikmu

                    FROM view_541

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai'

                    AND kode_prg_pnr='102-3302003' AND status !='REJECT' GROUP BY kode_daerah";
    
    $sqlIndividuInfakSedekah = "SELECT 
            
                        COUNT(npwz) AS individu_infak_sedekah

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302001' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlIndividuKadoRamadhan = "SELECT 
                
                        COUNT(npwz) AS individu_kado_ramadhan

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302005' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlIndividuFilantropi = "SELECT 
                    
                        COUNT(npwz) AS individu_filantropi

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302008' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlIndividuBack = "SELECT 
                        
                        COUNT(npwz) AS individu_back

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302004' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlIndividuMudikmu = "SELECT 
                            
                        COUNT(npwz) AS individu_mudikmu

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302003' AND jenis_donatur='Individu' AND status !='REJECT' GROUP BY kode_daerah";

    /** Entitas Infak */
    $sqlEntitasInfakSedekah = "SELECT 
            
                        COUNT(npwz) AS entitas_infak_sedekah

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302001' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlEntitasKadoRamadhan = "SELECT 
                
                        COUNT(npwz) AS entitas_kado_ramadhan

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302005' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlEntitasFilantropi = "SELECT 
                    
                        COUNT(npwz) AS entitas_filantropi

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302008' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlEntitasBack = "SELECT 
                        
                        COUNT(npwz) AS entitas_back

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302004' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $sqlEntitasMudikmu = "SELECT 
                            
                        COUNT(npwz) AS entitas_mudikmu

                    FROM view_541b

                    WHERE 

                    tgl_donasi BETWEEN '$dari' AND '$sampai' 

                    AND kode_prg_pnr='102-3302003' AND jenis_donatur='Entitas' AND status !='REJECT' GROUP BY kode_daerah";

    $hasilZakatMal      = $konek->query($sqlZakatMal);

    $hasilZakatProfesi  = $konek->query($sqlZakatProfesi);

    $hasilZakatFitrah   = $konek->query($sqlZakatFitrah);

    $hasilInfakSedekah  = $konek->query($sqlInfakSedekah);

    $hasilKadoRamadhan  = $konek->query($sqlKadoRamadhan);

    $hasilFilantropi    = $konek->query($sqlFilantropiCilik);

    $hasilBackToMasjid  = $konek->query($sqlBackToMasjid);

    $hasilMudikmu       = $konek->query($sqlMudikmu);

    $hasilIndividuZakatMal = $konek->query($sqlMuzakiIndividuZakatMal);

    $hasilIndividuZakatProfesi = $konek->query($sqlMuzakiIndividuZakatProfesi);

    $hasilIndividuZakatFitrah = $konek->query($sqlMuzakiIndividuZakatFitrah);

    $hasilIndividuInfakSedekah  = $konek->query($sqlIndividuInfakSedekah);

    $hasilIndividuKadoRamadhan  = $konek->query($sqlIndividuKadoRamadhan);

    $hasilIndividuFilantropi  = $konek->query($sqlIndividuFilantropi);

    $hasilIndividuBack = $konek->query($sqlIndividuBack);

    $hasilIndividuMudikmu = $konek->query($sqlIndividuMudikmu);

    $hasilEntitasInfakSedekah  = $konek->query($sqlEntitasInfakSedekah);

    $hasilEntitasKadoRamadhan  = $konek->query($sqlEntitasKadoRamadhan);

    $hasilEntitasFilantropi  = $konek->query($sqlEntitasFilantropi);

    $hasilEntitasBack = $konek->query($sqlEntitasBack);

    $hasilEntitasMudikmu = $konek->query($sqlEntitasMudikmu);

    $hasilEntitasZakatMal = $konek->query($sqlMuzakiEntitasZakatMal);

    $hasilEntitasZakatProfesi = $konek->query($sqlMuzakiEntitasZakatProfesi);

    $hasilEntitasZakatFitrah = $konek->query($sqlMuzakiEntitasZakatFitrah);

    $barisZakatMal      = $hasilZakatMal->fetch_assoc();

    $barisZakatProfesi  = $hasilZakatProfesi->fetch_assoc();

    $barisZakatFitrah   = $hasilZakatFitrah->fetch_assoc();

    $barisInfakSedekah  = $hasilInfakSedekah->fetch_assoc();

    $barisKadoRamadhan  = $hasilKadoRamadhan->fetch_assoc();

    $barisFilantropi    = $hasilFilantropi->fetch_assoc();

    $barisBackToMasjid  = $hasilBackToMasjid->fetch_assoc();

    $barisMudikmu       = $hasilMudikmu->fetch_assoc();

    $barisIndividuZakatMal      = $hasilIndividuZakatMal->fetch_assoc();

    $barisIndividuZakatProfesi  = $hasilIndividuZakatProfesi->fetch_assoc();

    $barisIndividuZakatFitrah   = $hasilIndividuZakatFitrah->fetch_assoc();

    $barisEntitasZakatMal      = $hasilEntitasZakatMal->fetch_assoc();

    $barisEntitasZakatProfesi     = $hasilEntitasZakatProfesi->fetch_assoc();

    $barisEntitasZakatFitrah      = $hasilEntitasZakatFitrah->fetch_assoc();

    $barisIndividuInfakSedekah    = $hasilIndividuInfakSedekah->fetch_assoc();

    $barisIndividuKadoRamadhan    = $hasilIndividuKadoRamadhan->fetch_assoc();

    $barisIndividuFilantropi      = $hasilIndividuFilantropi->fetch_assoc();

    $barisIndividuBack            = $hasilIndividuBack->fetch_assoc();

    $barisIndividuMudikmu         = $hasilIndividuMudikmu->fetch_assoc();

    $barisEntitasInfakSedekah     = $hasilEntitasInfakSedekah->fetch_assoc();

    $barisEntitasKadoRamadhan     = $hasilEntitasKadoRamadhan->fetch_assoc();

    $barisEntitasFilantropi      = $hasilEntitasFilantropi->fetch_assoc();

    $barisEntitasBack            = $hasilEntitasBack->fetch_assoc();

    $barisEntitasMudikmu         = $hasilEntitasMudikmu->fetch_assoc();

    $no=1;

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
                </tr>
                <tr>
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
                <tr>
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