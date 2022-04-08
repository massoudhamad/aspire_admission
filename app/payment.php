<?php
$db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>

        <style type="text/css">
            #message{
                font-size: 12px;
            }
        </style>
 <div class="page-title">
          <div>
            <h1><i class="fa fa-th-list"></i> Application Payment</h1>
            <p>Pay your application</p>
          </div>
        </div>

<?php
$applicantResult=$db->getRows('applicantresults',array('where'=>array('applicantID'=>$_SESSION['applicantID'],'levelStatus'=>1)));
foreach($applicantResult as $ars)
{
    $applicantResultStatus=$ars['applicantResultStatus'];
}
if($applicantResultStatus==0 && $admissionLevel=="UG")
{
    exit;
}
?>

<div class="row">
          <div class="col-md-10">
            <div class="card">


         <div class="col-lg-12">
                <?php
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="unsucc") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Invalid Data,Try again later</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="error") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator for this Message</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="exist") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Token is already used</strong>.
                    </div>";
                      }
                    }
                ?>
                </div>

                <script>
                    function validate() {
                        if(document.form1.payment.value=='bank')
                        {
                            if(document.form1.amount.value < 30000)
                            {
                                alert("Invalid Amount Paid");
                                return false;
                            }
                            else if(document.form1.token.value=="")
                            {
                                alert("Invalid Token");
                                return false;
                            }
                            else if(document.form1.token.value.length < 6)
                            {
                                alert("Invalid Receipt Number");
                                return false;
                            }
                            else
                            {
                                return true;
                            }
                        }
                        else if(document.form1.payment.value=='tigo')
                        {
                            if(document.form1.amount.value < 30000)
                            {
                                alert("Invalid Amount Paid");
                                return false;
                            }
                            else if(document.form1.token.value=="")
                            {
                                alert("Invalid Token");
                                return false;
                            }
                            else if(document.form1.token.value.length < 11)
                            {
                                alert("Invalid Token");
                                return false;
                            }
                            else
                            {
                                return true;
                            }
                        }
                        else
                        {
                            if(document.form1.amount.value < 30000)
                            {
                                alert("Invalid Amount Paid");
                                return false;
                            }
                            else if(document.form1.token.value=="")
                            {
                                alert("Invalid Token");
                                return false;
                            }
                            else if(document.form1.token.value.length < 20)
                            {
                                alert("Invalid Token");
                                return false;
                            }
                            else
                            {
                                return true;
                            }
                        }
                        return false;
                    }
                </script>

                <form class="form-horizontal" name="form1" id="form1" action="action_payments.php" method="post" onsubmit="return validate()">
                    <?php
                    $payments = $db->getRows("applicant_payment", array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by applicantID ASC'));
                    if (!empty($payments)) {
                        foreach ($payments as $pay) {
                            $paymentMethod=$pay['paymentMethod'];
                            $amount=$pay['amount'];
                            $token=$pay['token'];
                        }
                    }
                    else
                    {
                        $paymentMethod="";
                        $amount="";
                        $token="";
                    }
                            ?>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="gender">Payment Method</label>
                                        <select name="payment" id="payment" class="form-control" required="">
                                            <option value="<?php echo $paymentMethod;?>"><?php echo $paymentMethod;?></option>
                                            <option value="bank">PBZ Bank</option>
                                            <!-- <option value="tigo">Tigo Pesa</option>
                                            <option value="airtel">Airtel Money</option> -->
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="gender">Amount Paid</label>
                                        <input type="money" name="amount" value="<?php echo $amount;?>" placeholder="Eg. 30000" class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="gender">Payment Receipt</label>
                                        <input type="text" name="token" value="<?php echo $token;?>" class="form-control">
                                    </div>

                                </div>
                            </div>


                        <div class="row">
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doSave" value="Save Payments" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-6"></div>

                            <div class="col-lg-3">
                            <form name="" method="post" action="action_payments.php">
                            <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>

                    </form>
                            <!-- <input type="hidden" name="action_type" value="add"/> -->
<!--                             <input type="submit" name="doSubmit" value="Proceed to Application" class="btn btn-success form-control" />
 -->                        </div>

                        </div>

                        <?php
            $org = $db->getRows("organization");
            if (!empty($org)) {
                foreach ($org as $og) {
                    $orgName = $og['organizationName'];
                    $orgPhone = $og['organizationPhone'];
                    $studentSupport = $og['student_support'];
                }
            }
            $studyLevelID = $db->getStudyLevelID($firstChoice);
            //$appfees = $db->getData("applicationfees", "fees", "studyLevelID", $studyLevelID);
            $campus = $db->getRows("campus");
            if (!empty($campus)) {
                foreach ($campus as $cp) {
                    $campusName = $cp['campusName'];
                    $campusAddress = $cp['campusAddress'];
                    $accountNumber = $cp['accountNumber'];
                    $bankName = $cp['bankName'];
                    $accountName = $cp['accountName'];
                    $campusname = "$campusName,$campusAddress";
                }
            }
            ?>


            <div class="row">
                <p><span style="font-size: 24px;"><strong>Please pay application fee through <?php echo $bankName; ?> Bank/Agent with the following detail:</strong></span> </p>
                <div class="col-lg-12"><span style="font-size:18px">
                        Account Number: <?php echo $accountNumber; ?><br>
                        Account Name: <?php echo $accountName; ?><br>

                        <p><span style="font-size: 24px;"><strong>
                        <?php
                        $feescategories = $db->getRows("applicationfees", array('order by studyLevelID DESC'));
                        foreach ($feescategories as $fee) {
                            echo "Fee Categories: TSHs. " . $fee['fees'] . "/= ";/* for " . $db->getData("studylevels", "studyLevelName", "studyLevelID", $fee['studyLevelID']) . "<br>" */
                        }
                        ?>
                        </span></p>
                        <!-- Tshs. 25,000/= for Certificate/Diploma Applicant<br>
                        Tshs. 50,000/= for Postgraduate Applicant<br> -->
                        Send your pay-in slip through WhatsApp Numbers: <?php echo $studentSupport; ?>
                    </span>
                </div>

            </div>

                       <!--  <div class="row">
                            <div class="col-lg-12">
                                
                        

                            </div>
                        </div> -->
</form>
</div>
    </div>
                    </div>