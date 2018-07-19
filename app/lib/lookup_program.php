<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    
    include "../../bin/koneksi.php";

    $kode_daerah = $_SESSION['kode_daerah'];

    if ($kode_daerah=='') {
    
        $sql	= "SELECT 

                    kode_prg_pnr,

                    program, 

                    kategori, 

                    kode_kategori, 

                    akun_debit, 

                    akun_debit_bank,

                    akun_kredit,

                    akun_kredit_bank                      

                FROM view_411";

    } else {
        
        $sql	= "SELECT 

                    kode_prg_pnr,

                    program, 

                    kategori, 

                    kode_kategori, 

                    akun_debit, 

                    akun_debit_bank,

                    akun_kredit,

                    akun_kredit_bank   

                FROM view_411 WHERE kode_daerah='$kode_daerah' AND status='Aktif'";

    }

    $hasil	= $konek->query($sql);
    
    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['kode_prg_pnr'] = $row['kode_prg_pnr'];

        $r['program'] = $row['program'];

        $r['kategori'] = $row['kategori'];

        $r['kode_kategori'] = $row['kode_kategori'];

        $r['akun_debit'] = $row['akun_debit'];

        $r['akun_debit_bank'] = $row['akun_debit_bank'];

        $r['akun_kredit'] = $row['akun_kredit'];

        $r['akun_kredit_bank'] = $row['akun_kredit_bank'];

        array_push($response["data"], $r);

    }

    echo json_encode($response);

}