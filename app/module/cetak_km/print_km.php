<?php
session_start();
  if($_SESSION['status']!='LOGIN'){
    header("location:../index.php");
  } else {
?>
<html>
<head>
    <title>Cetak Struk</title>
    <style>
        @font-face{
            font-family: "sqr";
            src: url('../lib/fonts/square721.ttf');
        }

        @media print {
            html, body {
                font-family: "sqr";
            }
        
            /* @page {
              size: 21.49cm 13.97cm;
            }
        
            .logo {
              width: 30%;
            } */
        
        }

    </style>
</head>
    <!-- Call JQuery Library -->
    <script src="bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
    
    <!-- Call DataTables Library -->
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="bower_components/moment/min/moment.min.js" type="text/javascript"></script>
    <script src="bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="bower_components/bootstrap-select/js/bootstrap-select.js" type="text/javascript"></script>
    <script src="bower_components/wnumb/wNumb.js" type="text/javascript"></script>
    <script src="bower_components/Jquery-Price-Format/jquery.priceformat.min.js" type="text/javascript"></script>
    <script src="bower_components/PrintArea/demo/jquery.PrintArea.js" type="text/javascript"></script>

    <!-- Data Tables CSS -->
    <link rel="stylesheet" type="text/css" href="bower_components/datatables.net-dt/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="../bower_components/chosen/chosen.min.css"> -->

    <!-- Call Custom Library -->
    <script src="cetak_km/print_km.js" type="text/javascript"></script>
<body>
<?php
    $npwz = $_GET['npwz'];
?>
<br>
    <div id="cetak">
        <div class="row col-md-1"></div>
            <div class="row col-md-10">
                <table width="100%">
                    <tr>
                        <td>
                            <center><b>SURAT KETERANGAN</b></center>
                            <input type="hidden" id="npwz" value="<?php echo $npwz; ?>">
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td>
                            Yang bertanda tangan dibawah ini:
                        </td>
                    </tr>
                </table>
                <table width="100%" style="border:none;">
                    <tr style="border:none;">
                        <td width="125">Nama</td>
                        <td width="5">:&nbsp;</td>
                        <td contenteditable="true">Sabar Waluyo <td>
                    </tr>
                    <tr style="border:none;">
                        <td width="125">Alamat</td>
                        <td width="5">:&nbsp;</td>
                        <td contenteditable="true">Karanglo Rt.03 / 02 Kec. Cilongok Kab. Banyumas<td>
                    </tr>
                    <tr style="border:none;">
                        <td width="125">Jabatan</td>
                        <td width="5">:&nbsp;</td>
                        <td contenteditable="true">Direktur<td>
                    </tr>
                </table>
                <br>
                <table width="100%">
                    <tr>
                        <td rowspan="3">Menerangkan bahwa : </td>
                    </tr>
                </table>
                <table width="100%" style="border:none;">
                    <tr style="border:none;">
                        <td width="125">Nama</td>
                        <td width="5">:</td>
                        <td contenteditable="true" id="nama_muzaki"><td>
                    </tr>
                    <tr style="border:none;">
                        <td width="125">NPWZ</td>
                        <td width="5">:</td>
                        <td contenteditable="true"><?php echo $npwz; ?><td>
                    </tr>
                    <tr style="border:none;">
                        <td width="125">Tempat, Tgl Lahir</td>
                        <td width="5">:</td>
                        <td contenteditable="true">---<td>
                    </tr>
                    <tr style="border:none;">
                        <td width="125">Pekerjaan</td>
                        <td width="5">:</td>
                        <td contenteditable="true">---<td>
                    </tr>
                </table>
                <br>
                <p align="justify">Adalah Muzaki dilembaga Amil Zakat Infaq dan Shadaqah Muhammadiyah Banyumas sejak tahun <span contenteditable="true">---</span> sampai dengan sekarang.</p>
                <p align="justify">Berikut kami sampaikan bukti setoran zakatnya pada tahun <span contenteditable="true">---</span> sebagai berikut :</p>
                <br>
                <table>
                    <tr>
                        <td id="zakat" valign="top">
                            <table width="100%" border="0" class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <td colspan="2">Zakat</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td align="right">Jumlah</td>
                                    </tr>
                                </thead>
                                <tbody id="data-zakat"></tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total: </td>
                                        <td align="right"><span id="total_zakat"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                        <td>&nbsp;</td>
                        <td id="infaq" valign="top">
                            <table width="100%" border="0" class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <td colspan="2">Infaq</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td align="right">Jumlah</td>
                                    </tr>
                                </thead>
                                <tbody id="data-infaq"></tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total: </td>
                                        <td align="right"><span id="total_infaq"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <table border="0">
                    <tr>
                        <td>Demikian surat keterangan ini dibuat dengan sebenarnya.</td>
                    </tr>
                    <tr>
                        <td>Purwokerto, <span contenteditable="true">-----------</span></td>
                    </tr>
                    <tr>
                        <td>Direktur</td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td><span contenteditable="true">Sabar Waluyo, SE</span></td>
                    </tr>
                    <tr>
                        <td><span contenteditable="true">NBM.1146183</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div align="center" class="row col-md-1">
        <button class="btn btn-warning" id="btn-cetak">Cetak</button>
    </div>

</body>
</html>
<?php } ?>