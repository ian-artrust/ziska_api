<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_rekening    = $_POST['no_rekening'];

    $kode_daerah    = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
        
        $sql	= "SELECT 
                        id_judtl,
                        kode_daerah,
                        kode_akun,
                        kode_sub_kat_akun,
                        ref_number,
                        no_rekening,
                        status,
                        no_jurnal,
                        periode,
                        tgl_jurnal,
                        keterangan,
                        debit,
                        kredit,
                        @saldo_awal_bank:=@saldo_awal_bank+debit-kredit as saldo 
                    FROM view_323a x, (SELECT @saldo_awal_bank:=0) y 
                    WHERE x.kode_sub_kat_akun='102' 
                    AND x.no_rekening='$no_rekening'
                    AND x.status!='REJECT'";

    } else {
        
        $sql	= "SELECT 
                        id_judtl,
                        kode_daerah,
                        kode_akun,
                        kode_sub_kat_akun,
                        ref_number,
                        no_rekening,
                        status,
                        no_jurnal,
                        periode,
                        tgl_jurnal,
                        keterangan,
                        debit,
                        kredit,
                        @saldo_awal_bank:=@saldo_awal_bank+debit-kredit as saldo 
                        FROM view_323a x, (SELECT @saldo_awal_bank:=0) y 
                        WHERE x.kode_sub_kat_akun='102' 
                        AND x.no_rekening='$no_rekening'
                        AND x.kode_daerah='$kode_daerah' 
                        AND x.status!='REJECT'";

    }
    
    $result	= $konek->query($sql);

    $data   = '';

    while($row = $result->fetch_assoc()){

        $data .="
            <tr>
                <td>". $row['no_jurnal'] ."</td>

                <td>". $row['periode'] ."</td>
                
                <td>". $row['tgl_jurnal'] ."</td>

                <td>". $row['keterangan'] ."</td>

                <td>". number_format($row['debit'], 0, ',', '.') ."</td>

                <td>". number_format($row['kredit'], 0, ',', '.') ."</td>

                <td>". number_format($row['saldo'], 0, ',', '.') ."</td>

            </tr>
        ";

    }

    echo $data;
}