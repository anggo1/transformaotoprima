<div class="row">
    <div class="col-12 ">
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <?php $idlevel = $this->session->userdata['id_level']; ?>
                <?php if (($idlevel==1) or ($idlevel==7) or ($idlevel==12)){ ?>
                <div class="row">
                    <div class="col-lg-4 col-4">
                        <!-- small box -->
                        <div class="small-box bg-indigo">
                            <div class="inner">
                                <h3>Display Bay</h3>

                                <p>Proses Pekerjaan Bus</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-tv"></i>
                            </div>
                            <a href="<?php echo base_url('Display'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-4">
                        <!-- small box -->
                        <div class="small-box bg-lightblue">
                            <div class="inner">
                                <h3><?php echo $jml_bus ?></h3>

                                <p>Total Kendaraan</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-bus"></i>
                            </div>
                            <a href="<?php echo base_url('Body'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-4">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $jml_antri ?></h3>

                                <p>Antrian Perbaikan Bus</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-settings"></i>
                            </div>
                            <a href="<?php echo base_url('BusMasuk'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-4">
                        <!-- small box -->
                        <div class="small-box bg-lime">
                            <div class="inner">
                                <h3><?php echo $jml_spk ?></h3>

                                <p>Total SPK Aktif</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                            <a href="<?php echo base_url('BusPk'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-4">
                        <!-- small box -->
                        <div class="small-box bg-maroon">
                            <div class="inner">
                                <h3><?php echo $jml_pk ?></h3>

                                <p>Total PK Aktif</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-check"></i>
                            </div>
                            <a href="<?php echo base_url('ProsesPk'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-4">
                        <!-- small box -->
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3><?php echo $jml_selesai ?></h3>

                                <p>Bus Siap Keluar</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-thumbs-up"></i>
                            </div>
                            <a href="<?php echo base_url('BusKeluar'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- ./col -->

                <?php } if (($idlevel==1) or ($idlevel==9)){ ?>
                <!-- /.col-md-6 -->

                <div class="row">
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Grafik Barang Keluar Bulan Kemarin</h3>
                                    <a href="javascript:void(0);">View Report</a>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="position-relative mb-4">
                                    <canvas id="sales-chart" height="200"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Grafik Barang Keluar Bulan ini</h3>
                                    <a href="javascript:void(0);">View Report</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- /.d-flex -->

                                <div class="position-relative mb-4">
                                    <canvas id="visitors-chart" height="200"></canvas>
                                </div>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.row -->
                    <div class="col-lg-2 col-4">
                        <!-- small box -->
                        <div class="small-box bg-indigo">
                            <div class="inner">
                                <h3><?php echo $jml_barang ?></h3>

                                <p>Material & Sparepart</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-tv"></i>
                            </div>
                            <a href="<?php echo base_url('Sparepart'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-4">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>P O</h3>

                                <p>Purchase Order</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-coins"></i>
                            </div>
                            <a href="<?php echo base_url('PurchaseOrder'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-4">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>Part Masuk</h3>

                                <p>Barang Masuk Dengan PO</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-signature"></i>
                            </div>
                            <a href="<?php echo base_url('Part_masuk'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-4">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>Part Masuk</h3>

                                <p>Barang Masuk Tanpa PO</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-signature"></i>
                            </div>
                            <a href="<?php echo base_url('Part_masuk_npo'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-4">
                        <!-- small box -->
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>Part Keluar</h3>

                                <p>Barang Keluar Tanpa PK</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-upload"></i>
                            </div>
                            <a href="<?php echo base_url('Part_keluar'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-4">
                        <!-- small box -->
                        <div class="small-box bg-lightblue">
                            <div class="inner">
                                <h3>Part PO</h3>

                                <p>Part Keluar</p>
                            </div>
                            <div class="icon">
                                <i class="	fa fa-external-link-alt"></i>
                            </div>
                            <a href="<?php echo base_url('PartPk'); ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- ./col -->
                    <!-- ./col -->
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
//untuk Chart
$(function() {
    'use strict'

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    var $salesChart = $('#sales-chart')
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart($salesChart, {
        type: 'bar',
        data: {
            //$datanya=""; if(empty($data_asal)) {$datanya='Non SPK';}else{$datanya=$data_asal;} 
            labels: <?php echo $data_asal; ?>,
            datasets: [{
                backgroundColor: <?php  echo $warna_asal; ?>,
                borderColor: <?php  echo $warna_asal; ?>,
                data: <?php  echo $jumlah_asal; ?>
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect
            },
            hover: {
                mode: mode,
                intersect: intersect
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    display: true,
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: $.extend({
                        beginAtZero: true,

                        // Include a dollar sign in the ticks
                        callback: function(value) {
                            if (value >= 1000) {
                                value /= 1000
                                value += 'k'
                            }

                            return '' + value
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            }
        }
    })

    var $visitorsChart = $('#visitors-chart')
    // eslint-disable-next-line no-unused-vars
    var visitorsChart = new Chart($visitorsChart, {
        data: {
            labels: <?php  echo $data_barang; ?>,
            datasets: [{
                type: 'line',
                backgroundColor: <?php  echo $warna_barang; ?>,
                borderColor: <?php  echo $warna_barang; ?>,
                data: <?php  echo $jumlah_barang; ?>,
                //backgroundColor: 'transparent',
                // borderColor: '#007bff',
                pointBorderColor: '#007bff',
                pointBackgroundColor: <?php  echo $warna_barang; ?>,
                fill: false
                // pointHoverBackgroundColor: '#007bff',
                // pointHoverBorderColor    : '#007bff'
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect
            },
            hover: {
                mode: mode,
                intersect: intersect
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    // display: false,
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: $.extend({
                        beginAtZero: true,
                        suggestedMax: 200
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            }
        }
    })
})
</script>