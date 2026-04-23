<?php
			if (!empty($dataCus)) {
			foreach ($dataCus as $c)
                foreach ($dataSa as $s)  {{}}} ?>
<div class="card-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header card-dark card-outline">
                <h3 class="card-title"><i class="ion-outlet ion-lg text-blue"></i> &nbsp;
                    Status Proses Work Order</h3>
            </div>
            <table width="100%" cellpadding="1" cellspacing="0" class="data1" style="border-bottom:1px solid #8d8989;">
                <thead>
                <tbody>
                    <tr>
                        <th width="15%">Customer name</th>
                        <th width="1%">: </th>
                        <th width="20%"><?php echo $c->nama_cus; ?></th>
                        <th width="20%">&nbsp;</th>
                        <th colspan="3">PT Transforma Oto Prima</th>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>&nbsp;</th>
                        <th><?php echo $c->alamat; ?></th>
                        <th>&nbsp;</th>
                        <th colspan="3">Authorized Dealer of Mercedes-Benz Truck &amp; Bus in Indonesia</th>
                    </tr>
                    <tr>
                        <th>City</th>
                        <th>&nbsp;</th>
                        <th><?php echo $c->kota; ?></th>
                        <th>&nbsp;</th>
                        <th colspan="3">&nbsp;</th>
                    </tr>
                    <tr>
                        <th>Telp</th>
                        <th>&nbsp;</th>
                        <th><?php echo $c->no_tlp; ?></th>
                        <th>&nbsp;</th>
                        <th width="20%">Order No</th>
                        <th width="11">:</th>
                        <th width="25%"><?php echo $s->wo_no; ?></th>
                    </tr>
                    <tr>
                        <th>Tax Code</th>
                        <th>:</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>Date</th>
                        <th>:</th>
                        <th><?php echo tglIndoSedang($s->date_open_wo); ?></th>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                </tbody>
            </table>
                <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $s->wo_no; ?>" class="form-control"
                    placeholder="Operation">
        <div id="data-proses-start"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>