<div class="col-12 col-md-12 col-lg-12">
    <div class="modal-header">
        <?php
    if (!empty($dataOpTime)) {
      foreach ($dataOpTime as $dataOpTime) {
      }
    }?>
        <p></span>
        <h4 style="display:block; text-align:center;"><?php if (!empty($dataOpTime->id_x)) {
                                                    echo 'Edit XENTRY Operation Time';
                                                  } else {
                                                    echo 'Penambahan Data XENTRY Operation Time';
                                                  }
                                                  ?></h4>

    </div>
      <form <?php if (empty($dataOpTime->id_x)) {
            echo 'id="form-tambah-operation-time"';
          } else {
            echo 'id="form-update-operation-time"';
          } ?> method="POST">
    <div class="modal-body form">
            <div class="form-group">              
                <label class="control-label">No <span class="required red"> *</span></label>
                <input type="hidden" name="id_x" id="id_x" value="<?php if (!empty($dataOpTime->id_x)) {
                                                          echo $dataOpTime->id_x;
                                                        } ?>">
                <input type="text" class="form-control" placeholder="Code No" value="<?php
                                                                                    if (!empty($dataOpTime->code)) {
                                                                                      echo $dataOpTime->code;
                                                                                    } else { echo ''; }
                                                                                    ?>" name="code"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">Duration <span class="required red"> *</span></label>
                <input type="text" class="form-control" placeholder="Duration" value="<?php
                                                                                    if (!empty($dataOpTime->duration)) {
                                                                                      echo $dataOpTime->duration;
                                                                                    }
                                                                                    ?>" name="duration"
                    aria-describedby="sizing-addon2" required>
            </div>
            <div class="form-group">
                <label class="control-label">IM </label>
                <input type="text" class="form-control" placeholder="IM" value="<?php
                                                                                    if (!empty($dataOpTime->im)) {
                                                                                      echo $dataOpTime->im;
                                                                                    }
                                                                                    ?>" name="im"
                    aria-describedby="sizing-addon2">
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <input type="text" class="form-control" placeholder="Description" value="<?php
                                                                                    if (!empty($dataOpTime->description)) {
                                                                                      echo $dataOpTime->description;
                                                                                    }
                                                                                    ?>" name="description"
                    aria-describedby="sizing-addon2" required>
            </div>

    </div>
    <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
    </div>
    </form>
</div>