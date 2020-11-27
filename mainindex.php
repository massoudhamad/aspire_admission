<?php 
session_start();
switch((isset($_GET['sp'])?$_GET['sp'] : ''))
{
  case 'home':
  include('home.php');
  break;

case 'academicyear':
include('academicyear.php');
break;

  case 'user':
  include('viewusers.php');
  break;

  case 'departments':
  include('department.php');
  break;

case 'campus':
  include('campus.php');
  break;

case 'organization':
    //include('organization.php');
    include('organization_info.php');
  break;

case 'schools':
  include('schools.php');
  break;
  
case 'admission_setting':
    include('admission_setting.php');
    break;

    case 'admission_round':
        include('admission_round.php');
        break;

case 'document_upload':
    include('upload_document.php');
    break;
    
    
    
case 'upload_new_document':
        include('uploadnewdocument.php');
        break;
    
case 'programme_batch':
        include('programme_batch.php');
        break;

    case 'agents':
        include('agents.php');
        break;
        
case 'addprogrammebatch':
    include('addprogrammebatch.php');
    break;
    
case 'selectedapplicants':
    include('selectedreport.php');
    break;



    case 'pending_applicants':
        include('pending_list.php');
        break;


    case 'selected_csv':
        include('selected_report_csv.php');
        break;


    case 'registered_report':
        include('registered_report.php');
        break;

    case 'list_registered_report':
        include('list_registered_applicants.php');
        break;




        case 'edit_registration_number':
            include('edit_registration_number.php');
            break;


case 'applicantsreports':
include('applicantsreport.php');
break;


case 'enrolmentreport':
    include('enrolmentreport.php');
    break;
    
case 'zalongwareport':
    include('zalongwareport.php');
    break;

    

  case 'plevels':
  include('studylevels.php');
  break;

  case 'programmes':
  include('programme.php');
  break;
  
  case 'publish':
      include('publish.php');
      break;

      case 'subjects':
      include('subjects.php');
      break;
      
      case 'grades':
      include('grades.php');
      break;

      case 'pmapping':
      include('programmerequirements.php');
      break;
		  
      //Registration Form
      case 'rform':
      include('registration.php');
      break;

      case 'norminalroll':
      include('nominalroll.php');
      break;
      
      case 'searchstudents':
      include('student_search.php');
      break;
  
      case 'applicationfees':
      include('applicationfees.php');
      break;
  
      case 'feestype':
      include('feestype.php');
      break;
  
      case 'programmefees':
      include('programmefees.php');
      break;
  
      case 'addnewprogrammefees':
      include('addnewprogrammefees.php');
      break;

      //Semester Configuration
      case 'semester_setting':
      include('semester_setting.php');
      break;
      case 'semester_course':
      include('semester_course.php');
      break;

      case 'instructor':
      include('instructor.php');
      break;
  
      case 'instructor_course':
      include('instructor_course.php');
      break;
  
      case 'addnewstudylevel':
      include('addnewstudylevels.php');
      break;
  
      case 'addnewprogramme':
      include('addnewprogramme.php');
      break;
  
      case 'addnewuser':
      include('addnewuser.php');
      break;
     
     case 'programmefeesall':
     include('programmefeesall.php');
     break;
  
      case 'addnewprogrammerequirements':
      include('addnewprogrammerequirements.php');
      break;
        
    case 'application_level':
        include('application_level.php');
        break;
  
      //Edit Data
      case 'edit_user':
      include('edit_user.php');
      break;
      case 'edit_department':
      include('edit_department.php');
      break;

      case 'edit_school':
      include('edit_school.php');
      break;

    case 'edit_campus':
    include('edit_campus.php');
    break;
  
      case 'edit_levels':
      include('edit_levels.php');
      break;

      case 'edit_programme':
      include('edit_programme.php');
      break;
      
      
  
      case 'edit_subject':
      include('edit_subject.php');
      break;


        
    case 'admission_letter_setting':
        include('admission_letter_setting.php');
        break;
      //Admission Information
      case 'approve':
      include('approveapplicants.php');
      break;

    case 'foreign':
        include('approve_foreign_applicants.php');
        break;

    case 'pg_applicants':
        include('approve_pg_applicants.php');
        break;

        

    case 'pgapplicantdetails':
        include('pgapplicantdetails.php');
        break;
      
      case 'applicantdetails':
      include('applicantdetails.php');
      break;

    case 'applicantdetails_foreign':
        include('applicantdetails_foreign.php');
        break;
  
      
      case 'approvedlist':
      include('approvedapplicants.php');
      break;
  
  
      case 'viewbyremarks':
      include('viewapplicantsbyremarks.php');
      break;
  
      case 'listbyprogrammes':
      include('listbyprogrammes.php');
      break;
      
      
  
      case 'selection_list':
      include('selection_list.php');
      break;
  
      case 'selection_list_equivalent':
      include('selection_list_equivalent.php');
      break;

    case 'applicant_list_direct':
        include('applicant_list_direct.php');
        break;

    case 'applicant_list_equivalent':
        include('applicant_list_equivalent.php');
        break;
      
      case 'admitapplicants':
      include('admitapplicants.php');
      break;

      case 'registerapplicants':
      include('registerapplicants.php');
      break;



        case 'viewregisteredbatch':
        include('viewregisteredbatch.php');
        break;
 
      case 'registercertificate':
      include('registercertificate.php');
      break;
  
      case 'viewadmittedapplicants':
      include('viewadmittedapplicants.php');
      break;
  
      
      case 'viewrejectedapplicants':
      include('viewrejectedapplicants.php');
      break;
  
      case 'viewregisteredapplicants':
      include('viewregisteredapplicants.php');
      break;
  
      case 'viewregisteredcertificate':
      include('viewregisteredcertificate.php');
      break;
          
      case 'qualification':
      include('education_levels.php');
      break;
  
      case 'applicantinfo':
      include('applicantinfo.php');
      break;

      case 'view_applicant_info':
          include('viewapplicantinfo.php');
          break;


    case 'register_applicant':
        include('register_applicant.php');
        break;

    case 'request_confirmation_code':
        include('request_confirmation_code.php');
        break;


    case 'preview_form':
        include('preview_form.php');
        break;


    case 'payment_report':
        include('payment_report.php');
        break;

    case 'nacte_view_status':
        include('nacte_view_status.php');
        break;

    case 'listallapplicants':
        include('listallapplicants.php');
        break;


      case 'listbyschool':
      include('viewbyschools.php');
      break;
  
  
      case 'viewsummaryprogrammes':
      include('viewsummarybyprogrammes.php');
      break;
      //Edit Application
      case 'edit_study':
      include('edit_study.php');
      break;
      
      case 'edit_educational_background':
      include('edit_educational_background.php');
      break;
      
      case 'olevel':
      include('olevel.php');
      break;
  
      case 'alevel':
      include('alevel.php');
      break;
  
      case 'edit_equivalent':
      include('edit_equivalent_results.php');
      break;
  
      case 'edit_programme_choice':
      include('edit_programme_choice.php');
      break;
  
      case 'newsubject':
      include('newsubject.php');
      break;
      
      case 'edit_personal_details':
      include('edit_personal_details.php');
      break;

    case 'edit_api':
        include('edit_api_setting.php');
        break;

        

    case 'updaterequirement':
        include('updatenewrequirement.php');
        break;
      
      //edit application
      //reports
      case 'tcudirectreport':
      include('tcudirectreport.php');
      break;
  
      
      case 'nactereport':
      include('nactereportbyprogramme.php');
      break;


       case 'approvedapplicants':
           include('approvedreport.php');
           break;



    case 'submit_selected_tcu':
        include('tcu_submit_selected.php');
        break;




        case 'resubmit_applicant_tcu':
            include('resubmit_tcu_selected.php');
            break;

        case 'get_verification_list':
            include('get_verification_list.php');
            break;

            

        case 'submit_unselected_tcu':
            include('tcu_submit_unselected.php');
            break;

    case 'get_programmes_tcu':
        include('get_programmes_tcu.php');
        break;


            case 'search_applicant':
                include('search_applicant.php');
                break;
      
      
      case 'tcuenrolmentreport':
      include('tcuenrolmentreport.php');
      break;
     
      
      case 'user_archive':
      include('user_archive.php');
      break;

    case 'agentreport':
        include('agentreport.php');
        break;

    case 'agent_remarks':
        include('agent_remark.php');
        break;


        case 'check_status':
            include('tcu_check_status.php');
            break;

    case 'add_applicant_tcu':
        include('add_applicant_tcu.php');
        break;

    case 'populate_dashboard_tcu':
        include('populate_dashboard_tcu.php');
        break;



        case 'get_admitted_tcu':
            include('get_admitted_tcu.php');
            break;

    case 'get_list_of_confirmed_tcu':
        include('get_confirmed_list_tcu.php');
        break;



            case 'get_status_tcu':
                include('get_status_tcu.php');
                break;

    case 'confirm_app_list_tcu':
        include('confirm_app_list_tcu.php');
        break;


                case 'confirm_applicant_tcu':
                    include('confirm_applicant_tcu.php');
                    break;



    case 'confirm_app_list_tcu':
        include('confirm_app_list_tcu.php');
        break;

    case 'confirm_app_individual':
        include('confirm_applicant_form.php');
        break;

    case 'un_confirm_app_individual':
        include('un_confirm_applicant_form.php');
        break;

    case 'cancel_applicant':
        include('cancel_applicant.php');
        break;

    case 'tcu_view_status':
        include('tcu_view_status.php');
        break;


    case 'transfer_report':
        include('transfer_report.php');
        break;


    case 'internal_transfer':
        /* include('tcu_internal_transfer.php'); */
        include('internal_transfer_tcu.php');
        break;

    case 'external_transfer':
        include('tcu_external_transfer.php');
        break;

    case 'enrollment_submission':
        include('enrollment_submission.php');
        break;

    case 'submit_nondegree':
        include('submit_non_degree_tcu.php');
        break;

    case 'upload_non_degree':
        include('upload_non_degree_tcu.php');
        break;

    case 'get_admitted_non_degree':
        include('get_admitted_non_degree_tcu.php');
        break;



        //nacte intergration
    case 'institution_details':
        include('nacte_institutional_details.php');
        break;



        case 'upload_list_nacte':
            include('nacte_upload_selected.php');
            break;


    case 'nacte_get_feedback_error':
        include('nacte_get_feedback_error.php');
        break;

    case 'nacte_add_correction_error':
        include('nacte_add_correction.php');
        break;


    case 'nacte_add_correction':
        include('nacte_view_after_correction.php');
        break;


case 'api_setting':
        include('api_setting.php');
        break;




      default:
      include('dashboard.php');
}
	  
	  ?>