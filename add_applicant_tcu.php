<div class="container">
    <div class="col-lg-12">
        <?php
        if(!empty($_REQUEST['msg']))
        {
            if($_REQUEST['msg']=="unsucc") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: We are unable to save your data in TCU Database.</strong>
                    </div>";
            }
            else {
                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>".$_SESSION['output']."</strong>.
                    </div>";
            }
        }
        ?>
    </div>
    <h2>List of applicants for TCU Status</h2>
    <hr >
    <div class="row">
        <div class="col-md-12">
            <table  id="add_applicant_tcu" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Category</th>
                    <th>Index Number</th>
                    <th>Form Six</th>
                    <th>Other Form 4</th>
                    <th>Other Form 6</th>
                    <th>Choice 1</th>
                    <th>Choice 2</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div></div>
</div>
<script type="text/javascript" src="ajax/index.js"></script>