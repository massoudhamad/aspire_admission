<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include("DB.php");
$db=new DBHelper();

$apiNumber="S1291-0029/1/2003";
$token="";

$json = file_get_contents("https://api.necta.go.tz/api/public/results/" . $apiNumber . "/" . $token);


//$appfees = $db->getData("applicationfees", "fees", "studyLevelID", 3);
//$appfees=$db->getApplicationFees(3);
//echo $appfees;

/*$academicYear="2018/2019";
$year=explode("/",$academicYear);
$year1=$year[0];
$year1Sub=substr((string)$year1,2,3);//17
$year2=$year[1];
$year2Sub=substr((string)$year2,2,3);
echo $year1Sub."".$year2Sub;*/

/* if(strlen($db->getAPIToken())>1)
    echo $db->getAPIToken();
else
    echo 0; */

//echo $db->getEquivalentStudyLevels(5);

//echo $db->getOrganizationValue("organizationReference");

/*echo $db->my_simple_crypt(25,'e');
echo "<br>";
echo $db->my_simple_crypt($db->my_simple_crypt(25,'e'),'d')-$db->my_simple_crypt($db->my_simple_crypt(25,'e'),'d');

$token= $db->getAPIToken();
 
$indexNumber='S0402/0005/2015';
$iNumber=explode("/",$indexNumber);
$centerNumber=$iNumber[0];
$number=$iNumber[1];
$yearTaken=$iNumber[2];
$apiNumber=$centerNumber."-".$number."/1/".$yearTaken;


$json=file_get_contents("https://api.necta.go.tz/api/public/results/".$apiNumber."/".$token);
$data = json_decode($json,true);
var_dump($data);
echo "Name is ".$data['particulars']['first_name']; */

/* $token= $db->getAPIToken();
//echo $token;
$indexNumber="S0384-0034";
$exam_id=1;
$exam_year=2017;
$apiNumber=$indexNumber."/".$exam_id."/".$exam_year;

$json=file_get_contents("https://api.necta.go.tz/api/public/particulars/".$apiNumber."/".$token);
$data = json_decode($json,true);

var_dump($data);
echo $data['particulars']['first_name'];
echo "<br>";
echo $data['status']['code']; */

/*foreach($data as $values)
{
    echo $values['first_name'];
    echo $values['code'];
}*/

//var_dump($db->getApprovedList(39,1));

/*$academicYear=$db->getData("academicyears","academicYear","academicYearID",1);
$year=explode("/",$academicYear);
$year1=$year[0];

$yearSub=substr((string)$year1,2,3);//17
$yearSubString=substr((string)$year1,1,3);//017

echo $yearSub;
echo "<br>";
echo $yearSubString;

echo $db->count_digit(100);

$campusID=$db->getProgrammeCampus(19);
echo $campusID;

/*var_dump($db->getProgrammeMajorID(2452));


$osubjects=$db->getSelectionSubjects(2452,"Ordinary");
if(!empty($osubjects))
{
    $odata=array();
    
    $subjects=array();
    $grade=array();
    $arrpoints=array();
    
    $totalPoints=0;
    foreach($osubjects as $subject) {
        $subjectID=$subject['subjectID'];
        $gradeID=$subject['gradeID'];
        $points=$subject['points'];
        if(empty($subjects))
        {
            $subjects[]=$subjectID;
            $grade[]=$subject['gradeID'];
            $arrpoints[]=$subject['points'];
        }
        else if(in_array($subjectID,$subjects))
        {
            for($i=0;$i<sizeof($subjects);$i++)
            {
                if($subjects[$i]==$subjectID)
                {
                    if((int)$arrpoints[$i]<(int)$points)
                    {
                       $subjects[$i]=$subjectID;
                        $grade[$i]=$gradeID;
                        $arrpoints[$i]=$points;
                        /*array_replace($subjects[$i],$subjectID); 
                        array_replace($grade[$i],$gradeID);
                        array_replace($arrpoints[$i],$points);
                        
                    }
                }
                
            }
        }
       else 
       {
                $subjects[]=$subjectID;
                $grade[]=$gradeID;
                $arrpoints[]=$points;
                /*array_push($subjects,$subjectID);
                array_push($grade,$gradeID);
                array_push($arrpoints,$points);
        }*/
        
        
        /*$subjectCode=$subject['subjectCode'];
        
        $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
        $gradePoints=$db->getData("grades","gradePoint","gradeID",$gradeID);
        
        $totalPoints+=$points;
        $odata[]=$subjectCode."-".$grade;
        
    }
    //echo sizeof($subjects);
    //echo sizeof($grade);
    //echo sizeof($arrpoints);
    $totalPoints=0;
    for($j=0;$j<sizeof($subjects);$j++)
    {
        echo $subjects[$j]."-".$grade[$j]."-".$arrpoints[$j];
        echo "<br>";
        $totalPoints+=$arrpoints[$j];
    }
    
}


//var_dump($odata);
//echo $totalPoints;

/*$cars=array(2,4,5);
echo $cars[2];
echo "<br>";
*/
/*if($db->checkProgrammeChoice(39,3151))
{
    echo "Done";
}
else
{
    echo "None";
}

if($db->checkCompulsorySubjects(49))
{
    echo "Good";
}
else
{
    echo "Bad";
}*/
// /var_dump($db->getApplicantsSubjects(943,'Ordinary'));    

//$data=$db->getApplicantPoints($applicantID);
////$data= array_unique($data);
//rsort($data);
//var_dump($data);
//echo "<br>";
//var_dump($db->getPassPoints(2,3));
//echo "<br>";
//echo $db->getSumPoints(2,3);
//echo "<br>";
//var_dump($db->programmeChoice(8,3));
//echo "<br>";
//echo $db->getSubjectCount(3);
//echo "<br>";
//echo $db->getQualification(3);
//echo "<br>";
//var_dump($db->programmeChoice(4,3));
//echo $db->haveCompulsorySubjects(23,7);
echo "<br>";
//echo $db->isDataExist("employmentstatus", "applicantID",7);
//echo $db->getAppSubjectPoint(7,4);
echo "<br>";
//var_dump($db->getProgrammeChoice());
//var_dump($db->getCompulsorySubjects(13));
echo "<br>";
//echo $db->isIndexNumberExist('S1023/7234/2010',1);
//var_dump($db->haveCompulsorySubjects(13,7));

//var_dump($db->programmeChoice(3,8));
echo "<br>";
//var_dump($db->getSelectionPoints(2,"Ordinary"));
echo "<br>";
//echo $db->isEquavalentEntry(36);
echo "<br>";
//echo $db->getCountApplicants(1,1,'all');

echo "<br>";
//var_dump($db->getProgrammeChoice(1,54));
echo "<br>";
//echo $db->getGradeType(432);
//echo $db->haveCompulsorySubjects(51,894);
//var_dump($db->getCompulsorySubjects(51));
echo "<br>";
//var_dump($db->getCompulsoryGrade(51));
//var_dump($db->getApproved(23,1,1,2,0,1));
//echo $db->getStudyLevelID(18);
/*$allowedSubjects=$db->getRows("subjectrequirements",array('where'=>array('programmeRequirementID'=>1,'subjectType'=>'allowed'),'order by subjectRequirementID'));
foreach($allowedSubjects as $sub)
{
    echo $sub['subjectID'];
}
var_dump($db->getProgrammeMajor(4));
echo "<br>";
foreach ($db->getProgrammeMajor(4) as $pMajor)
{
    echo $pMajor['programmeMajorID']."<br>";
}
echo "<br>";*/
//var_dump($db->getApplicantSubjects(36));
//$applicantSubjects=$this->getApplicantSubjects($applicantID);
//echo 
echo "<br>";
//echo $db->haveCompulsorySubjects(1,36);
/*var_dump($db->getCompulsorySubjects(15));
echo "<br>";
echo $db->haveCompulsorySubjects(1,36);*/
//echo $db->getAppSubjectPoint(36,45);
//echo $db->getCompulsoryGrade(1);
        /*//echo $db->get
 //var_dump($db->getQualification(34));
//echo $db->isEquavalentEntry(30);
//echo $db->getGradeType(34);
//echo $db->getApplicantGPA(34);
//$db->getProgrammeChoice(2,34);
//$array=array();
//$chosen=$_POST['chosen'];
//for($i=0; $i < count($chosen); $i++) {
//    //$choose= implode(",",$chosen[$i]);
//    $array[]=$i;
//}
//for($x=0;$x<count($array);$x++)
//{
//    echo $array;
//}

//if ( !empty($_POST) ){

    //$_POST['languages'] is an array itself
    //$langs = implode(',', $_POST['chosen']);
    //$langs you want to insert into the table:
    //print $langs;

 //}

/*$found = null;
$people = array(3,20,2,8);
$criminals = array( 2, 4, 8, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);
foreach($people as $num) {
    if (in_array($num,$criminals)) {
        echo "Found<br>";
    } 
}*/
//var_dump($found);
?>

