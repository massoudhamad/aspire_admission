<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>

<div class="row">
          <div class="col-md-12">
           <div class="row">
          <div class="col-md-10">
            <div class="card">
                 <?php 
                   $indexNumber = $db->getRows('users',array('where'=>array('userID'=>$_SESSION['user_session']),'order_by'=>'userID ASC'));
                   if(!empty($indexNumber)){ 
                       $count = 0; 
                       foreach($indexNumber as $iNumber){ $count++;
                       $formfour=$iNumber['userName'];
                       }
                   }
                   ?>
                <form class="form-horizontal" name="form-get-olevel-data" id="form-get-olevel-data" action="" method="post" onsubmit="return ajax_ordinary_level();">
                      <fieldset>
                        <legend>Form Four Results</legend>
                        <div class="form-group">
                          <label class="col-lg-2 control-label" for="inputEmail">Form Four Index Number</label>
                          <div class="col-lg-6">
                            <input class="form-control" id="indexNumber" type="text" value="<?php echo $formfour;?>" readonly>
                          </div>
                          <div class="col-lg-4">
                         <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                          </div>
                        </div> 
                        </fieldset>
                        </form>
          
        </div></div>
        </div>
        </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div id="result">
        </div>
    </div>
</div>
     

                        
