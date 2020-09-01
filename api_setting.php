<div class="container">
  <h3>API Setting</h3>
  <hr>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#departments').DataTable({
        "scrollX": true,
        paging: true,
        //dom: 'Blfrtip',
        buttons: [{
            extend: 'excel',
            title: 'List of API',
            footer: false,
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          }, ,
          {
            extend: 'print',
            title: 'List of API',
            footer: false,
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
          },
          {
            extend: 'pdfHtml5',
            title: 'List of API',
            footer: true,
            exportOptions: {
              columns: [0, 1, 2, 3]
            }
            //orientation: 'landscape',
          }

        ]
      });
    });
  </script>
  <div class="row">
    <div class="col-md-12">
      <div class="pull-right">
        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New API</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <br>
      <?php
      if (!empty($_REQUEST['msg'])) {
        if ($_REQUEST['msg'] == "succ") {
          echo "<div class='alert alert-success fade in'><a href='index3.php?sp=api_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>API data has been inserted successfully</strong>.
</div>";
        } else if ($_REQUEST['msg'] == "edited") {
          echo "<div class='alert alert-success fade in'><a href='index3.php?sp=api_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>API data has been edited Successfully</strong>.
</div>";
        } else {
          echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=api_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
        }
      }
      ?>


    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php

      $db = new DBHelper();

      ?>
      <h3 class="text-info">List of Registered API</h3>
      <table id="departments" class="display nowrap" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Username</th>
            <th>Source</th>
            <th>URL</th>
            <th>Token</th>
            <th>Token Type</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $api_data = $db->getRows('api_setting', array('order_by' => 'apiSettingID'));
          if (!empty($api_data)) {
            foreach ($api_data as $api) {
              $api_id = $api['apiSettingID'];
              $api_name = $api['userName'];
              $api_token = $api['token'];
          ?>
              <tr>
                <td><?php echo $api_name; ?></td>
                <td><?php echo $api['organizationName']; ?></td>
                <td><?php echo $api['url']; ?></td>
                <td><?php echo $api_token; ?></td>
                <td><?php echo $api['tokenType']; ?></td>
                <td>
                  <a href="index3.php?sp=edit_api&id=<?php echo $api['apiSettingID'];?>" class="glyphicon glyphicon-edit"></a>
                </td>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <form name="" method="post" action="action_api_setting.php">
          <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="modal-body">

            <div class="form-group">
              <label for="email">Username</label>
              <input type="text" id="username" name="username" placeholder="UserName" class="form-control" />
            </div>

            <div class="form-group">
              <label for="email">Token</label>
              <input type="text" id="token" name="token" placeholder="Code" class="form-control" />
            </div>

            <div class="form-group">
              <label for="email">Token Type</label>
              <select name="tokenType" class="form-control">
                <option value="">Select Here</option>
                <option value="token">Token</option>
                <option value="auth">Authentication</option>
              </select>
            </div>

            <div class="form-group">
              <label for="email">Source</label>
              <select name="organizationName" class="form-control">
                <option value="">Select Here</option>
                <option value="TCU">TCU</option>
                <option value="NACTE">NACTE</option>
                <option value="NECTA">NECTA</option>
                <option value="MOODLE">MOODLE</option>
                <option value="StAR">Academic Records</option>
                <option value="Finace">Finance</option>
                <option value="OUT">OUT</option>
              </select>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <input type="hidden" name="action_type" value="add" />
            <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
            <!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
            </form>
          </div>
        </div>
      </div>
    </div>