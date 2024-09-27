<script>
document.getElementById("btnPrint").onclick = function() {
    printElement(document.getElementById("printThis"));
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);

    var $printSection = document.getElementById("printSection");

    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }

    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
}
</script>
<style>
@media screen {
    #printSection {
        display: none;
    }
}

@media print {
    body * {
        visibility: hidden;
    }

    #printSection,
    #printSection * {
        visibility: visible;
    }

    #printSection {
        position: absolute;
        left: 0;
        top: 0;
    }
}


p,
td,
th {
    font: 2 Verdana, Arial, Helvetica, sans-serif;

}

.datatable2 {
    border-collapse: collapse;
    font: bold;
}

.datatable td,
th {
    padding: 0px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
}

.datatable1 th {
  padding: 0px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    font: bold;
    text-align: center;
    border-top: 1px solid #000;
    border-bottom: double;
    height: 10px;
}

.datatable2 th {
    border: 0px solid #000;
    height: 10px;
} 

.datatable3 td {
    padding: 2px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
}

.datatable3 th {
    border: 0px solid #000;
    align-content: left;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: left;
}
.table-dalam th {
    border: 1px solid #000;
    font-display: block;
    align-content: center;
    font-weight: bolder;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    text-align: center;
}
.table-kwitansi td {
    border: 0px solid #000;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
    text-align: left;
    height: 5px;
    padding-top: 0px;
    padding-bottom: 0px;
}
.table-dalam td {
    border: 1px solid #000;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
    text-align: left;
    height: 5px;
}
.table-ttd th {
    padding: 1px;
    border: 0px solid #000;
    font-display: block;
    align-content: center;
    font-weight: bolder;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
    text-align: center;
    height: 5px;
}
.table-ttd td {
    padding: 1px;
    border: 0px solid #000;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    }
.table-atas {
    border-collapse: collapse;
    font: bold;
    float: right;
}

.table-atas td {
    padding: 0px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font: bold;
}

.table-atas th {
    border: 2px solid #000;
    font: bold;
    font-weight: normal;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
}

.text1 {
    font: bold;
    font-weight: normal;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 8px;
    padding: 1px 1px 1px 1px;
    padding-bottom: 0px;
    width: auto;
}

.text2 {
    font: bold;
    font-weight: bold;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 11px;
    align-content: center;
    text-align: center;
    padding: 1px 1px 1px 1px;
    width: auto;
}

.under {
    text-decoration: underline;
}

#A4 {
    background-color: #FFFFFF;
    left: 1px;
    right: 1px;
    height: 5.51in;
    /*Ukuran Panjang Kertas */
    width: 8.50in;
    /*Ukuran Lebar Kertas */
    margin: 1px solid #FFFFFF;

    font-family: Georgia, "Times New Roman", Times, serif;
}

.dbl-border {
    border: 3px double black;
    padding: 10px, 10px, 10px, 10px;
}
</style>

<div id="printThis">

    <div class="modal-body">
        <tbody>
            <tr>
              <td width="100%" style="padding: 15px;">
                    <?php
	foreach ($dataPo as $k) {
  }
    $lokasi = $this->session->userdata['lokasi'];
	$apl1 = $this->db->get("aplikasi where lokasi='".$lokasi."'")->row();
    $tgl_sekarang =date("Y-m-d");
	?>
                    <table width="100%" border="0" cellpadding="5" cellspacing="0">
                        <tr align="center">
                            <th width="99%" align="center">
                                <img src="<?php echo base_url(); ?>assets\dist\img\logo_mercedes.png" width="25%">
                            </th>
                        </tr>

                    </table>
                    <table width="100%" border="0" cellpadding="1" style="font-size: 14px;" cellspacing="0"
                        class="datatable">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    <div align="left"></div>
                                </th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"></div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="2">&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->nama_owner; ?></div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th width="185">&nbsp;</th>
                                <th width="489">&nbsp;</th>
                                <th width="138">&nbsp;</th>
                                <th width="513">
                                    <div align="left"><?php echo  $apl1->status; ?></div>
                                </th>
                                <th width="71">&nbsp;</th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->alamat; ?></div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th height="20">&nbsp;</th>
                                <th height="20">&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->kota.' '.$apl1->kode_pos; ?> Indonesia</div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th height="20">&nbsp;</th>
                                <th height="20">&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <div align="left"><?php echo  $apl1->tlp; ?></div>
                                </th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                    </table>

                    <table width="100%" cellpadding="1" cellspacing="0" class="datatable1">
                        <tr>
                            <th height="37">SURAT PESANAN KENDARAAN</th>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="1" style="
  border-bottom: 1px solid black;border-top: 1px solid black;" cellspacing="0" class="datatable2">
                        <tr>
                            <th width="13%" height="37">
                                <font size="-2">Nama Perusahaan</font>
                            </th>
                            <th width="42%">&nbsp;</th>
                            <th width="13%">
                                <font size="-2">Nama BPKB/STNK</font>
                            </th>
                            <th width="32%">&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Alamat</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">No KTP/No TDP</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">Alamat</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">No Telepone/Fax</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Faktur Pajak</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">No. NPWP</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">Plat Kendaraan</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Alamat NPWP</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>
                                <font size="-2">Type Body</font>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                                <font size="-2">Contact Person</font>
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>
                            <font size="-2">No. Telepon/HP.</font></th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="3" cellspacing="0" class="datatable3">
                            <tr>
                                <th height="31" colspan="4">KETERANGAN UNIT dan ESTIMASI HARGA</th>
                                <th colspan="3">SYARAT dan KETENTUAN</th>
                            </tr>
                            <?php
        $no = 0;
        foreach ($dataPo as $d) : $no++;
          ?>
                            <tr>
                                <td width="9%">Jumlah Unit</td>
                                <td width="1%">:</td>
                                <td width="30%">&nbsp;</td>
                                <td width="9%">&nbsp;</td>
                                <td width="1%">&nbsp;</td>
                                <td width="1%">1.</td>
                                <td width="49%"> Harga yang tercantum dalam surat pesanan ini tidak mengikat &amp; tidak berlaku jika terjadi force majour</td>
                            </tr>

                            <tr>
                                <td>Kategori</td>
                                <td>:</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>2.</td>
                                <td>Surat Pesanan ini dianggap SAH, apabila :</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>:</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>a.  Telah di tandatangani oleh PEMESAN</td>
                            </tr>
                            <tr>
                                <td>Warna/Tahun</td>
                                <td>:</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>b. Telah ditandatangani oleh PEJABAT CABANG</td>
                            </tr>
                            <tr>
                                <td>Harga Off The Road</td>
                                <td>:</td>
                                <td>&nbsp;</td>
                                <td>/unit</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>c. Uang muka / DP 20% telah dibayarkan LUNAS oleh PEMESAN</td>
                            </tr>
                            <tr>
                              <td>Biaya BBN</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit</td>
                              <td>&nbsp;</td>
                              <td>3.</td>
                              <td> Pembayaran dengan Cek/biyel Giro / Transfer harus diatasnamakan PT. Transforma</td>
                            </tr>
                            <tr>
                              <td>Harga Nett On The Road</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>Oto Prima dan dianggap sah apabila telah diterima direkening PT. Transforma Oto Prima</td>
                            </tr>
                            <tr>
                              <td>PErlengkapan Tambahan</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>4.</td>
                              <td>PT. Transforma Oto Prima tidak menerima pembayaran cash/tunai</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit</td>
                              <td>&nbsp;</td>
                              <td>5.</td>
                              <td>Jika pesanan DIBATALKAN oleh PEMESAN dengan alasan / dalil apapun, maka surat pemesanan ini</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>harus dikembalikan dan uang muka yang sudah diterima seluruhnya menjadi HAK PT. Transforma Oto Prima</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit</td>
                              <td>&nbsp;</td>
                              <td>6.</td>
                              <td>Faktur kendaraan diterbitkan setelah semua pembayaran lunas dan ada pemohon tertulis dan</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit +</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td> PEMESAN dan hanya berlaku 2(dua)  minggu dari tanggal terbit. Resiko dan biaya yang timbul</td>
                            </tr>
                            <tr>
                              <td>Harga Jual per Unit</td>
                              <td>:</td>
                              <td>&nbsp;</td>
                              <td>/unit</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td> sehubungan dengan faktur kendaraan yang melampaui masa berlaku akan menjadi tanggung  jawab PEMESAN.</td>
                            </tr>
                            <tr>
                              <td>TPTAL HARGA JUAL</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>7.</td>
                              <td>Apabila pembatal dari pihak Dealer dikarenakan unit yang dipesan oleh PEMESAN tidak</td>
                            </tr>
                            <tr>
                                <td colspan="4" rowspan="9">
                                 <table width="100%" border="1" cellpadding="3" cellspacing="0" class="table-dalam">
                                  <tbody>
                                    <tr>
                                      <th width="6%">No</th>
                                      <th width="94%">Keterangan</th>
                                    </tr>
                                    <tr>
                                      <td>1</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>5</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>6</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>7</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>8</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>9</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>10</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                  </tbody>
                                </table>  
                              </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>tersedia dalam jangka waktu yang sudah disetujui oleh kedua belah pihak. Maka Dealer wajib</td>
                            </tr>
                            <?php endforeach ?>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td> mengembalikan uang tanda jadi yang sudah masuk ke rekening PT. Transforma Oto Prima.</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>8.</td>
                              <td>Apabila unit yang di pesan oleh PEMESAN sudah terssedia, maka PEMESAN berkewajiban untuk</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>sepenuhnya membayar sisa pembayaran yang sudah di informasikan dalam waktu 2 (dua) minggu</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>jika pembayaran belum kami terima dalam waktu 2 (dua) minggu Dealer berhak membatalkan SPK</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>tanpa mengembalikan uang yang sudah masuk di rekening PT. Transforma Oto Prima</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>9.</td>
                              <td>Biaya BBN tidak mengikat (termasuk jika ada biaya yang timbul aikbat kekurangan dokumen</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>PEMESAN saat proses pengurusan) dan biaya pajak progresif menjadi tanggung jawab PEMESAN</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td colspan="2" style="border: 1px solid #000; font-weight: bolder; ">PEMESAN berkewajiban membayar biaya / pajak kendaraan dalam hal terdapat penambahan biaya / pajak, karena berlakunya ketentuan Perundangan tentang pajak progresif atas pemilikan dan pendaftaran kendaraan bermotor atau karena adanya perubahan tarif pajak yang berlaku pada saat pendaftaran BBN</td>
                            </tr>

                    </table>
                <table width="100%" padding="5" border="0" cellpadding="5" cellspacing="0">
                        <tr>
                            <td colspan="4">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom: 2px dashed black;" class="table-ttd">
                        <tr>
                          <td colspan="3" style="text-decoration: underline; font-weight: bold;">Cara Pembayaran</td>
                          <td>&nbsp;</td>
                          <td style="text-align: center;">Wiraniaga</td>
                          <td style="text-align: center;">Pemesan</td>
                          <td style="text-align: center;">Supervisor</td>
                          <td style="text-align: center;">Kepala Cabang</td>
                        </tr>
                        <tr>
                          <td style="border: 2px #000 solid; margin-bottom: 12px;margin-top: 12px;">&nbsp;</td>
                          <td>Tunai / TOP</td>
                          <td>: _______________________ Hari</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="border: 2px #000 solid; margin-bottom: 15px;margin-top: 15px;">&nbsp;</td>
                          <td>Kredit Via</td>
                          <td>: _______________________</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>Contact Peson</td>
                          <td>: _______________________ </td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>Telepon / HP</td>
                          <td>: _______________________ </td>
                          <td>&nbsp;</td>
                          <td style="text-align: center;">( ......................... )</td>
                          <td style="text-align: center;">( ......................... )</td>
                          <td style="text-align: center;">( ......................... )</td>
                          <td style="text-align: center;">( ......................... )</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td style="text-align: center;">Nama Jelas</td>
                          <td style="text-align: center;">Nama Jelas</td>
                          <td style="text-align: center;">Nama Jelas</td>
                          <td style="text-align: center;">Nama Jelas</td>
                        </tr>
                        <tr>
                            <td width="3%">&nbsp;</td>
                            <td width="11%">&nbsp;</td>
                            <td width="35%">&nbsp;</td>
                            <td width="2%">&nbsp;</td>
                            <td width="12%">Tgl</td>
                            <td width="12%">Tgl</td>
                            <td width="13%">Tgl</td>
                            <td width="12%">Tgl</td>
                        </tr>
                    </table></td>
                      </tr>
                </table>
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="table-kwitansi">
                      <tr>
                          <td colspan="5">TANDA TERIMA JAMINAN PEMBELIAN ( SEMENTARA )</td>
                          <td colspan="2" style="font-weight: bolder;text-align: center; font-size: 10px;"><?php echo  $apl1->nama_owner; ?></td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">No SPK</td>
                        <td width="5%">:</td>
                        <td width="12%">&nbsp;</td>
                        <td width="17%">&nbsp;</td>
                        <td colspan="2" style="font-size: 9px;">Head Offce : Jl. D.I Panjaitan No. 12 Jakarta Timur 13410, Indonesia . Phone : +62-21 8591377 Fax : +62-21 8561867</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">Tanggal</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="2" style="font-size: 9px;">Branch Office : Jl. Tambak Osowilangon 23 Surabaya, Indonesia. Phone : +62-31 99340505 / 0811 1560 073 </td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">Nama Pemesan</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="2" rowspan="4" style="border: 2px #000 dashed; margin-bottom: 12px;margin-top: 12px; text-align: center"><strong style="font-style: italic;">PERHATIAN ! Demi Keamanan pelanggan, Mohon diperhatikan hal sebagai berikut:</strong><br>
                        &quot;Pembayaran dengan Bilyet Giro atau Cek harus diatasnamakan PT. Transforma Oto Prima <br>
                        No Rekening Bank BCA Kelapa Gading 065-8775777 / Bank Danamon 008800202395 atas nama PT.Transforma Oto Prima<br>
                        *Tukarkan segera Tanda Terima Jaminan  Pembelian (sementara)ini dengan KWITANSI ASLI selambatnya 5 hari kerja dari tanggal di terima ini.
                        </td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">Alamat</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">Sebesar</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">Terbilang</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Yang Menyerahkan</td>
                        <td>Yang Menerima</td>
                      </tr>
                      <tr align="center">
                        <td colspan="2">&nbsp;</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td width="33%">&nbsp;</td>
                        <td width="25%">&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td colspan="4">Dengan Perincian Pembayaran :</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td width="2%" style="border: 2px #000 solid;">&nbsp;</td>
                        <td colspan="3"> Tunai</td>
                        <td>:</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td style="border: 2px #000 solid;">&nbsp;</td>
                        <td colspan="3"> Transfer Via Bank / TglTransfer Via Bank / Tgl</td>
                        <td>:</td>
                        <td><span style="text-align: center;">( ......................... )</span></td>
                        <td><span style="text-align: center;">( ......................... )</span></td>
                      </tr>
                      <tr align="center">
                        <td style="border: 2px #000 solid;">&nbsp;</td>
                        <td colspan="3"> BG/Cek Bank/No/Tgl</td>
                        <td>:</td>
                        <td>                              Nama Jelas</td>
                        <td>Nama Jelas</td>
                      </tr>
                      <tr align="center">
                          <td colspan="2">&nbsp;</td>
                          <td colspan="2">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                  </table>
                  <!--</table>-->

    </div>



</div>
<div class="modal-footer justify-content-between">
    <button type="button" id="btnPrint" class="btn btn-success"><span class="fa fa-print"></span>&nbsp;&nbsp; C E T A K
    </button>
    <button class="btn btn-danger" id="tutup" onClick="window.location.assign("
        <?php echo base_url(); ?>/Transaksi/Pengiriman");" data-dismiss="modal"><span
            class="fa fa-close"></span>&nbsp;&nbsp; T U T U P</button>
</div>