<?php
    include "../../../bin/koneksi.php";

    $sql	= "SELECT no_kantor, nama_kantor, phone, pimpinan FROM tm_kantor_layanan WHERE status !='REJECT'";

    $hasil	= $konek->query($sql);

    $response = array();

    $response["data"] = array();

    while($row 	= $hasil->fetch_assoc()){

        $r['no_kantor'] = $row['no_kantor'];

        $r['nama_kantor'] = $row['nama_kantor'];

        $r['phone'] = $row['phone'];

        $r['pimpinan'] = $row['pimpinan'];

        array_push($response["data"], $r);
    }

    echo json_encode($response);