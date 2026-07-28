<style>
    .inEdit {
        background-color: #FFFFFF;
        border: 2px solid #000;
        border-radius: 5px;
        padding: 25;
    }
    .listwo_keluar th td{
        align-items: center;
        text-align: center;
        align-content: center;
        font-size: large;
    }

</style>
<table class="table table-striped  table-bordered table-hover nowrap listwo_keluar" id="listwo_keluar">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%">No Part</th>
            <th width="15%">Nama Barang</th>
            <th width="5%">Satuan</th>
            <th width="5%">Hrg Satuan</th>
            <th width="5%">Jumlah</th>
            <!-- kalau ada permintaan saja 
             <th width="5%">Aktual</th>
            <th width="5%">Sisa</th> -->
            <th width="5%">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $idlokasi = $this->session->userdata['lokasi'];
        $no = 1;
        foreach ($dataPo as $s) {
            if ($idlokasi == 'Cibitung') {
                $stok = $s->stok_cbt;
                $harga = $s->hrg_net_cbt;
                $harga = $s->price_list_cbt;
            }
            if ($idlokasi == 'Jakarta') {
                $stok = $s->stok_jkt;
                $harga = $s->hrg_net_jkt;
                $harga = $s->price_list_jkt;
            }
            if ($idlokasi == 'Surabaya') {
                $stok = $s->stok_sby;
                $harga = $s->hrg_net_sby;
                $harga = $s->price_list_sby;
            }

        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $s->no_part; ?></td>
                <td><?php echo $s->nama_part; ?></td>
                <td><?php echo $s->satuan; ?></td>
                <td align="right"><?php echo number_format($s->harga_net) ?></td>
                <td align="center"><?php echo $s->jumlah; ?></td>
                <!-- kalau ada permintaan saja
                <td class="inEdit"><input type="number" name="jml_keluar[]" id="jml_keluar[]"
                        value="<?php echo $s->jumlah; ?>"
                        onkeypress="saveData(event,'<?php echo $s->id_detail; ?>','<?php echo $s->jumlah; ?>',$(this).val() )"
                        class="form-control col-sm-12">
                </td>
                <td align="center"><?php echo $s->sisa; ?></td>
                -->
                    <input type="hidden" name="jumlah[]" id="jumlah[]" value="<?php echo $s->jumlah; ?>">
                    <input type="hidden" name="harga[]" id="harga[]" value="<?php echo $s->harga; ?>">
                    <input type="hidden" name="harga_penawaran[]" id="harga_penawaran[]" value="<?php echo $s->harga_net; ?>">
                    <input type="hidden" name="no_part[]" id="no_part[]" value="<?php echo $s->no_part; ?>">
                    <input type="hidden" name="nama_part[]" id="nama_part[]" value="<?php echo $s->nama_part; ?>">
                    <input type="hidden" name="satuan[]" id="satuan[]" value="<?php echo $s->satuan; ?>">
                    <input type="hidden" name="stok[]" id="stok[]" value="<?php echo $stok ?>">
                    <input type="hidden" name="nik" id="nik" value="<?php echo $s->nik; ?>">
                    <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>">
                    <input type="hidden" name="petugas" id="petugas" value="<?php echo $s->petugas; ?>">
                    <input type="hidden" name="kode_pr" id="kode_pr" value="<?php echo $s->kode_pr; ?>">
                <td align="right"><?php echo number_format($s->jml_masuk * $s->harga_net); ?></td>
            </tr>
        <?php
            $no++;
        }
        ?>
    </tbody>
    <tfoot></tfoot>
</table>
<script language="javascript">
    var MyTable = $('#listwo_keluar').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": false,
        "info": true
    });
    function saveData(e, id, qty_awal, jml_keluar) {
        var wo_no = document.getElementById("wo_no").value;
        var nik = document.getElementById("nik").value;
        var petugas = document.getElementById("petugas").value;
        var kode_pr = document.getElementById("kode_pr").value;
        if (e.keyCode === 13) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('PartKeluarWo/updatePart') ?>",
                data: {
                    'id': id,
                    'qty_awal': qty_awal,
                    'jml_keluar': jml_keluar,
                },

                success: function(response) {
                    showPart(wo_no,nik,petugas,kode_pr);
                }
            });
        }
    }

    function delData(e, id, sisa) {
        var id_po = document.getElementById("id_po").value;
        var no_po = document.getElementById("no_po").value;
        var status = document.getElementById("status").value;
        var kode_sup = document.getElementById("kode_sup").value;
        var supplier = document.getElementById("supplier").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Part_masuk/deletepartDetail') ?>",
            data: {
                'id': id,
                'sisa': sisa,
            },

            success: function(response) {
                showPart(id_po, no_po, kode_sup, supplier, status);
            }
        });
    }
</script>