<style>
    vakses.dataTable td {
        padding: 2px;
        font-size: 42px;
    }
    
</style>
<div class="box ">
    <table class="table table-bordered table-hover nowrap vakses" id="vakses">
        <thead>
            <tr>
                <th align="left">Menu</th>
                <th>View</th>
                <th>Add</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Print</th>
                <th>Upload</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_submenu as $row) {?>
            <tr>
                <td align="left" width="40%" style="font-size: larger;"><?=$row->nama_menu.' -> '.$row->nama_submenu?></td>
                <td width="10%" >
                    <?php if ($row->view_level=="Y") {?>
                    <div onClick="vchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/check.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php }else{ ?>
                    <div onClick="vunchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/uncheck.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php } ?>
                </td>
                <td width="10%">
                    <?php if ($row->add_level=="Y") {?>
                    <div onClick="addchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/check.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php }else{ ?>
                    <div onClick="addunchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/uncheck.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php } ?>
                </td>
                <td width="10%">
                    <?php if ($row->edit_level=="Y") {?>
                    <div onClick="editchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/check.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php }else{ ?>
                    <div onClick="editunchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/uncheck.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php } ?>
                </td>
                <td width="10%">
                    <?php if ($row->delete_level=="Y") {?>
                    <div onClick="delchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/check.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php }else{ ?>
                    <div onClick="delunchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/uncheck.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php } ?>
                </td>
                <td width="10%">
                    <?php if ($row->print_level=="Y") {?>
                    <div onClick="printchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/check.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php }else{?>
                    <div onClick="printunchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/uncheck.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php } ?>
                </td>
                <td width="10%">
                    <?php if ($row->upload_level=="Y") {?>
                    <div onClick="uplchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/check.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php }else{ ?>
                    <div onClick="uplunchecked('<?=$row->id?>','<?=$row->id_level?>')">
                    <a href="#"><img src="<?php echo base_url(); ?>assets/dist/img/uncheck.png"width="30%" class="shadow-light"></a>
                    </div>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$("#vakses").DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "stateSave": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
});

function vchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/view_akses') ?>',
        data: 'chek=checked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function vunchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/view_akses') ?>',
        data: 'chek=unchecked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function addchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/add_akses') ?>',
        data: 'chek=checked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function addunchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/add_akses') ?>',
        data: 'chek=unchecked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function editchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/edit_akses') ?>',
        data: 'chek=checked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function editunchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/edit_akses') ?>',
        data: 'chek=unchecked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function delchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/delete_akses') ?>',
        data: 'chek=checked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function delunchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/delete_akses') ?>',
        data: 'chek=unchecked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function uplchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/upload_akses') ?>',
        data: 'chek=checked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function uplunchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/upload_akses') ?>',
        data: 'chek=unchecked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function printchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/print_akses') ?>',
        data: 'chek=checked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}

function printunchecked(id, level) {
    $.ajax({
        type: 'POST',
        url: '<?=base_url('userlevel/print_akses') ?>',
        data: 'chek=unchecked&id=' + id,
        success: function(data) {
            $.ajax({
                url: '<?php echo base_url('userlevel/view_akses_submenu'); ?>',
                type: 'post',
                data: 'id=' + level,
                success: function(respon) {
                    $("#md_def").html(respon);
                }
            })
        }
    });
}
</script>