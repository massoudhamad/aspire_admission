<?php $db = new DBHelper();
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<link href="css/validation.css" rel="stylesheet">

<div class="page-title">
    <div>
        <h1><i class="fa fa-graduation-cap"></i>Advanced Level(FVI) Results</h1>
        <p>Add Advanced/Form Six Results</p>
    </div>
</div>
<div class="row" style="padding-bottom: 15.5%;">
    <div class="col-lg-12">
    </div>

    <div class="row">
        <div class="col-md-10">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <label for="FirstName">Advanced Level Examination Body</label>
                        <select name="exam_body" id="exam_body" class="form-control">
                            <option value="">Select Examination Body</option>
                            <option value="NECTA">National Examination Council of Tanzania (NECTA)</option>
                            <option value="Others">Other/Foreign Examination Body</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="NECTA">
                        <form name="form-get-olevel-data" id="form-get-olevel-data" method="post" onsubmit="return myFunction();">
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="inputEmail">Form Six/Alevel Index Number</label>
                                <div class="col-lg-6">
                                    <input class="form-control" id="indexNumber" type="text" name="indexNumber">
                                </div>
                                <div class="col-lg-4">
                                    <input type="hidden" name="level" id="level" value="alevel">
                                    <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- <div class="row">

                    <div class="col-md-10">
                        <div id="result">
                        </div>
                    </div>
                </div> -->


                <!-- Modal Start here-->

                    <div class="Others">
                        <br>
                        <form name="form-get-equi-olevel-data" id="form-get-equi-olevel-data" method="post" onsubmit="return equivalence_alevel();">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label class="control-label" for="inputEmail">Equivalence A-Level Number</label>
                                    <input class="form-control" id="equivalenceNumber" type="text" name="equivalenceNumber" placeholder="EQ00000000">
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label" for="inputEmail">Examination Year</label>
                                    <input class="form-control" id="yearTaken" type="text" name="yearTaken" placeholder="YYYY">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="level" id="eqlevel" value="alevel">
                                    <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                </div>
                            </div>

                    </div>
</div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <div id="result">
        </div>
    </div>
</div>