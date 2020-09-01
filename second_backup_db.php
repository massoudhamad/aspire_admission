<?php
/*
 * conn Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 * @author    Massoud Hamad
 * @url       http://www.hmytechnologies.com
 */
require_once('dbconfig.php');
define('SALT_LENGTH', 9);
class DBHelper{

    private $conn;
    public function __construct(){
        
       $database = new Database();
        $conn = $database->dbConnection();
        $this->conn = $conn;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
    

    public function PwdHash($pwd, $salt = null)
    {
        if ($salt === null)
        {
            $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
        }
        else 
        {
            $salt = substr($salt, 0, SALT_LENGTH);
        }
        return $salt . sha1($pwd . $salt);
    }

    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table,$conditions = array()){
        $sql = 'SELECT';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        }else{
            if($query->rowCount() > 0){
                $data = $query->fetchAll();
            }
        }
        return !empty($data)?$data:false;
    }
    
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table,$data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
           if(!array_key_exists('createdDate',$data)){
                $data['createdDate'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('modifiedDate',$data)){
                $data['modifiedDate'] = date("Y-m-d H:i:s");
            }

             if(!array_key_exists('createdBy',$data)){
                $data['createdBy'] = $_SESSION['user_session'];
            }
            
            

            $columnString = implode(',', array_keys($data));
            $valueString = ":".implode(',:', array_keys($data));
            $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")";
            $query = $this->conn->prepare($sql);
            foreach($data as $key=>$val){
                 $query->bindValue(':'.$key, $val);
            }
            $insert = $query->execute();
            return $insert?$this->conn->lastInsertId():true;
        }else{
            return false;
        }
    }
    
    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table,$data,$conditions){
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            if(!array_key_exists('modifiedDate',$data)){
                $data['modifiedDate'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('createdBy',$data)){
                $data['createdBy'] = $_SESSION['user_session'];
            }
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $query = $this->conn->prepare($sql);
            $update = $query->execute();
            return $update?$query->rowCount():false;
        }else{
            return false;
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table,$conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $sql = "DELETE FROM ".$table.$whereSql;
        $delete = $this->conn->exec($sql);
        return $delete?$delete:false;
    }



    public function doLogin($uname,$upass)
    {
       try
       {
          $stmt = $this->conn->prepare("SELECT u.userID,userName,password,status,roleID FROM users u,userroles ur WHERE u.userID=ur.userID and userName=:uname and status=:st");
          $stmt->execute(array(':uname'=>$uname,'st'=>1));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if($userRow['password']===$this->PwdHash($upass, substr($userRow['password'],0,9)))
             {
                $_SESSION['user_session'] = $userRow['userID'];
                $_SESSION['role_session']=$userRow['roleID'];
                //$_SESSION['user_privilege']=$userRow['user_privilege'];
                return true;
             }
             else
             {
                return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function is_loggedin()
   {
      if(isset($_SESSION['user_session']))
      {
         return true;
      }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function doLogout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
   }

    //get Single Record
    public function getData($table,$attrName,$id,$id2)
    {
        try{
        $query = $this->getRows($table,array('where'=>array($id=>$id2),' order_by'=>$attrName.'  ASC'));
        if(!empty($query))
        {
            foreach ($query as  $q) {
                $attrName=$q[$attrName];
                
            }
            return $attrName;
        }
        }
        catch(PDOException $exception)
		{
                    echo "Getting Data error: " . $exception->getMessage();
                }
    }
 
   public function isFieldExist($table,$field,$field2)
    {
       try
       {
      $query=$this->getRows($table,array('where'=>array($field=>$field2),'order_by'=>$field.' ASC'));
        if(!empty($query))
        {
            return true;
        }
        else
        {
          return false;
        }
        }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
    }
    //testing only
    public function isDataExist($table,$field,$field2)
    {
       try
       {
        $query=$this->conn->prepare("SELECT $field from $table where $field=:attr3");
        $query->execute(array(':attr3'=>$field2));
        //$row=$query->fetch(PDO::FETCH_ASSOC);
        //$row=$query->rowCount();
        $row=$query->fetchAll();
        if(count($row)>0)
        //if(!empty($query))
        //if($row>0)
        {
            return true;
        }
        else
        {
          return false;
        }
        }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
    }
//Start Admission System
public function getSubject($applicantResultID,$str)
{
    try
    {
    $query=$this->conn->prepare("SELECT subjectID,subjectName from subjects where subjectID  NOT IN(SELECT subjectID from applicantSubjects where applicantResultID=:appResID) and stream=:st and status=:ss");
    $query->execute(array(':appResID'=>$applicantResultID,':st'=>$str,':ss'=>1));
    $data=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $data[]=$row;
    }
    return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getAlevelSubject($applicantResultID)
{
    try
    {
    $query=$this->conn->prepare("SELECT subjectID,subjectName from subjects where subjectID  NOT IN(SELECT subjectID from applicantSubjects where applicantResultID=:appResID) and stream=:st and status=:ss");
    $query->execute(array(':appResID'=>$applicantResultID,':st'=>2,':ss'=>1));
    $data=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $data[]=$row;
    }
    return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getRemarks()
{
    try
    {
    $query=$this->conn->prepare("SELECT remarkID,remark from remarks where remark='Approved'");
   $query->execute();
    $data=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $data[]=$row;
    }
    return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getApplicantsApproved($pID,$acadID)
{
    try
    {
    $query=$this->conn->prepare("SELECT firstName,middleName,lastName,gender from applicants a, applicantapplication aa where a.applicantID=aa.applicantID and aa.programID=:progID and a.applicationYearID=:appYearID and choice=:chc and applicantsRemarksID=:remarkID");
    $query->execute(array(':progID'=>$pID,':appYearID'=>$acadID,':chc'=>1,'remarkID'=>2));
    $data=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $data[]=$row;
    }
    return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}


public function getQualification($applicantID)
{
    try
    {
    $query=$this->conn->prepare("SELECT qualificationTypeID from applicantstudylevel where applicantID=:appID");
    $query->execute(array(':appID'=>$applicantID));
    $row=$query->fetch(PDO::FETCH_ASSOC);
    $value=$row['qualificationTypeID'];
    return $value;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}


public function getCompulsorySubjects($programmeMajorID)
{
    try
    {
    $query=$this->conn->prepare("SELECT subjectID from subjectrequirements sr,programrequirements pr,programmemajor pm where pm.programmeMajorID=pr.programmeMajorID and pm.programmeMajorID=:progMajID and pr.programRequirementID=sr.programmeRequirementID  and subjectType=:type");
    $query->execute(array(':progMajID'=>$programmeMajorID,':type'=>'compulsory'));
    $data=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $data[]=$row;
    }
    return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getCompulsoryGrade($programmeMajorID)
{
    try
    {
        $query=$this->conn->prepare("SELECT compulsorySubjectGrade from programrequirements pr,programmemajor pm where pm.programmeMajorID=pr.programmeMajorID and pm.programmeMajorID=:progMajID");
        $query->execute(array(':progMajID'=>$programmeMajorID));
        $row=$query->fetch(PDO::FETCH_ASSOC);
        $data=$row['compulsorySubjectGrade'];
        return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}



public function getAppSubjectPoint($applicantID,$subjectID)
{
    try
    {
        $query=$this->conn->prepare("SELECT  points from applicantsubjects a,applicantresults ar where ar.applicantResultID=a.applicantResultID and ar.applicantID=:appID and subjectID=:subID");
        $query->execute(array(':appID'=>$applicantID,':subID'=>$subjectID));
        $row=$query->fetch(PDO::FETCH_ASSOC);
        $value=$row['points'];
        return $value;
    
    }   catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getApplicantSubjects($applicantID)
{
    try
    {
        $qualificationType=$this->getQualification($applicantID);
        if($qualificationType==6)
        {
            $examinationLevel="Ordinary";
        }
        else if($qualificationType==7)
        {
            $examinationLevel="Advance";
        }
        $query=$this->conn->prepare("SELECT subjectID from applicantsubjects aps,applicantresults ar where ar.applicantResultID=aps.applicantResultID and ar.applicantID=:appID and ar.examinationLevel=:level");
        $query->execute(array(':appID'=>$applicantID,':level'=>$examinationLevel));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getApplicantGrade($applicantID)
{
    try
    {
        $qualificationType=$this->getQualification($applicantID);
        if($qualificationType==6)
        {
            $examinationLevel="Ordinary";
        }
        else if($qualificationType==7)
        {
            $examinationLevel="Advance";
        }
        $query=$this->conn->prepare("SELECT gradeID from applicantsubjects aps,applicantresults ar where ar.applicantResultID=aps.applicantResultID and ar.applicantID=:appID and ar.examinationLevel=:level");
        $query->execute(array(':appID'=>$applicantID,':level'=>$examinationLevel));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}
//Not Used by Anything just for test only
public function haveCompulsoryPoints($programmeMajorID,$applicantID)
{
    $found=false;
    $applicantSubjects=$this->getApplicantSubjects($applicantID);
    //print_r($applicantSubjects[0]);
    $progCompulsorySubjects=$this->getCompulsorySubjects($programmeMajorID);
    $compGrade=$this->getCompulsoryGrade($programmeMajorID);
    $compPoints=$this->getData("grades","gradePoint","gradeID",$compGrade);
    $gradePoints=$this->getAppSubjectPoint($applicantID,$appSubjectID);
   
    foreach($progCompulsorySubjects as $pcsubjects)
    {
        $compSubject=$pcsubjects['subjectID'];
       // echo $compSubject."<br>";
        if(in_array($pcsubjects, $applicantSubjects))
        {
            
            foreach($applicantSubjects as $item)
            { 
                $applicantSubjectID= $item['subjectID'];
                if($compSubject==$applicantSubjectID)
                {
                    //echo $applicantSubjectID."<br>";
                    $found= true;
                }
           }
        }
        
          
    }
      return $found;
}

public function haveCompulsorySubjects($programmeMajorID,$applicantID)
{
    try
    {
    $found=false;
    $applicantSubjects=$this->getApplicantSubjects($applicantID);
    $progCompulsorySubjects=$this->getCompulsorySubjects($programmeMajorID);
    foreach($progCompulsorySubjects as $pcsubjects)
    {
        if(in_array($pcsubjects, $applicantSubjects))
        {
           $compSubjectID=$pcsubjects['subjectID'];
            $compGrade=$this->getCompulsoryGrade($programmeMajorID);
            $compPoints=$this->getData("grades","gradePoint","gradeID",$compGrade);
            foreach($applicantSubjects as $item)
            { 
                $applicantSubjectID= $item['subjectID'];
                if($compSubjectID==$applicantSubjectID)
                {
                    $gradePoints=$this->getAppSubjectPoint($applicantID,$applicantSubjectID); 
                    if($gradePoints>=$compPoints)
                    {
                        $found= true;
                    }
                    
                }
                
            }
            
        }
        else
        {
            $found= false;
            break;
        }
    }
    return $found;
    }
     catch (PDOException $ex) {
        echo "Getting Data Error:".$ex->getMessage();

    }
}

public function isEquavalentEntry($appliID)
{
    try
    {
    $query=$this->conn->prepare("SELECT COUNT(applicantID) as number from applicants where applicantID=:appID and entryqualification=:entry");
    $query->execute(array(':appID'=>$appliID,':entry'=>1));
    $row=$query->fetch(PDO::FETCH_ASSOC);
    $value=$row['number'];
    if($value>0)
    {
        return true;
    }
    else
    {
      return false;
    }
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getGradeType($appliID)
{
    try
    {
    $query=$this->conn->prepare("SELECT gradeType  from applicantresults where applicantID=:appID and entryQualification=:entry");
    $query->execute(array(':appID'=>$appliID,'entry'=>1));
    $row=$query->fetch(PDO::FETCH_ASSOC);
    $value=$row['gradeType'];
    return $value;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getApplicantGPA($appliID)
{
    try
    {
    $query=$this->conn->prepare("SELECT gradePoints  from applicantresults where applicantID=:appID and entryQualification=:entry");
    $query->execute(array(':appID'=>$appliID,'entry'=>1));
    $row=$query->fetch(PDO::FETCH_ASSOC);
    $value=$row['gradePoints'];
    return $value;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

public function getProgrammeMajor($studyLevelID)
{
    try
    {
        $query=$this->conn->prepare("SELECT programmeMajorID from programmemajor pm,programs p,studylevels s where p.programID=pm.programmeID and s.studyLevelID=p.studyLevelID and p.studyLevelID=:stdLvlID");
        $query->execute(array(':stdLvlID'=>$studyLevelID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}

//gettingStudyLevelID
public function getStudyLevelID($programmeMajorID)
{
    try
    {
        $query=$this->conn->prepare("SELECT 
            p.studyLevelID
        from
            studylevels s,
            programmemajor pm,
            programs p
        where
                p.programID = pm.programmeID
                and programmeMajorID =:pMajorID
                and s.studyLevelID=p.studyLevelID");
        $query->execute(array(':pMajorID'=>$programmeMajorID));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $studyLevelID=$row['studyLevelID'];
        }
        return $studyLevelID;
        
    } catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getProgrammeRequired()
{
    try
    {
        $query=$this->conn->prepare("SELECT programmeMajorID,programmeMajor from programmemajor pm,programs p where p.programID=pm.programmeID and programmeMajorID NOT IN(SELECT programmeMajorID from programrequirements)");
        $query->execute();
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
        
    } catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

    

//Getting Applicants Subject Points

public function getProgrammeChoice($studyID,$applicantID)
{ 
    try
    {
    $data=array();
    if($this->isEquavalentEntry($applicantID))
        {
        if($this->getGradeType($applicantID)=="GPA")
        {
            $gpagrade=$this->getApplicantGPA($applicantID);
            $query=$this->conn->prepare("SELECT programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and :gpa >= pr.equivalentEntryGPA and programStatus=:status");
            $query->execute(array(':levelID'=>$studyID,':gpa'=>$gpagrade,'status'=>1));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
        }
        else 
        {
            $gpagrade=$this->getApplicantGPA($applicantID);
            $query=$this->conn->prepare("SELECT programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and programStatus=:status");
            $query->execute(array(':levelID'=>$studyID,':status'=>1));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
        }
    }
    else 
    {
        $programmeMajor=$this->getProgrammeMajor($studyID);
        foreach($programmeMajor as $proMajor)
        {
            $programmeMajorID=$proMajor['programmeMajorID'];
            $nPassGrade=$this->getData("programrequirements","numberOfPassGrade","programmeMajorID",$programmeMajorID);
            $appPoints=$this->getSumPoints($nPassGrade, $applicantID);
            $pointsRequired=$this->getData("programrequirements","pointsRequired","programmeMajorID",$programmeMajorID);
            $subjectCount=$this->getSubjectCount($applicantID);
           // if(($appPoints>=$pointsRequired)||($subjectCount>=$nPassGrade))
            if($this->getQualification($applicantID)==7)
            {
                if($appPoints>=$pointsRequired)
                {
                    if($this->haveCompulsorySubjects($programmeMajorID, $applicantID))
                    {
                        $query=$this->conn->prepare("SELECT programmeMajorID,programmeMajor from programs p,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=:pMajorID and programStatus=:status");
                        $query->execute(array(':pMajorID'=>$programmeMajorID,'status'=>1));
                        while($row=$query->fetch(PDO::FETCH_ASSOC))
                        {    
                            $data[]=$row;
                        }
                    }
                }
            }
            else if($this->getQualification($applicantID)==6)
            {
                if($subjectCount>=$nPassGrade)
                {
                    if($this->haveCompulsorySubjects($programmeMajorID, $applicantID))
                    {
                        $query=$this->conn->prepare("SELECT programmeMajorID,programmeMajor from programs p,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=:pMajorID and programStatus=:status");
                        $query->execute(array(':pMajorID'=>$programmeMajorID,'status'=>1));
                        while($row=$query->fetch(PDO::FETCH_ASSOC))
                        {    
                            $data[]=$row;
                        }
                    }
                }
            }
        }
    }
    return $data;
    }
    catch(PDOException $exception)
    {
        echo "Getting Data error: " . $exception->getMessage();
    }
}
//for non compulsory programmes
 public function programmeChoice($studyID,$applicantID)
 {
        try
        {
            $programmeMajor=$this->getProgrammeMajor($studyID);
            $data=array();
            foreach($programmeMajor as $proMajor)
            {
                
                $programmeMajorID=$proMajor['programmeMajorID'];
                $nPassGrade=$this->getData("programrequirements","numberOfPassGrade","programmeMajorID",$programmeMajorID);
                $appPoints=$this->getSumPoints($nPassGrade, $applicantID);
                $pointsRequired=$this->getData("programrequirements","pointsRequired","programmeMajorID",$programmeMajorID);
                $subjectCount=$this->getSubjectCount($applicantID);
                //if(($appPoints>=$pointsRequired) || ($subjectCount>=$nPassGrade))
                if($this->getQualification($applicantID)==6)
                {
                    if($subjectCount>=$nPassGrade)
                    {
                        $query=$this->conn->prepare("SELECT
                        pm.programmeMajorID, programmeMajor
                    from
                        programs p,
                        programmemajor pm,
                        programrequirements pr
                    where
                        p.programID = pm.programmeID
                            and pm.programmeMajorID = pr.programmeMajorID
                            and pr.programmeMajorID=:majorID
                            and compulsorySubject = :value
                            and programStatus = :status");
                        $query->execute(array('majorID'=>$programmeMajorID,':value'=>0,':status'=>1));
                        while($row=$query->fetch(PDO::FETCH_ASSOC))
                        {    
                            $data[]=$row;
                        }

                    }
                }
                else if($this->getQualification($applicantID)==7)
                {
                    if($appPoints>=$pointsRequired)
                    {
                        $query=$this->conn->prepare("SELECT
                        pm.programmeMajorID, programmeMajor
                    from
                         programs p,
                        programmemajor pm,
                        programrequirements pr
                    where
                            p.programID = pm.programmeID
                            and pm.programmeMajorID = pr.programmeMajorID
                            and pr.programmeMajorID=:majorID
                            and compulsorySubject = :value
                            and programStatus = :status");
                        $query->execute(array('majorID'=>$programmeMajorID,':value'=>0,':status'=>1));
                        while($row=$query->fetch(PDO::FETCH_ASSOC))
                        {    
                            $data[]=$row;
                        }

                    }
                }
            }
            return $data;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
 }
 //This is all points for applicants based on their levels(Advance-Points, Ordinary-Count)
 public function getApplicantPoints($applicantID)
 {
     try
     {
        $data=array();
        if($this->getQualification($applicantID)==6)
        {
            $elevel="Ordinary";
        }
        else 
        {
            $elevel="Advance";
        }
        $query=$this->conn->prepare("SELECT 
            MAX(points) as points
        from
            applicantsubjects at,
            applicantresults ar
        where
            ar.applicantResultID = at.applicantResultID
                and ar.applicantID = :appID
                and examinationLevel = :level
        group by subjectID
        having count(subjectID) >= 2");
        $query->execute(array(':appID'=>$applicantID,':level'=>'Advance'));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {    
            $data[]=$row;
        }
        
        //for not multiples
        $queryresult=$this->conn->prepare("SELECT 
            points
        from
            applicantsubjects at,
            applicantresults ar
        where
            ar.applicantResultID = at.applicantResultID
                and ar.applicantID = :appID
                and examinationLevel = :level
                        and subjectID NOT IN (SELECT subjectID 
                        from
            applicantsubjects at,
            applicantresults ar
        where
            ar.applicantResultID = at.applicantResultID
                and ar.applicantID = :appID
                and examinationLevel = :level group by subjectID having count(subjectID) >= 2)");
        $queryresult->execute(array(':appID'=>$applicantID,':level'=>'Advance'));
        while($row=$queryresult->fetch(PDO::FETCH_ASSOC))
        {    
            $data[]=$row;
        }
        
        return $data;
         
     } catch (PDOException $ex) {
         echo "Data Error:".$ex->getMessage();
     }
 }
 //getting applicant points based on their passgrade from programmerequirements
 public function getPassPoints($nPassGrade,$applicantID)
 {
     try
     {
         $applicantPoints= $this->getApplicantPoints($applicantID);
         rsort($applicantPoints);
         $data=array();
         if(!empty($applicantPoints))
         {  
                for($x=0;$x<$nPassGrade;$x++)
                {
                     $data[]=$applicantPoints[$x];
                }
         }
         return $data;
     } catch (PDOException $ex) {
         echo "Getting Data Error: ".$ex->getMessage();
     }
 }
 //getting sum of points
 public function getSumPoints($nPassGrade,$applicantID)
 {
     try
     {
         $passPoints= $this->getPassPoints($nPassGrade,$applicantID);
         
         if(!empty($passPoints))
         {   
             $totalSum=0;
             foreach($passPoints as $point)
             {
                 $totalSum+=$point['points'];
             }
         }
         return $totalSum;
     } catch (PDOException $ex) {
         echo "Getting Data Error: ".$ex->getMessage();
     }
 }
 
 public function getSubjectCount($applicantID)
 {
     try
     {
         //$data=array();
        if($this->getQualification($applicantID)==6)
        {
            $elevel="Ordinary";
        }
        else 
        {
            $elevel="Advance";
        }
         $query=$this->conn->prepare("SELECT 
            COUNT(DISTINCT(subjectID)) as subjectCount
        from
            applicantsubjects at,
            applicantresults ar
        where
            ar.applicantResultID = at.applicantResultID
                and ar.applicantID = :appID
                and examinationLevel = :level");
        $query->execute(array(':appID'=>$applicantID,':level'=>$elevel));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {    
            $subjectCount=$row['subjectCount'];
        }
        
        return $subjectCount;
         
     } catch (PDOException $ex) {
         echo "Getting Data Error".$ex->getMessage();

     }
 }
 
}
?>