<?php

    include "../koneksi.php";

    $npwz = $_GET['npwz'];

    $sqlFind 	= "SELECT 
    
                        SUM(jml_donasi) AS total 
    
                    FROM view_donasi 
                    
                    WHERE npwz = '$npwz' AND kategori='Zakat' AND status_hdr='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);