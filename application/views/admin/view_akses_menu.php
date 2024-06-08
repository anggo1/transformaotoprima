<div class="card-body">
  <table class="table table-bordered table-hover" id="vakses1"> 
    <thead>
      <tr class="bg-primary">
        <th>No</th>
        <th>Menu</th>
        <th>Show</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; foreach ($data_menu as $row) {

        ?>
        <tr>
          <td><?=$i++;?></td>
          <td align="left"><?=$row->nama_menu?></td>
          <td>
            <?php if ($row->view_level=="Y") {?>
              <div id="vcek<?=$i.$row->id?>" onClick="checked('<?=$row->id?>','<?=$row->id_level?>')">
                <i class="fas fa-check-circle text-success btn"></i>
              </div>
              
            <?php }else{?>
              <div id="vucek<?=$i.$row->id?>" onClick="unchecked('<?=$row->id?>','<?=$row->id_level?>')">
                <i class="fa fa-times-circle text-red btn"></i>
              </div>

            <?php } ?>
          </td>
    </tr>
<?php } ?>
</tbody>
</table>
</div>

<script type="text/javascript">
$("#vakses1").DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "stateSave": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
});
  function checked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/update_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }
  function unchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/update_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {

        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
            table.ajax.reload(null, false);
          }
        })
      }
    });
  }
  function vchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/view_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function vunchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/view_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function addchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/add_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function addunchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/add_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function editchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/edit_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function editunchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/edit_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function delchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/delete_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function delunchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/delete_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function uplchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/upload_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function uplunchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/upload_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }
  function printchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/print_akses') ?>',
      data : 'chek=checked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }

  function printunchecked(id, level) {
    $.ajax({
      type : 'POST',
      url : '<?=base_url('userlevel/print_akses') ?>',
      data : 'chek=unchecked&id='+id,
      success : function (data) {
        $.ajax({
          url : '<?php echo base_url('userlevel/view_akses_menu'); ?>',
          type : 'post',
          data : 'id='+level,
          success : function(respon){
            $("#md_def").html(respon);
          }
        })
      }
    });
  }
</script>