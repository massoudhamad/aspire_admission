<div class="container">
    <div class="col-lg-12">
        <?php
        if (!empty($_REQUEST['msg'])) {
            if ($_REQUEST['msg'] == "unsucc") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: We are unable to save your data in TCU Database.</strong>
                    </div>";
            } else {
                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Data saved successfully</strong>.
                    </div>";
                /*echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>".$_SESSION['output']."</strong>.
                    </div>";*/
            }
        }
        ?>
    </div>
    <h2>List of applicants pending approval</h2>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <table id="foreignsubmitlist" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>Ref.Number</th>
                        <th>Index Number</th>
                        <th>Programme</th>
                        <th>Submitted Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="ajax/index.js"></script>