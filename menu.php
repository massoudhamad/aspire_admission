<?php
session_start();
?>
<!-- Sidebar Menu -->
<ul class="sidebar-menu">
    <li class="header">
        <h2>Main Menu</h2>
    </li>
    <!-- Optionally, you can add icons to the links -->
    <li class="active"><a href="index3.php"><i class="glyphicon glyphicon-home"></i> <span>Home</span></a></li>

    <li class="treeview">
        <a href="#"><i class="glyphicon glyphicon-th-large active"></i> <span>System Configuration</span>
            <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <?php
            if (($_SESSION['role_session'] == 3) || $_SESSION['role_session'] == 1) {
            ?>
                <li><a href="index3.php?sp=academicyear">Academic Year</a></li>
                <li><a href="index3.php?sp=organization">Organization Info</a></li>
                <li><a href="index3.php?sp=campus">Campus</a></li>
                <li><a href="index3.php?sp=schools">Schools</a></li>
                <li><a href="index3.php?sp=departments">Departments</a></li>
                <li><a href="index3.php?sp=plevels">Study Levels</a></li>
                <li><a href="index3.php?sp=programmes">Programmes</a></li>
                <li><a href="index3.php?sp=publish">Publish/Unpublish Programmes</a></li>
                <li><a href="index3.php?sp=subjects">Subjects</a></li>
                <li><a href="index3.php?sp=grades">Grades</a></li>
                <li><a href="index3.php?sp=pmapping">Program Requirements</a></li>
                <li><a href="index3.php?sp=applicationfees">Application Fees</a></li>
                <li><a href="index3.php?sp=feestype">Fees Type</a></li>
                <li><a href="index3.php?sp=programmefees">Programme Fees</a></li>
                <li><a href="index3.php?sp=agents">Manage Agents</a></li>
                <li><a href="index3.php?sp=admission_setting">Admission Setting</a></li>
                <li><a href="index3.php?sp=admission_round">Admission Rounds</a></li>
                <li><a href="index3.php?sp=application_level">Application Levels</a></li>
                <li><a href="index3.php?sp=admission_letter_setting">Admission Letter Setting</a></li>
                <li><a href="index3.php?sp=programme_batch">Programme Batch</a></li>
                <li><a href="index3.php?sp=document_upload">Documents Uploads</a></li>
            <?php
            }
            if ($_SESSION['role_session'] == 1) {
            ?>
                <li><a href="index3.php?sp=user">Manage User</a></li>
                <li><a href="index3.php?sp=user_archive">User Archive</a></li>
                <li><a href="index3.php?sp=api_setting">API Setting</a></li>
            <?php
            }
            ?>

        </ul>
    </li>

    <!--              <li class="treeview">
              <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Admission Configuration</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                  <li><a href="index3.php?sp=semester_setting">Admission Setting</a></li>
                   
              </ul>
            </li>-->

    <li class="treeview">
        <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Admission Information</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="index3.php?sp=approve">Applicants List(NECTA)</a></li>
            <li><a href="index3.php?sp=foreign">Applicants List(Others)</a></li>
            <li><a href="index3.php?sp=pg_applicants">Applicants List(Postgraduate)</a></li>
            <li><a href="index3.php?sp=approvedlist">List of Approved</a></li>
            <?php
            if ($_SESSION['role_session'] == 3 || $_SESSION['role_session'] == 1) {
            ?>
                <li><a href="index3.php?sp=viewbyremarks">View By Remarks</a></li>
            <?php
            }
            ?>
            <?php
            if ($_SESSION['role_session'] == 3 || $_SESSION['role_session'] == 1) {
            ?>
                <li><a href="index3.php?sp=listallapplicants">List of All Applicants</a></li>
            <?php
            }
            ?>
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Selection Process</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <!-- <li><a href="index3.php?sp=applicant_list_direct">Applicants-Direct</a></li>
            <li><a href="index3.php?sp=applicant_list_equivalent">Applicants-Equivalent</a></li> -->
            <li><a href="index3.php?sp=selection_list">Approved Applicants-Direct</a></li>
            <li><a href="index3.php?sp=selection_list_equivalent">Approved Applicants-Equivalent</a></li>
            <li><a href="index3.php?sp=admitapplicants">Admit Applicants</a></li>
            <li><a href="index3.php?sp=viewadmittedapplicants">View Admitted Applicants</a></li>
            <li><a href="index3.php?sp=viewrejectedapplicants">View Rejected Applicants</a></li>
            <li><a href="index3.php?sp=registerapplicants">Register Applicants</a></li>
            <li><a href="index3.php?sp=viewregisteredapplicants">View Registered Applicants</a></li>
            <li><a href="index3.php?sp=viewregisteredbatch">Batch Registered Print</a></li>
            <!--<li><a href="index3.php?sp=registercertificate">Register Certificate</a></li>
                <li><a href="index3.php?sp=viewregisterceredtificate">View Registered Certificate</a></li>-->
        </ul>
    </li>





    <li class="treeview">
        <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="index3.php?sp=applicantsreports">Applicant Reports(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=approvedapplicants">Approved Report(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=pending_applicants">Pending List(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=selectedapplicants">Provisional Admitted(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=selected_csv">Selected CSV Format(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=tcu_view_status">TCU View Status</a></li>
            <li><a href="index3.php?sp=nacte_view_status">NACTE View Status</a></li>
            <li><a href="index3.php?sp=search_applicant">Search Applicant</a></li>
            <li><a href="index3.php?sp=registered_report">Registered Report(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=list_registered_report">List of Registered Applicants</a></li>
            <li><a href="index3.php?sp=enrolmentreport">Enrollment Reports(TCU&NACTE)</a></li>
            <li><a href="index3.php?sp=tcuenrolmentreport">TCU All Enrollment</a></li>
            <li><a href="index3.php?sp=transfer_report">Transfer Report</a></li>
            <li><a href="index3.php?sp=nactereport">Approved NACTE</a></li>
            <li><a href="index3.php?sp=agentreport">Agents Report</a></li>
            <li><a href="index3.php?sp=agent_remarks">Agents By Remarks</a></li>
            <li><a href="index3.php?sp=payment_report">Payment Report</a></li>

            <!--  <li><a href="index3.php?sp=tcuequivalentreport">TCU Equivalent Reports</a></li> -->
            <!-- <li><a href="index3.php?sp=admittedreport">Admitted Applicants</a></li>
<li><a href="index3.php?sp=registeredreports">Registered Applicants</a></li>
<li><a href="index3.php?sp=reportbyprogrammes">Report By Programmes</a></li>
<li><a href="index3.php?sp=statisticalsummary">Statistical Summary</a></li>
<li><a href="index3.php?sp=certificatereport">Certificate Report</a></li>
<li><a href="index3.php?sp=remarksreport">Remarks Report</a></li> -->
        </ul>
    </li>
    <?php
    if ($_SESSION['role_session'] == 3 || $_SESSION['role_session'] == 1) {
    ?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>TCU Integration</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=check_status">Check Status</a></li>
                <li><a href="index3.php?sp=add_applicant_tcu">Add Applicant</a></li>
                <li><a href="index3.php?sp=submit_selected_tcu" title="Submit Applicant Programme Choices">Submit
                        Applicant</a></li>
                <li><a href="index3.php?sp=resubmit_applicant_tcu">Resubmit Applicant</a></li>
                <li><a href="index3.php?sp=submit_unselected_tcu">Submit Unselected Applicant</a></li>
                <li><a href="index3.php?sp=populate_dashboard_tcu">Populate Dashboard</a></li>
                <li><a href="index3.php?sp=get_admitted_tcu">Get Admitted Applicants</a></li>
                <li><a href="index3.php?sp=get_programmes_tcu">Get Programmes with Admitted</a></li>
                <li><a href="index3.php?sp=get_status_tcu">Get Applicant Status</a></li>
                <li><a href="index3.php?sp=confirm_app_individual">Confirm Individual Applicant</a></li>
                <li><a href="index3.php?sp=un_confirm_app_individual">UnConfirm Individual Applicant</a></li>
                <li><a href="index3.php?sp=cancel_applicant">Cance/Reject Applicant</a></li>
                <li><a href="index3.php?sp=request_confirmation_code">Request Confirmation Code</a></li>
                <!-- <li><a href="index3.php?sp=confirm_app_list_tcu">Confirm Applicant</a></li> -->
                <li><a href="index3.php?sp=get_list_of_confirmed_tcu">Get List of Confirmed</a></li>
                <li><a href="index3.php?sp=get_verification_list">Get Verification List</a></li>
                <li><a href="index3.php?sp=internal_transfer">Internal Transfer</a></li>
                <li><a href="index3.php?sp=external_transfer">External Transfer</a></li>
                <li><a href="index3.php?sp=submit_nondegree">Submit Non-Degree</a></li>
                <li><a href="index3.php?sp=upload_non_degree">Upload Non-Degree</a></li>
                <li><a href="index3.php?sp=get_admitted_non_degree">Get Admitted Non-Degree</a></li>
                <li><a href="index3.php?sp=enrollment_submission">Enrollment Submission</a></li>

            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="glyphicon glyphicon-th-large"></i> <span>NACTE Integration</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=institution_details">Institution Details</a></li>
                <li><a href="index3.php?sp=upload_list_nacte">Upload List for Verification</a></li>
                <li><a href="index3.php?sp=nacte_get_feedback_error">Get Feedback Error</a></li>
                <li><a href="index3.php?sp=nacte_add_correction_error">Upload list of Corrected Data</a></li>
                <li><a href="index3.php?sp=nacte_add_correction">View List After Correction</a></li>
                <li><a href="index3.php?sp=verified_admitted_students">Verified Admitted</a></li>
                <li><a href="index3.php?sp=balance_payment">Balance Payment</a></li>
            </ul>
        </li>

    <?php
    }
    ?>

</ul><!-- /.sidebar-menu -->