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
    
    /** SQL Zakat */
    $sqlPenerimaanZakat = "SELECT
    
                    akun,

                    SUM(kredit) AS penerimaan_zakat
                
                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Zakat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Zakat Via Bank' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Zakat Non Tunai' AND status !='REJECT'
                
                GROUP BY kode_akun";

    $sqlPenyaluranZakat = "SELECT
    
                    akun,

                    SUM(debit) AS penyaluran_zakat

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Zakat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Zakat Non Tunai' AND status !='REJECT'

                GROUP BY kode_akun";

    $sqlJmlPnrZakat = "SELECT
        
                        akun,

                        SUM(kredit) AS jml_pnr_zakat

                    FROM view_641 

                    WHERE kode_daerah='$kode_daerah' AND periode='$periode' 
                    
                    AND komponen='PDZ' AND kategori_induk='Penerimaan' AND status !='REJECT'";

    $sqlJmlPylZakat = "SELECT
            
                        akun,

                        SUM(debit) AS jml_pyl_zakat

                    FROM view_641 

                    WHERE kode_daerah='$kode_daerah' AND periode='$periode' 
                    
                    AND komponen='BDZ' AND kategori_induk='Pendistribusian' AND status !='REJECT'";

    $sqlSaldoAwalZakat = "SELECT 

                SUM(jml_setup) AS saldo_awal 

            FROM view_321c

            WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

            AND komponen='SDZ' AND status !='REJECT'
    
            GROUP BY komponen";

    /** SQL Infak */

    $sqlPenerimaanIT = "SELECT
    
                akun,

                SUM(kredit) AS penerimaan_it
            
            FROM view_641 

            WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat' AND status !='REJECT'

            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat Via Bank' AND status !='REJECT'

            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat Non Tunai' AND status !='REJECT'
            
            GROUP BY kode_akun";

    $sqlJmlPnrIT = "SELECT
        
                akun,

                SUM(kredit) AS jml_pnr_it

            FROM view_641 

            WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat' AND status !='REJECT'

            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat Via Bank' AND status !='REJECT'

            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Terikat Non Tunai' AND status !='REJECT'
            
            GROUP BY kategori";

    $sqlPenerimaanIS = "SELECT
            
                akun,

                SUM(kredit) AS penerimaan_is

            FROM view_641 

            WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat' AND status !='REJECT'

            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat Via Bank' AND status !='REJECT'
            
            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat Non Tunai' AND status !='REJECT'
            
            GROUP BY kode_akun";

    $sqlJmlPnrIS = "SELECT

                akun,

                SUM(kredit) AS jml_pnr_is

            FROM view_641 

            WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat' AND status !='REJECT'

            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat Via Bank' AND status !='REJECT'
            
            OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Infak Tidak Terikat Non Tunai' AND status !='REJECT'
            
            GROUP BY kategori";
    
    $sqlPenyaluranIT = "SELECT
    
                    akun,

                    SUM(debit) AS penyaluran_it

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Terikat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Terikat Non Tunai' AND status !='REJECT'
                
                GROUP BY kode_akun";

    $sqlJmlPylIT= "SELECT
                
                    akun,

                    SUM(debit) AS jml_pyl_it

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Terikat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Terikat Non Tunai' AND status !='REJECT'

                GROUP BY kategori";

    $sqlPenyaluranIS = "SELECT
        
                    akun,

                    SUM(debit) AS penyaluran_is

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Tidak Terikat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Tidak Terikat Non Tunai' AND status !='REJECT'
                
                GROUP BY kode_akun";

    $sqlJmlPylIS= "SELECT

                    akun,

                    SUM(debit) AS jml_pyl_is

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Tidak Terikat' AND status !='REJECT'

                OR kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran Infak Tidak Terikat Non Tunai' AND status !='REJECT'
                
                GROUP BY kategori";
    
    $sqlSaldoAwalInfak = "SELECT 

                        SUM(jml_setup) AS saldo_awal 

                    FROM view_321c

                    WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                    AND komponen='SDI' AND status !='REJECT'

                    GROUP BY komponen";

    /** SQL CSR */

    $sqlPenerimaanCSR = "SELECT
        
                    akun,

                    SUM(kredit) AS penerimaan_csr

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan CSR' AND status !='REJECT' OR 

                kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan CSR Via Bank' AND status !='REJECT'

                GROUP BY kode_akun";

    $sqlPenyaluranCSR = "SELECT

                    akun,

                    SUM(debit) AS penyaluran_csr

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran CSR' AND status !='REJECT'

                GROUP BY kode_akun";

    $sqlJmlPnrCSR = "SELECT

                    akun,

                    SUM(kredit) AS jml_pnr_csr

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan CSR' AND status !='REJECT' 
                
                OR 

                kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan CSR Via Bank' AND status !='REJECT'

                GROUP BY kategori";

    $sqlJmlPylCSR = "SELECT

                    akun,

                    SUM(debit) AS jml_pyl_csr

                FROM view_641 

                WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penyaluran CSR' AND status !='REJECT'

                GROUP BY kategori";

    $sqlSaldoAwalCSR = "SELECT 

                    SUM(jml_setup) AS saldo_awal 

                FROM view_321c

                WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                AND komponen='SDC' AND status !='REJECT'

                GROUP BY komponen";

    /** SQL Amil */
    $sqlPenerimaanAmil = "SELECT
    
                            akun,

                            SUM(kredit) AS penerimaan_amil

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Amil' AND status !='REJECT'

                        GROUP BY kode_akun";

    $sqlPenyaluranAmil = "SELECT

                            akun,

                            SUM(debit) AS penyaluran_amil

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Beban Amil' AND status !='REJECT'

                        GROUP BY kode_akun";

    $sqlJmlPnrAmil = "SELECT

                            akun,

                            SUM(kredit) AS jml_pnr_amil

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Penerimaan Amil' AND status !='REJECT'

                        GROUP BY kategori";

    $sqlJmlPylAmil = "SELECT

                            akun,

                            SUM(debit) AS jml_pyl_amil

                        FROM view_641 

                        WHERE kode_daerah='$kode_daerah' AND periode='$periode' AND kategori='Beban Amil' AND status !='REJECT'

                        GROUP BY kategori";

    $sqlSaldoAwalAmil = "SELECT 

                            SUM(jml_setup) AS saldo_awal 

                        FROM view_321c

                        WHERE periode='$periode' AND kode_daerah='$kode_daerah' 

                        AND komponen='SDA' AND status !='REJECT'

                        GROUP BY komponen";
    
    /** Zakat */
    $hasilPnrZakat              = $konek->query($sqlPenerimaanZakat);

    $hasilPylZakat              = $konek->query($sqlPenyaluranZakat);

    $hasilJmlPnrZakat           = $konek->query($sqlJmlPnrZakat);

    $hasilJmlPylZakat           = $konek->query($sqlJmlPylZakat);
    
    $hasilSaldoDanaAwalZakat    = $konek->query($sqlSaldoAwalZakat);

    $rowJmlPnrZakat             = $hasilJmlPnrZakat->fetch_assoc();
    
    $rowJmlPylZakat             = $hasilJmlPylZakat->fetch_assoc();

    $rowSaldoAwalDanaZakat      = $hasilSaldoDanaAwalZakat->fetch_assoc(); 

    /** Infak */
    $hasilPnrIT              = $konek->query($sqlPenerimaanIT);

    $hasilJmlPnrIT           = $konek->query($sqlJmlPnrIT);

    $hasilPylIT              = $konek->query($sqlPenyaluranIT);

    $hasilJmlPylIT           = $konek->query($sqlJmlPylIT);

    $rowJmlPnrIT             = $hasilJmlPnrIT->fetch_assoc();

    $rowJmlPylIT             = $hasilJmlPylIT->fetch_assoc();

    $hasilPnrIS              = $konek->query($sqlPenerimaanIS);

    $hasilJmlPnrIS           = $konek->query($sqlJmlPnrIS);

    $hasilPylIS              = $konek->query($sqlPenyaluranIS);

    $hasilJmlPylIS           = $konek->query($sqlJmlPylIS);

    $hasilSaldoDanaAwalInfak = $konek->query($sqlSaldoAwalInfak);

    $rowJmlPnrIS             = $hasilJmlPnrIS->fetch_assoc();

    $rowJmlPylIS             = $hasilJmlPylIS->fetch_assoc();

    $rowSaldoAwalDanaInfak   = $hasilSaldoDanaAwalInfak->fetch_assoc(); 

    /** CSR */
    $hasilPnrCSR              = $konek->query($sqlPenerimaanCSR);

    $hasilPylCSR              = $konek->query($sqlPenyaluranCSR);

    $hasilJmlPnrCSR           = $konek->query($sqlJmlPnrCSR);

    $hasilJmlPylCSR           = $konek->query($sqlJmlPylCSR);
    
    $hasilSaldoDanaAwalCSR    = $konek->query($sqlSaldoAwalCSR);

    $rowJmlPnrCSR             = $hasilJmlPnrCSR->fetch_assoc();
    
    $rowJmlPylCSR             = $hasilJmlPylCSR->fetch_assoc();

    $rowSaldoAwalDanaCSR      = $hasilSaldoDanaAwalCSR->fetch_assoc(); 

    /** Amil */
    $hasilPnrAmil              = $konek->query($sqlPenerimaanAmil);

    $hasilPylAmil              = $konek->query($sqlPenyaluranAmil);

    $hasilJmlPnrAmil           = $konek->query($sqlJmlPnrAmil);

    $hasilJmlPylAmil           = $konek->query($sqlJmlPylAmil);
    
    $hasilSaldoDanaAwalAmil    = $konek->query($sqlSaldoAwalAmil);

    $rowJmlPnrAmil             = $hasilJmlPnrAmil->fetch_assoc();
    
    $rowJmlPylAmil             = $hasilJmlPylAmil->fetch_assoc();

    $rowSaldoAwalDanaAmil      = $hasilSaldoDanaAwalAmil->fetch_assoc(); 


    /** Zakat */
    $surplus_defisit            =  intval($rowJmlPnrZakat['jml_pnr_zakat']) - intval($rowJmlPylZakat['jml_pyl_zakat']);

    if($surplus_defisit > 0){

        $sdz = $surplus_defisit;

    }else{

        $sdz = "(".$surplus_defisit.")";

    }

    $saldo_akhir_zakat = intval($rowSaldoAwalDanaZakat['saldo_awal'])+intval($surplus_defisit);

    if ($saldo_akhir_zakat > 0) {
        
        $sdaz = $saldo_akhir_zakat;

    } else {

        $sdaz = "(".$saldo_akhir_zakat.")";

    }

    /** Infak */
    $total_penerimaan = intval($rowJmlPnrIT['jml_pnr_it']) + intval($rowJmlPnrIS['jml_pnr_is']);

    $total_penyaluran = intval($rowJmlPylIT['jml_pyl_it']) + intval($rowJmlPylIS['jml_pyl_is']);

    $surplus_defisit_infak =  intval($total_penerimaan)-intval($total_penyaluran);

    if ($surplus_defisit_infak > 0) {
        
        $sdi = $surplus_defisit_infak;

    }elseif($surplus_defisit_infak == 0 OR is_null($surplus_defisit_infak)){

        $sdi = 0;

    } else {

        $sdi = "(".$surplus_defisit_infak.")";

    }

    $saldo_akhir_infak = intval($rowSaldoAwalDanaInfak['saldo_awal'])+intval($surplus_defisit_infak);

    if ($saldo_akhir_infak > 0) {
        
        $sdai = $saldo_akhir_infak;

    }elseif($saldo_akhir_infak == 0 OR is_null($saldo_akhir_infak)){

        $sdai = 0;

    } else {

        $sdai = "(".$saldo_akhir_infak.")";

    }

    /** Amil */
    $var_pnr_amil = intval($rowJmlPnrAmil['jml_pnr_amil']);

    if(is_null($var_pnr_amil)){
        $var_pnr_amil = 0;
    } else {
        $var_pnr_amil = intval($rowJmlPnrAmil['jml_pnr_amil']);
    }

    $var_pyl_amil = intval($rowJmlPylAmil['jml_pyl_amil']);

    if(is_null($var_pyl_amil)){
        $var_pyl_amil = 0;
    } else {
        $var_pyl_amil = intval($rowJmlPylAmil['jml_pyl_amil']);
    }

    $surplus_defisit_amil =  intval($var_pnr_amil) - intval($var_pyl_amil);

    if($surplus_defisit_amil > 0){

        $sdamil =  $surplus_defisit_amil;

    }else{

        $sdamil =  "(".$surplus_defisit_amil.")";

    }

    $var_sa_amil = intval($rowSaldoAwalDanaAmil['saldo_awal'])+intval($surplus_defisit_amil);

    if(is_null($var_sa_amil)){

        $saldo_akhir_amil = 0;

    }else{

        $saldo_akhir_amil =  $var_sa_amil;

    }
    
     /** CSR */
     $var_pnr_csr = intval($rowJmlPnrCSR['jml_pnr_csr']);

     if(is_null($var_pnr_csr)){
         $var_pnr_csr = 0;
     } else {
         $var_pnr_csr = intval($rowJmlPnrCSR['jml_pnr_csr']);
     }
 
     $var_pyl_csr = intval($rowJmlPylCSR['jml_pyl_csr']);
 
     if(is_null($var_pyl_csr)){
         $var_pyl_csr = 0;
     } else {
         $var_pyl_csr = intval($rowJmlPylCSR['jml_pyl_csr']);
     }
 
     $surplus_defisit_csr =  intval($var_pnr_csr) - intval($var_pyl_csr);
 
     if($surplus_defisit_csr > 0){
 
         $sdcsr =  $surplus_defisit_csr;
 
     }else{
 
         $sdcsr =  "(".$surplus_defisit_csr.")";
 
     }
 
     $var_sa_csr = intval($rowSaldoAwalDanaCSR['saldo_awal'])+intval($surplus_defisit_csr);
 
     if(is_null($var_sa_csr)){
 
         $saldo_akhir_csr = 0;
 
     }else{
 
         $saldo_akhir_csr =  $var_sa_csr;
 
     }    

    $data   = "

                <h4><b>LAPORAN PERUBAHAN DANA - PSAK-109</b></h4>

                <hr style='border:1px solid;'>

                <table cellspacing='15' cellpadding='15'>
                    
                    <tr>
                        <td width='100'><b>LAZISMU</b></td>
                        <td><b>:</b></td>
                        <td contenteditable='true'>&nbsp;".$_SESSION['nama_daerah']."</td>
                    </tr>
                    <tr>
                        <td><b>PERIODE</b></td>
                        <td><b>:</b></td>
                        <td contenteditable='true'>&nbsp;".$periode."</td>
                    </tr>

                </table>
            
                <hr style='border:1px solid;'>

            ";



    $data .= "
        <div class='row'>
            <div class='col-md-6'>
                <!-- DANA ZAKAT -->
                <table class='table table-condensed'>
                    <tr>
                        <td colspan='5'><b>DANA ZAKAT</b></td>
                    </tr>  
                    <tr>
                        <td colspan='5'><b>Penerimaan Zakat</b></td>
                    </tr>";
                    while ($row_pnr_zakat = $hasilPnrZakat->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3' width='55%'>".$row_pnr_zakat['akun']."</td>
                            <td width='2%'>:</td>
                            <td align='right' width='48%'>".number_format($row_pnr_zakat['penerimaan_zakat'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }

                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penerimaan Zakat</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPnrZakat['jml_pnr_zakat'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5'><b>Penyaluran Zakat</b></td>
                    </tr>";
                    while ($row_pyl_zakat = $hasilPylZakat->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3'>".$row_pyl_zakat['akun']."</td>
                            <td>:</td>
                            <td align='right'>".number_format($row_pyl_zakat['penyaluran_zakat'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }
                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penyaluran Zakat</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPylZakat['jml_pyl_zakat'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Surplus (Defisit)</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($sdz, 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Awal</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowSaldoAwalDanaZakat['saldo_awal'], 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Akhir</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($sdaz, 0, ',', '.')."</b></td>
                    </tr>    
                </table>
                
                <hr style='border:0.5px dotted;'>

                <!-- DANA INFAK -->
                <table class='table table-condensed'>
                    <tr>
                        <td colspan='5'><b>DANA INFAK</b></td>
                    </tr>  
                    <tr>
                        <td colspan='5'><b>Penerimaan Infak Terikat</b></td>
                    </tr>";
                    while ($row_pnr_it = $hasilPnrIT->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3' width='55%'>".$row_pnr_it['akun']."</td>
                            <td width='2%'>:</td>
                            <td align='right' width='48%'>".number_format($row_pnr_it['penerimaan_it'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }

                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penerimaan Infak Terikat</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPnrIT['jml_pnr_it'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5'><b>Penerimaan Infak Sodaqoh</b></td>
                    </tr>";
                    while ($row_pnr_is = $hasilPnrIS->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3' width='55%'>".$row_pnr_is['akun']."</td>
                            <td width='2%'>:</td>
                            <td align='right' width='48%'>".number_format($row_pnr_is['penerimaan_is'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }

                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penerimaan Infak</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPnrIS['jml_pnr_is'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>TOTAL PENERIMAAN: </b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($total_penerimaan, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5'><b>Penyaluran Infak Terikat</b></td>
                    </tr>";
                    while ($row_pyl_it = $hasilPylIT->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3'>".$row_pyl_it['akun']."</td>
                            <td>:</td>
                            <td align='right'>".number_format($row_pyl_it['penyaluran_it'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }
                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penyaluran Infak Terikat</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPylIT['jml_pyl_it'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5'><b>Penyaluran Infak Sodaqoh</b></td>
                    </tr>";
                    while ($row_pyl_is = $hasilPylIS->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3'>".$row_pyl_is['akun']."</td>
                            <td>:</td>
                            <td align='right'>".number_format($row_pyl_is['penyaluran_is'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }
                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penyaluran Infak Sodaqoh</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPylIS['jml_pyl_is'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>TOTAL PENYALURAN: </b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($total_penyaluran, 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Surplus (Defisit)</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($surplus_defisit_infak, 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Awal</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowSaldoAwalDanaInfak['saldo_awal'], 0, ',', '.')."</b></td>
                    </tr>  
                    <tr>
                        <td colspan='3'><b>Saldo Akhir</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($sdai, 0, ',', '.')."</b></td>
                    </tr>    
                </table>

                <hr style='border:0.5px dotted;'>

                <!-- DANA CSR -->
                <table class='table table-condensed'>
                    <tr>
                        <td colspan='5'><b>DANA CSR</b></td>
                    </tr>  
                    <tr>
                        <td colspan='5'><b>Penerimaan CSR</b></td>
                    </tr>";
                    while ($row_pnr_csr = $hasilPnrCSR->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3' width='55%'>".$row_pnr_csr['akun']."</td>
                            <td width='2%'>:</td>
                            <td align='right' width='48%'>".number_format($row_pnr_csr['penerimaan_csr'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }

                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penerimaan CSR</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPnrCSR['jml_pnr_csr'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5'><b>Penyaluran CSR</b></td>
                    </tr>";
                    while ($row_pyl_csr = $hasilPylCSR->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3'>".$row_pyl_csr['akun']."</td>
                            <td>:</td>
                            <td align='right'>".number_format($row_pyl_csr['penyaluran_csr'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }
                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penyaluran CSR</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPylCSR['jml_pyl_csr'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Surplus (Defisit)</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($surplus_defisit_csr, 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Awal</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowSaldoAwalDanaCSR['saldo_awal'], 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Akhir</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($saldo_akhir_csr, 0, ',', '.')."</b></td>
                    </tr>    
                </table>

                <hr style='border:0.5px dotted;'>

                <!-- DANA AMIL -->
                <table class='table table-condensed'>
                    <tr>
                        <td colspan='5'><b>DANA AMIL</b></td>
                    </tr>  
                    <tr>
                        <td colspan='5'><b>Penerimaan Amil</b></td>
                    </tr>";
                    while ($row_pnr_amil = $hasilPnrAmil->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3' width='55%'>".$row_pnr_amil['akun']."</td>
                            <td width='2%'>:</td>
                            <td align='right' width='48%'>".number_format($row_pnr_amil['penerimaan_amil'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }

                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Penerimaan Amil</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPnrAmil['jml_pnr_amil'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='5'><b>Beban Amil</b></td>
                    </tr>";
                    while ($row_pyl_amil = $hasilPylAmil->fetch_assoc()) {
                        $data .= "
                        <tr>
                            <td colspan='3'>".$row_pyl_amil['akun']."</td>
                            <td>:</td>
                            <td align='right'>".number_format($row_pyl_amil['penyaluran_amil'], 0, ',', '.')."</td>
                        </tr> 
                        ";
                    }
                    $data .= "<tr>
                        <td colspan='3'><b>Jumlah Pengeluaran Amil</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowJmlPylAmil['jml_pyl_amil'], 0, ',', '.')."</b></td>
                    </tr>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='3'><b>Surplus (Defisit)</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($surplus_defisit_amil, 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Awal</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($rowSaldoAwalDanaAmil['saldo_awal'], 0, ',', '.')."</b></td>
                    </tr> 
                    <tr>
                        <td colspan='3'><b>Saldo Akhir</b></td>
                        <td>:</td>
                        <td align='right'><b>".number_format($saldo_akhir_amil, 0, ',', '.')."</b></td>
                    </tr>    
                </table>
            </div>
        </div>
    ";

    echo $data;

}