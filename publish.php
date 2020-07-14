<div class="container">
  <script type="text/javascript">
  $(document).ready(function () {
            $('#programmes').DataTable(
                {
                   "scrollX":true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            exportOptions:{
                                columns:[0,1,2,3,4,5,6,7,8]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Programmes',
                            footer: false,
                            exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Programmes',
                            footer: true,
                           exportOptions: {
                                columns:[0,1,2,3,4,5,6,7,8]
                            },
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<h2>Publish/Unpublish Programme</h2>
<div class="row"> 
</div>
<div class="row">
        <div class="col-md-12">
            <hr>



        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<?php
          
            $db = new DBHelper();
            $users = $db->getRows('programmemajor',array('order_by'=>'programmeMajor ASC'));
?>
<form name="register" id="register" method="post" action="action_publish.php">
<table  id="programmes" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th width="10">No.</th>
    <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
    <th>Programme Name</th>
    <th>Status</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;

 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><input type='checkbox' class='checkbox_class' name='id[]' value='<?php echo $user['programmeMajorID'];?>'></td>
                <td><?php echo $user['programmeMajor']; ?></td>
                <?php if($user['publishStatus']==1){?>
                <td><span class="label label-success">Published</span></td>
                <?php }else {
                    ?>
                    <td><span class="label label-danger">Un Published</span></td>
                    <?php
                }?>
            </tr>
            <?php } }?>
</tbody>
 </table>
 <div class="row">
            <div class="col-lg-6"></div>
            <input type="hidden" name="number_applicants" value="<?php echo $i;?>">
            <div class="col-lg-3">
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doAdmit" value="Publish" class="btn btn-success form-control">
            </div>
             <div class="col-lg-3">
                 <input type="hidden" name="action_type" value="edit"/>
                <input type="submit" name="doReject" value="Unpublish" class="btn btn-danger form-control">
            </div>
        </div>
        </form>
 </div></div>  
</div>