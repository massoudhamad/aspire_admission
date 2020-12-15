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
class DBHelper
{

    private $conn;

    public function __construct()
    {

        $database = new Database();
        $conn = $database->dbConnection();
        $this->conn = $conn;
    }

    public function runQuery($sql)
    {
        $sql = $this->conn->quote($sql);
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }


    public function PwdHash($pwd, $salt = null)
    {
        if ($salt === null) {
            $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
        } else {
            $salt = substr($salt, 0, SALT_LENGTH);
        }
        return $salt . sha1($pwd . $salt);
    }

    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table, $conditions = array())
    {
        $sql = 'SELECT';
        $sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
        $sql .= ' FROM ' . $table;
        if (array_key_exists("where", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }

        if (array_key_exists("order_by", $conditions)) {
            $sql .= ' ORDER BY ' . $conditions['order_by'];
        }

        if (array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
            $sql .= ' LIMIT ' . $conditions['start'] . ',' . $conditions['limit'];
        } elseif (!array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
            $sql .= ' LIMIT ' . $conditions['limit'];
        }

        $query = $this->conn->prepare($sql);
        $query->execute();

        if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
            switch ($conditions['return_type']) {
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        } else {
            if ($query->rowCount() > 0) {
                $data = $query->fetchAll();
            }
        }
        return !empty($data) ? $data : false;
    }

    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table, $data)
    {
        if (!empty($data) && is_array($data)) {
            $columns = '';
            $values = '';
            $i = 0;
            if (!array_key_exists('createdDate', $data)) {
                $data['createdDate'] = date("Y-m-d H:i:s");
            }
            if (!array_key_exists('modifiedDate', $data)) {
                $data['modifiedDate'] = date("Y-m-d H:i:s");
            }

            /*if(!array_key_exists('createdBy',$data)){
                $data['createdBy'] = $_SESSION['user_session'];
            }*/
            $columnString = implode(',', array_keys($data));
            $valueString = ":" . implode(',:', array_keys($data));
            $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
            //$sql=$this->conn->quote($sql);
            $query = $this->conn->prepare($sql);
            foreach ($data as $key => $val) {
                $query->bindValue(':' . $key, $val);
            }
            $insert = $query->execute();
            return $insert ? $this->conn->lastInsertId() : true;
        } else {
            return false;
        }
    }

    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table, $data, $conditions)
    {
        if (!empty($data) && is_array($data)) {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            if (!array_key_exists('modifiedDate', $data)) {
                $data['modifiedDate'] = date("Y-m-d H:i:s");
            }
            if (!array_key_exists('createdBy', $data)) {
                $data['createdBy'] = $_SESSION['user_session'];
            }
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colvalSet .= $pre . $key . "='" . $val . "'";
                $i++;
            }
            if (!empty($conditions) && is_array($conditions)) {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach ($conditions as $key => $value) {
                    $pre = ($i > 0) ? ' AND ' : '';
                    $whereSql .= $pre . $key . " = '" . $value . "'";
                    $i++;
                }
            }
            $sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
            //$sql=$this->conn->quote($sql);
            $query = $this->conn->prepare($sql);
            $update = $query->execute();
            return $update ? $query->rowCount() : false;
        } else {
            return false;
        }
    }

    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table, $conditions)
    {
        $whereSql = '';
        if (!empty($conditions) && is_array($conditions)) {
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach ($conditions as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $whereSql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }
        $sql = "DELETE FROM " . $table . $whereSql;
        $delete = $this->conn->exec($sql);
        return $delete ? $delete : false;
    }


    public function doLogin($uname, $upass)
    {
        try {
            $stmt = $this->conn->prepare("SELECT u.userID,userName,password,status,roleID FROM users u,userroles ur WHERE u.userID=ur.userID and userName=:uname and status=:st");
            $stmt->execute(array(':uname' => $uname, 'st' => 1));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                if ($userRow['password'] === $this->PwdHash($upass, substr($userRow['password'], 0, 9))) {
                    $_SESSION['user_session'] = $userRow['userID'];
                    $_SESSION['role_session'] = $userRow['roleID'];
                    //$_SESSION['user_privilege']=$userRow['user_privilege'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function is_loggedin()
    {
        if (isset($_SESSION['user_session'])) {
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
    public function getData($table, $attrName, $id, $id2)
    {
        try {
            $query = $this->getRows($table, array('where' => array($id => $id2), ' order_by' => $attrName . '  ASC'));
            if (!empty($query)) {
                foreach ($query as $q) {
                    $attrName = $q[$attrName];
                }
                return $attrName;
            }
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function isFieldExist($table, $field, $field2)
    {
        try {
            $query = $this->getRows($table, array('where' => array($field => $field2), 'order_by' => $field . ' ASC'));
            if (!empty($query)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    //testing only
    public function isIndexNumberExist($indexNumber, $admissionID)
    {
        try {
            $query = $this->conn->prepare("SELECT indexNumber from applicantresults where indexNumber=:inumber and admissionID=:admID");
            $query->execute(array(':inumber' => $indexNumber, ':admID' => $admissionID));
            //$row=$query->fetch(PDO::FETCH_ASSOC);
            //$row=$query->rowCount();
            $row = $query->fetchAll();
            if (count($row) > 0)
                //if(!empty($query))
                //if($row>0)
            {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    //Subject Exist or not
    public function isSubjectExist($arid, $ssid)
    {
        try {
            $query = $this->conn->prepare("SELECT * from applicantsubjects where applicantResultID=:id and subjectID=:sid");
            $query->execute(array(':id' => $arid, ':sid' => $ssid));
            $row = $query->fetchAll();
            if (count($row) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//Start Admission System
    public function getSubject($applicantResultID, $str)
    {
        try {
            $query = $this->conn->prepare("SELECT subjectID,subjectName from subjects where subjectID  NOT IN(SELECT subjectID from applicantsubjects where applicantResultID=:appResID) and stream=:st and status=:ss");
            $query->execute(array(':appResID' => $applicantResultID, ':st' => $str, ':ss' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getAlevelSubject($applicantResultID)
    {
        try {
            $query = $this->conn->prepare("SELECT subjectID,subjectName from subjects where subjectID  NOT IN(SELECT subjectID from applicantSubjects where applicantResultID=:appResID) and stream=:st and status=:ss");
            $query->execute(array(':appResID' => $applicantResultID, ':st' => 2, ':ss' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getRemarks()
    {
        try {
/*            $query = $this->conn->prepare("SELECT remarkID,remark from remarks where remarkID <> 6 AND remarkID<>3");*/
            $query = $this->conn->prepare("SELECT remarkID,remark from remarks");
            $query->execute();
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicantsApproved($pID, $acadID, $admID, $entry,$roundname)
    {
        try {
            if($roundname=='all')
            {
                $query = $this->conn->prepare("SELECT 
    DISTINCT(a.applicantID), firstName, middleName, lastName, gender
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
    a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	and p.programID=pm.programmeID 
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        AND a.admissionID = :aid
        and choice = :chc
        and applicantsRemarksID = :remarkID
        and entryQualification=:entry
       ");
                $query->execute(array(':progID' => $pID, ':appYearID' => $acadID, ':aid' => $admID, ':chc' => 1, 'remarkID' => 2, ':entry' => $entry));
            }
            else
            {
                $query = $this->conn->prepare("SELECT 
    DISTINCT(a.applicantID), firstName, middleName, lastName, gender
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
    a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	and p.programID=pm.programmeID 
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        AND a.admissionID = :aid
        and choice = :chc
        and applicantsRemarksID = :remarkID
        and entryQualification=:entry
        AND admissionRound=:round");
                $query->execute(array(':progID' => $pID, ':appYearID' => $acadID, ':aid' => $admID, ':chc' => 1, 'remarkID' => 2, ':entry' => $entry,':round'=>$roundname));
            }$data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicantsApplicants($pID, $acadID, $admID, $entry)
    {
        try {
            $query = $this->conn->prepare("SELECT 
    DISTINCT(a.applicantID), firstName, middleName, lastName, gender
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
    a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	and p.programID=pm.programmeID 
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        AND a.admissionID = :aid
        and choice = :chc
        and entryQualification=:entry");
            $query->execute(array(':progID' => $pID, ':appYearID' => $acadID, ':aid' => $admID, ':chc' => 1, ':entry' => $entry));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//get all selected applicants
    public function getSelectedList($pID, $acadID, $admID)
    {
        try {
            if ($pID == 1) {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID));
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND aa.admissionStatus = :adminStatus
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:adminID");
                $query->execute(array(':adminStatus' => 1, ':studyc' => 2, ':studyd' => 3, ':studydd' => 4, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    //registered_report
    public function getRegisteredList($pID, $acadID, $admID)
    {
        try {
            if ($pID == 1) {
               /* $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),registrationNumber, firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,a.tcu_status
        from
            applicants a,
            applicantregistration aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeID = pm.programmeMajorID
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            order by tcu_status ASC");
                $query->execute(array(':studyID' => 1, ':remarkID' => 6, ':appYearID' => $acadID, ':adminID' => $admID));*/

                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),registrationNumber, firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,nextOfKinName,nextOfKinPhoneNumber,a.tcu_status
        from
            applicants a,
            applicantregistration aa
        where
            a.applicantID=aa.applicantID
            AND aa.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            order by a.tcu_status ASC");
                $query->execute(array(':studyID' => 1, ':remarkID' => 6, ':appYearID' => $acadID, ':adminID' => $admID));

            } else {
                /*$query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID),registrationNumber, firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID
            from
                applicants a,
                applicantregistration aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeID = pm.programmeMajorID
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:adminID");
                $query->execute(array(':studyc' => 2, ':studyd' => 3, ':studydd' => 4, ':remarkID' => 6, ':appYearID' => $acadID, ':adminID' => $admID));*/
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID),registrationNumber, firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,nextOfKinName,nextOfKinPhoneNumber
            from
                applicants a,
                applicantregistration aa
                
            where
                a.applicantID=aa.applicantID
                and (aa.studyLevelID=:studyc or aa.studyLevelID=:studyd or aa.studyLevelID=:studydd)
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:adminID");
                $query->execute(array(':studyc' => 2, ':studyd' => 3, ':studydd' => 4, ':remarkID' => 6, ':appYearID' => $acadID, ':adminID' => $admID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    //getTransfer
    public function getTransferList($pID, $acadID, $admID)
    {
        try {
            if ($pID == 1) {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and transferStatus=:tstatus
            and applicationYearID=:appYearID
            and admissionID=:adminID
            order by tcu_status ASC");
                $query->execute(array(':studyID' => 1, ':tstatus' => 1, ':appYearID' => $acadID, ':adminID' => $admID));
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and transferStatus=:tstatus
                and applicationYearID=:appYearID
                and admissionID=:adminID");
                $query->execute(array(':studyc' => 2, ':studyd' => 3, ':studydd' => 4, ':tstatus' => 1, ':appYearID' => $acadID, ':adminID' => $admID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getSubmitUnSelectedListTCU($pID, $acadID, $admID)
    {
        try {
            if ($pID == 1) {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,tcu_status,citizenship,tcu_final,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and (applicantsRemarksID=:remarkID or applicantsRemarksID=:remark2_ID or applicantsRemarksID=:remark3_ID)
            and applicationYearID=:appYearID
            and admissionID=:adminID
            AND NULLIF(tcu_final, '') IS NULL 
            order by tcu_status ASC");
                $query->execute(array(':studyID' => 1, ':remarkID' => 2,':remark2_ID'=>4,':remark3_ID'=>5, ':appYearID' => $acadID, ':adminID' => $admID));
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and (applicantsRemarksID=:remarkID or applicantsRemarksID=:remark2_ID or applicantsRemarksID=:remark3_ID or applicantsRemarksID=:remark4_ID)
                and applicationYearID=:appYearID
                and admissionID=:adminID");
                $query->execute(array(':studyc' => 2, ':studyd' => 3, ':studydd' => 4, ':remark2_ID'=>4,':remark3_ID'=>5,':remark4_ID'=>1, ':appYearID' => $acadID, ':adminID' => $admID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }



    public function getSubmitSelectedListTCU($pID, $acadID, $admID)
    {
        try {
            if ($pID == 1) {
                /*$query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,tcu_status,citizenship,tcu_final,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            AND (tcu_status=:tstatus)
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID,':tstatus'=>31));
                */
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,tcu_status,citizenship,tcu_final,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID));
                /* $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,tcu_status,citizenship,tcu_final,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
        
            AND NULLIF(tcu_final, '') IS NULL 
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID)); */
                /*                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID,':qual'=>'%Qualified%',':mpt'=>'%Multiple Admission%',':conf'=>'%confirmed%'));*/

                /*$query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID));*/
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND aa.admissionStatus = :adminStatus
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:adminID");
                $query->execute(array(':adminStatus' => 1, ':studyc' => 2, ':studyd' => 3, ':studydd' => 4, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    //get non degree applicants TCU
    public function getSubmitSelectedListNonDegreeTCU($admID)
    {
        try {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND aa.admissionStatus = :adminStatus
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd)
                and (applicantsRemarksID=:remarkID1 or applicantsRemarksID=:remarkID2 or applicantsRemarksID=:remarkID3)
                and admissionID=:adminID");
                $query->execute(array(':adminStatus' => 1, ':studyc' => 2, ':studyd' => 3, 'remarkID1' => 3, 'remarkID2' => 2,'remarkID3' => 6, ':adminID' => $admID));
            
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//get all applicants
    public function getTCUApplicantList($acadID, $adminID)
    {
        try {
            $query = $this->conn->prepare("
                SELECT DISTINCT
                (a.applicantID),
                firstName,
                middleName,
                lastName,
                gender,
                phoneNumber,
                entryQualification,
                tcu_status
                
            FROM
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            WHERE
                a.applicantID = aa.applicantID
                    AND aa.programmeMajorID = pm.programmeMajorID
                    AND p.programID = pm.programmeID
                    AND p.studyLevelID = :studyID
                    AND applicationYearID = :appYearID
                    AND admissionID = :adminID
                    AND choice=:chc
                    order by tcu_status ASC");
            $query->execute(array(':studyID' => 1, ':appYearID' => $acadID, ':adminID' => $adminID,':chc'=>1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


//
    public function getApprovedList($pID, $acadID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,
physicalAddress,phoneNumber,email,nextOfKinName,nextOfKinPhoneNumber,nextOfKinAddress,relationship
        from
           applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
        a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	    and p.programID=pm.programmeID 
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        and choice = :chc
        and applicantsRemarksID = :remarkID");
            $query->execute(array(':progID' => $pID, ':appYearID' => $acadID, ':chc' => 1, 'remarkID' => 2));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//getAdmitted for NACTE
    public function getNacteAdmittedList($pID, $acadID,$adminID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,
physicalAddress,phoneNumber,email,districtID,nextOfKinName,nextOfKinPhoneNumber,nextOfKinAddress,relationship
        from
           applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
        a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	    and p.programID=pm.programmeID
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        and a.admissionID = :admID
        and choice = :chc
        and applicantsRemarksID = :remarkID");
            $query->execute(array(':progID' => $pID, ':appYearID' => $acadID,':admID'=>$adminID,':chc' => 1, 'remarkID' => 2));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    //getApplicantNACTE
    public function getApplicantNacteAdmittedList($applicantID,$pID, $acadID,$adminID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,
physicalAddress,phoneNumber,email,districtID,nextOfKinName,nextOfKinPhoneNumber,nextOfKinAddress,relationship
        from
           applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
        a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	    and p.programID=pm.programmeID
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        and a.admissionID = :admID
        and a.applicantID=:appID
        and choice = :chc
        and applicantsRemarksID = :remarkID");
            $query->execute(array(':progID' => $pID, ':appYearID' => $acadID,':admID'=>$adminID,':appID'=>$applicantID,':chc' => 1, 'remarkID' => 2));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//get all applicants
    public function getApplicantsList($pID, $acadID, $adminID,$remark)
    {
        try {
            if ($pID == 1) {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,districtID
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.choice = :chc
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID>=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:admiID");
                $query->execute(array(':chc' => 1, ':studyID' => 1, 'remarkID' => $remark, ':appYearID' => $acadID, ':admiID' => $adminID));
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,districtID
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND aa.choice = :chc
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and applicantsRemarksID>=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:admiID");
                $query->execute(array(':chc' => 1, ':studyc' => 2, ':studyd' => 3, ':studydd' => 4, 'remarkID' => $remark, ':appYearID' => $acadID, ':admiID' => $adminID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }



    //get pending applicants
    public function getPendingApplicantsList($pID, $acadID, $adminID,$remark)
    {
        try {
            if ($pID == 1) {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,districtID
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.choice = :chc
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:admiID");
                $query->execute(array(':chc' => 1, ':studyID' => 1, 'remarkID' => $remark, ':appYearID' => $acadID, ':admiID' => $adminID));
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,phoneNumber,districtID
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND aa.choice = :chc
                AND p.programID = pm.programmeID
                and (p.studyLevelID=:studyc or p.studyLevelID=:studyd or p.studyLevelID=:studydd)
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:admiID");
                $query->execute(array(':chc' => 1, ':studyc' => 2, ':studyd' => 3, ':studydd' => 4, 'remarkID' => $remark, ':appYearID' => $acadID, ':admiID' => $adminID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicantByProgrammes($pID,$adminID)
    {
        try {
            $query = $this->conn->prepare("SELECT 
    DISTINCT(a.applicantID), a.firstName, a.middleName, a.lastName, a.gender,a.phoneNumber, refNumber,userName,applicantsRemarksID
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm,
    users u
where
        a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
        and aa.choice=:chc
        and p.programID=pm.programmeID 
        and pm.programmeID = :prog
        and a.admissionID=:adminID
        and a.userID=u.userID
        and applicantsRemarksID<>0");
            $query->execute(array(':chc' => 1, ':prog' => $pID,':adminID'=>$adminID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//Approved by Major
    public function getApplicantsApprovedByMajor($pID, $acadID, $entry)
    {
        try {
            $query = $this->conn->prepare("SELECT 
    DISTINCT(a.applicantID), firstName, middleName, lastName, gender
from
    applicants a,
    applicantapplication aa,
    programmemajor pm,
    programs p
where
    a.applicantID = aa.applicantID
    and p.programID=pm.programmeID
    and pm.programmeMajorID=aa.programmeMajorID
    and aa.programmeMajorID=:progID
    and a.applicationYearID = :appYearID
    and choice = :chc
    and applicantsRemarksID = :remarkID
    and entryQualification=:entry");
            $query->execute(array(':progID' => $pID, ':appYearID' => $acadID, ':chc' => 1, 'remarkID' => 2, ':entry' => $entry));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    /*public function getApplicantsApprovedByMajor($pID,$acadID,$entry)
{
    try
    {
    $query=$this->conn->prepare("SELECT
    DISTINCT(a.applicantID), firstName, middleName, lastName, gender
from
    applicants a,
    applicantapplication aa,
    programmemajor pm
where
    a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
        and aa.programmeMajorID=:progID
        and a.applicationYearID = :appYearID
        and choice = :chc
        and applicantsRemarksID = :remarkID
        and entryQualification=:entry");
    $query->execute(array(':progID'=>$pID,':appYearID'=>$acadID,':chc'=>1,'remarkID'=>2,':entry'=>$entry));
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
}*/

    public function getTestTCUAdmitted($pID, $acadID, $entry)
    {
        try {
            $query = $this->conn->prepare("SELECT 
    a.applicantID, firstName, middleName, lastName, gender
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
    a.applicantID = aa.applicantID
        and pm.programmeMajorID=aa.programmeMajorID
	and p.programID=pm.programmeID 
        and pm.programmeID = :progID
        and a.applicationYearID = :appYearID
        and choice = :chc
        and applicantsRemarksID = :remarkID
        and entryQualification=:entry");
            $query->execute(array(':progID' => $pID, ':appYearID' => $acadID, ':chc' => 1, 'remarkID' => 2, ':entry' => $entry));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getProgramme($appID, $choice)
    {
        try {
            $query = $this->conn->prepare("SELECT 
   p.programID,programCode,programName
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID 
        and pm.programmeMajorID=aa.programmeMajorID
        and a.applicantID = aa.applicantID
        and aa.applicantID=:appID
        and choice = :chc
        ");
            $query->execute(array(':appID' => $appID, ':chc' => $choice));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getAdmittedProgramme($appID, $choice)
    {
        try {
            $query = $this->conn->prepare("SELECT
   p.programID,pm.programmeMajorID,pm.programmeMajor,major,programCode,programName,studyLevelID
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        and a.applicantID = aa.applicantID
        and aa.applicantID=:appID
        
        and admissionStatus=:status
        ");
            $query->execute(array(':appID' => $appID, ':status' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }



    public function getTransfferedProgramme($appID, $choice)
    {
        try {
            $query = $this->conn->prepare("SELECT
   p.programID,pm.programmeMajorID,pm.programmeMajor,programCode,programName,studyLevelID
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        and a.applicantID = aa.applicantID
        and aa.applicantID=:appID
        and choice = :chc
        ");
            $query->execute(array(':appID' => $appID, ':chc' => $choice));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getQualification($applicantID)
    {
        try {
            $query = $this->conn->prepare("SELECT qualificationTypeID from applicantstudylevel where applicantID=:appID");
            $query->execute(array(':appID' => $applicantID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['qualificationTypeID'];
            return $value;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getCompulsorySubjects($programmeMajorID)
    {
        try {
            $query = $this->conn->prepare("SELECT subjectID from subjectrequirements sr,programrequirements pr,programmemajor pm where pm.programmeMajorID=pr.programmeMajorID and pm.programmeMajorID=:progMajID and pr.programRequirementID=sr.programmeRequirementID  and subjectType=:type");
            $query->execute(array(':progMajID' => $programmeMajorID, ':type' => 'compulsory'));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getCompulsoryGrade($programmeMajorID)
    {
        try {
            $query = $this->conn->prepare("SELECT compulsorySubjectGrade from programrequirements pr,programmemajor pm where pm.programmeMajorID=pr.programmeMajorID and pm.programmeMajorID=:progMajID");
            $query->execute(array(':progMajID' => $programmeMajorID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $data = $row['compulsorySubjectGrade'];
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getAppSubjectPoint($applicantID, $subjectID)
    {
        try {
            $query = $this->conn->prepare("SELECT  points from applicantsubjects a,applicantresults ar where ar.applicantResultID=a.applicantResultID and ar.applicantID=:appID and subjectID=:subID");
            $query->execute(array(':appID' => $applicantID, ':subID' => $subjectID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['points'];
            return $value;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    public function getApplicantSubjects($applicantID, $studyID)
    {
        try {
            if ($studyID == 3) {
                $examinationLevel = "Ordinary";
            } else {
                $examinationLevel = "Advance";
            }
            $query = $this->conn->prepare("SELECT subjectID from applicantsubjects aps,applicantresults ar where ar.applicantResultID=aps.applicantResultID and ar.applicantID=:appID and ar.examinationLevel=:level and status=:st");
            $query->execute(array(':appID' => $applicantID, ':level' => $examinationLevel, ':st' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicantGrade($applicantID)
    {
        try {
            $qualificationType = $this->getQualification($applicantID);
            if ($qualificationType == 6) {
                $examinationLevel = "Ordinary";
            } else if ($qualificationType == 7) {
                $examinationLevel = "Advance";
            }
            $query = $this->conn->prepare("SELECT gradeID from applicantsubjects aps,applicantresults ar where ar.applicantResultID=aps.applicantResultID and ar.applicantID=:appID and ar.examinationLevel=:level");
            $query->execute(array(':appID' => $applicantID, ':level' => $examinationLevel));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//Not Used by Anything just for test only
    public function haveCompulsoryPoints($programmeMajorID, $applicantID)
    {
        $found = false;
        $applicantSubjects = $this->getApplicantSubjects($applicantID);
        //print_r($applicantSubjects[0]);
        $progCompulsorySubjects = $this->getCompulsorySubjects($programmeMajorID);
        $compGrade = $this->getCompulsoryGrade($programmeMajorID);
        $compPoints = $this->getData("grades", "gradePoint", "gradeID", $compGrade);
        $gradePoints = $this->getAppSubjectPoint($applicantID, $appSubjectID);

        foreach ($progCompulsorySubjects as $pcsubjects) {
            $compSubject = $pcsubjects['subjectID'];
            // echo $compSubject."<br>";
            if (in_array($pcsubjects, $applicantSubjects)) {

                foreach ($applicantSubjects as $item) {
                    $applicantSubjectID = $item['subjectID'];
                    if ($compSubject == $applicantSubjectID) {
                        //echo $applicantSubjectID."<br>";
                        $found = true;
                    }
                }
            }


        }
        return $found;
    }

//checkout if programme has compulsory subject or not
    public function checkCompulsorySubjects($programmeMajorID)
    {
        try {
            $found = false;
            $query = $this->conn->prepare("SELECT compulsorySubject from programrequirements pr,programmemajor pm where pm.programmeMajorID=pr.programmeMajorID and pm.programmeMajorID=:progMajID");
            $query->execute(array(':progMajID' => $programmeMajorID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            if ($row['compulsorySubject'] == 1)
                $found = true;
            else
                $found = false;
            return $found;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function haveCompulsorySubjects($programmeMajorID, $applicantID, $studyID)
    {
        try {
            $found = false;
            $applicantSubjects = $this->getApplicantSubjects($applicantID, $studyID);
            $progCompulsorySubjects = $this->getCompulsorySubjects($programmeMajorID);
            foreach ($progCompulsorySubjects as $pcsubjects) {
                if (in_array($pcsubjects, $applicantSubjects)) {
                    $compSubjectID = $pcsubjects['subjectID'];
                    $compGrade = $this->getCompulsoryGrade($programmeMajorID);
                    $compPoints = $this->getData("grades", "gradePoint", "gradeID", $compGrade);
                    foreach ($applicantSubjects as $item) {
                        $applicantSubjectID = $item['subjectID'];
                        $gradePoints = $this->getAppSubjectPoint($applicantID, $applicantSubjectID);
                        if($compSubjectID == $applicantSubjectID) {
                            if($gradePoints >= $compPoints) {
                                $found = true;
                                break;
                            }/* else {
                                $found = false;
                                continue;
                            }*/
                        }
                    }
                    //$found = true;
                    break;
                } else {
                    $found = false;
                    break;
                }
            }
            return $found;
        } catch (PDOException $ex) {
            echo "Getting Data Error:" . $ex->getMessage();

        }
    }

    public function isEquavalentEntry($appliID)
    {
        try {
            $query = $this->conn->prepare("SELECT COUNT(applicantID) as number from applicants where applicantID=:appID and entryqualification=:entry");
            $query->execute(array(':appID' => $appliID, ':entry' => 1));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['number'];
            if ($value > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getGradeType($appliID)
    {
        try {
            $query = $this->conn->prepare("SELECT gradeType  from applicantresults where applicantID=:appID and entryQualification=:entry");
            $query->execute(array(':appID' => $appliID, 'entry' => 1));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['gradeType'];
            return $value;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicantGPA($appliID)
    {
        try {
            $query = $this->conn->prepare("SELECT gradePoints  from applicantresults where applicantID=:appID and entryQualification=:entry");
            $query->execute(array(':appID' => $appliID, 'entry' => 1));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['gradePoints'];
            return $value;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    /*public function getProgrammeGPA($programmeMajorID)
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
}*/

    public function getProgrammeMajor($levelID)
    {
        try {
            $query = $this->conn->prepare("SELECT programmeMajorID,programmeMajor from programmemajor pm,programs p,studylevels s where p.programID=pm.programmeID and s.studyLevelID=p.studyLevelID and p.studyLevelID=:stdLvlID and p.programStatus=:status and pm.publishStatus=:pstatus");
            $query->execute(array(':stdLvlID' => $levelID, ':status' => 1, ':pstatus' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//getallprogrammesin
    public function getProgrammes()
    {
        try {
            $query = $this->conn->prepare("SELECT programmeMajorID,programmeMajor from programmemajor pm,programs p where p.programID=pm.programmeID and p.programStatus=:status");
            $query->execute(array(':status' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


//gettingStudyLevelID
    public function getStudyLevelID($programmeMajorID)
    {
        try {
            $query = $this->conn->prepare("SELECT 
            programmeMajor,programDuration,schoolCode,regCode,schoolName,p.studyLevelID
        from
            schools s,
            departments d,
            programmemajor pm,
            programs p,
            studylevels l
        where
                s.schoolID=d.schoolID
                AND d.departmentID=p.departmentID
                AND p.programID = pm.programmeID
                AND l.studyLevelID=p.studyLevelID
                AND programmeMajorID =:pMajorID");
            $query->execute(array(':pMajorID' => $programmeMajorID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    //get Only StudyLevelID
    public function getStudyLevelIDData($programmeMajorID)
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
        try {
            $query = $this->conn->prepare("SELECT programmeMajorID,programmeMajor from programmemajor pm,programs p where p.programID=pm.programmeID and programmeMajorID NOT IN(SELECT programmeMajorID from programrequirements)");
            $query->execute();
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }
    public function getProgrammeRequirement()
    {
        try {
            $query = $this->conn->prepare("SELECT programRequirementID,
    pm.programmeMajorID, programmeMajor
FROM
    programmemajor pm,
    programs p,
    programrequirements pr
WHERE
    p.programID = pm.programmeID
        AND pm.programmeMajorID = pr.programmeMajorID");
            $query->execute();
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    public function getProgrammeName()
    {
        try {
            $query = $this->conn->prepare("SELECT programID,programName from programs");
            $query->execute();
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    // public function getDistinctProgrammeFees()
    // {
    //     try {
    //         $query = $this->conn->prepare("SELECT DISTINCT(pf.programID),programName,academicYearID from programs p, programmefees pf where p.programID=pf.programID");
    //         $query->execute();
    //         $data = array();
    //         while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    //             $data[] = $row;
    //         }
    //         return $data;

    //     } catch (PDOException $ex) {
    //         echo "Getting Data Error: " . $ex->getMessage();
    //     }
    // }

    public function getDistinctProgrammeFees()
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT(pf.programID),programmeMajor,academicYearID from programmemajor p, programmefees pf where p.programmeMajorID=pf.programID");
            $query->execute();
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }


    /* public function getProgrammeFees($programmeID)
    {
        try {

            $query = $this->conn->prepare("SELECT DISTINCT(pf.programID),programName,academicYearID from programs p, programmefees pf where p.programID=pf.programID and pf.programID=:pID");
            $query->execute(array(':pID' => $programmeID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    } */

    public function getProgrammeFees($programmeID)
    {
        try {

            $query = $this->conn->prepare("SELECT DISTINCT(pf.programID),programmeMajor,academicYearID from programmemajor p, programmefees pf where p.programmeMajorID=pf.programID and pf.programID=:pID");
            $query->execute(array(':pID' => $programmeID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    public function getPrintedProgrammeFees($programmeMajorID)
    {
        try {

            $query = $this->conn->prepare("SELECT DISTINCT(pf.programID) as programID,programName,academicYearID from programs p,programmemajor pm, programmefees pf where p.programID=pm.programmeID and p.programID=pf.programID and pm.programmeMajorID=:pMajorID");
            $query->execute(array(':pMajorID' => $programmeMajorID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

//checkout programme qualification
    public function checkProgrammeChoice($programmeMajorID, $applicantID)
    {
        try {
            $status = FALSE;
            if ($this->isEquavalentEntry($applicantID)) {
                if ($this->getGradeType($applicantID) == "GPA") {
                    $gpagrade = $this->getApplicantGPA($applicantID);
                    if ($gpagrade >= 3) {
                        $status = TRUE;
                    }
                } else {
                    $gpagrade = $this->getApplicantGPA($applicantID);

                }
            } else {
                $nPassGrade = $this->getData("programrequirements", "numberOfPassGrade", "programmeMajorID", $programmeMajorID);
                $appPoints = $this->getSumPoints($nPassGrade, $applicantID);
                $pointsRequired = $this->getData("programrequirements", "pointsRequired", "programmeMajorID", $programmeMajorID);
                $subjectCount = $this->getSubjectCount($applicantID);
                if ($this->getQualification($applicantID) == 7) {
                    if ($appPoints >= $pointsRequired) {
                        if ($this->checkCompulsorySubjects($programmeMajorID)) {
                            if ($this->haveCompulsorySubjects($programmeMajorID, $applicantID)) {
                                $status = TRUE;
                            }
                        } else {
                            $status = True;
                        }
                    }
                } else if ($this->getQualification($applicantID) == 6) {
                    if ($subjectCount >= $nPassGrade) {
                        if ($this->checkCompulsorySubjects($programmeMajorID)) {
                            if ($this->haveCompulsorySubjects($programmeMajorID, $applicantID)) {
                                $status = TRUE;
                            }
                        } else {
                            $status = True;
                        }
                    }
                }
            }
            return $status;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//Getting Applicants Subject Points
    /*public function getProgrammeChoice($studyID,$applicantID)
{
    try
    {
    $data=array();
    if($this->isEquavalentEntry($applicantID))
    {
        if($this->getGradeType($applicantID)=="GPA")
        {
            $gpagrade=$this->getApplicantGPA($applicantID);
            $query=$this->conn->prepare("SELECT pm.programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and :gpa >= pr.equivalentEntryGPA and programStatus=:status");
            $query->execute(array(':levelID'=>$studyID,':gpa'=>$gpagrade,'status'=>1));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
        }
        else
        {
            $gpagrade=$this->getApplicantGPA($applicantID);
            $query=$this->conn->prepare("SELECT pm.programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and programStatus=:status");
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
}*/

    public function getApplicantByAgents($agentID, $academicYear,$admissionID)
    {
        try {
            $query = $this->conn->prepare("SELECT
    DISTINCT(a.applicantID),programName,userName, a.firstName, a.middleName, a.lastName, a.gender,a.phoneNumber,refNumber,applicantsRemarksID
from
    applicants a,
    applicantapplication ar,
    programs p,
    programmemajor pm,
    users u
where
        a.applicantID=ar.applicantID
        AND pm.programmeMajorID=ar.programmeMajorID
        AND p.programID=pm.programmeID
        AND ar.choice=:chc
        AND u.userID=a.userID
        AND agentID=:agentD
        and applicantsRemarksID<>0
        and applicationYearID=:yearID
        and admissionID=:adminID");
            $query->execute(array(':chc' => 1,':agentD' => $agentID, ':yearID' => $academicYear,':adminID'=>$admissionID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    //temporary getProgrammeChoice

    //end of temporary

    public function getProgrammeChoice($studyID, $applicantID)
    {
        try {
            $data = array();
            if ($studyID == 3) {
                $programmeMajor = $this->getProgrammeMajor($studyID);
                foreach ($programmeMajor as $proMajor) {
                    $programmeMajorID = $proMajor['programmeMajorID'];
                    //$nPassGrade = $this->getData("programrequirements", "numberOfPassGrade", "programmeMajorID", $programmeMajorID);
                    //$appPoints = $this->getSumPoints($nPassGrade, $applicantID);
                    //$pointsRequired = $this->getData("programrequirements", "pointsRequired", "programmeMajorID", $programmeMajorID);
                    //$subjectCount = $this->getSubjectCount($applicantID, $studyID);
                    //if (($subjectCount >= $nPassGrade) || ($appPoints >= $pointsRequired)) {
                        //if ($this->haveCompulsorySubjects($programmeMajorID, $applicantID, $studyID)) {
                            $query = $this->conn->prepare("SELECT 
                            programmeMajorID, programmeMajor
                        FROM
                            programs p,
                            programmemajor pm
                        WHERE
                            p.programID = pm.programmeID
                                AND pm.programmeMajorID = :pMajorID
                                AND programStatus = :status");
                            $query->execute(array(':pMajorID' => $programmeMajorID, 'status' => 1));
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $data[] = $row;
                            }
                       // }
                    //}
                }

            } else {
                if ($this->isEquavalentEntry($applicantID)) {
                    if ($this->getGradeType($applicantID) == "GPA") {
                        $gpagrade = $this->getApplicantGPA($applicantID);
                       $query = $this->conn->prepare("SELECT pm.programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and :gpa >= pr.equivalentEntryGPA and publishStatus=:status");
                        $query->execute(array(':levelID' => $studyID, ':gpa' => $gpagrade, 'status' => 1));
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $data[] = $row;
                        }
                    } else {
                        $gpagrade = $this->getApplicantGPA($applicantID);
                        $query = $this->conn->prepare("SELECT pm.programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and publishStatus=:status");
                        $query->execute(array(':levelID' => $studyID, ':status' => 1));
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $data[] = $row;
                        }
                    }
                } else {
                    $programmeMajor = $this->getProgrammeMajor($studyID);
                    foreach ($programmeMajor as $proMajor) {
                        $programmeMajorID = $proMajor['programmeMajorID'];
                        //$nPassGrade = $this->getData("programrequirements", "numberOfPassGrade", "programmeMajorID", $programmeMajorID);
                        //$appPoints = $this->getSumPoints($nPassGrade, $applicantID);
                        //$pointsRequired = $this->getData("programrequirements", "pointsRequired", "programmeMajorID", $programmeMajorID);
                        //$subjectCount = $this->getSubjectCount($applicantID, $studyID);
                        //if (($subjectCount >= $nPassGrade) || ($appPoints >= $pointsRequired)) {
                            //if ($this->haveCompulsorySubjects($programmeMajorID, $applicantID, $studyID)) {
                                $query = $this->conn->prepare("SELECT 
                            programmeMajorID, programmeMajor
                        FROM
                            programs p,
                            programmemajor pm
                        WHERE
                            p.programID = pm.programmeID
                                AND pm.programmeMajorID = :pMajorID
                                AND publishStatus = :status");
                                $query->execute(array(':pMajorID' => $programmeMajorID, 'status' => 1));
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    $data[] = $row;
                                }
                            //}
                       // }
                    }
                }
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


//End
    public function programmeChoice($studyID, $applicantID)
    {
        try {
            $programmeMajor = $this->getProgrammeMajor($studyID);
            $data = array();
            foreach ($programmeMajor as $proMajor) {
                $programmeMajorID = $proMajor['programmeMajorID'];
                //$nPassGrade = $this->getData("programrequirements", "numberOfPassGrade", "programmeMajorID", $programmeMajorID);
                //$appPoints = $this->getSumPoints($nPassGrade, $applicantID);
                //$pointsRequired = $this->getData("programrequirements", "pointsRequired", "programmeMajorID", $programmeMajorID);
                //$subjectCount = $this->getSubjectCount($applicantID, $studyID);

                //if ($subjectCount >= $nPassGrade || $appPoints >= $pointsRequired) {
                    $query = $this->conn->prepare("SELECT
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
                    $query->execute(array('majorID' => $programmeMajorID, ':value' => 0, ':status' => 1));
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
                    }

                //}

            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//end

    public function programmePGChoice($studyID, $applicantID)
    {
        try {
            $programmeMajor = $this->getProgrammeMajor($studyID);
            $data = array();
            foreach ($programmeMajor as $proMajor) {
                $programmeMajorID = $proMajor['programmeMajorID'];
                $nPassGrade = $this->getData("programrequirements", "numberOfPassGrade", "programmeMajorID", $programmeMajorID);
                $appPoints = $this->getSumPoints($nPassGrade, $applicantID);
                $pointsRequired = $this->getData("programrequirements", "pointsRequired", "programmeMajorID", $programmeMajorID);
                $subjectCount = $this->getSubjectCount($applicantID, $studyID);

                if ($subjectCount >= $nPassGrade || $appPoints >= $pointsRequired) {
                    $query = $this->conn->prepare("SELECT
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
                    $query->execute(array('majorID' => $programmeMajorID, ':value' => 0, ':status' => 1));
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $data[] = $row;
                    }

                }

            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//for non compulsory programmes
    /*public function programmeChoice($studyID,$applicantID)
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
                //if($this->getQualification($applicantID)==6)
                if($studyID==6)
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
                //else if($this->getQualification($applicantID)==7)
                else if($studyID==7)
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
 }*/
    //This is all points for applicants based on their levels(Advance-Points, Ordinary-Count)
    public function getApplicantPoints($applicantID)
    {
        try {
            $data = array();
            if ($this->getQualification($applicantID) == 6) {
                $elevel = "Ordinary";
            } else {
                $elevel = "Advance";
            }
            $query = $this->conn->prepare("SELECT 
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
            $query->execute(array(':appID' => $applicantID, ':level' => 'Advance'));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }

            //for not multiples
            $queryresult = $this->conn->prepare("SELECT 
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
            $queryresult->execute(array(':appID' => $applicantID, ':level' => 'Advance'));
            while ($row = $queryresult->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }

            return $data;

        } catch (PDOException $ex) {
            echo "Data Error:" . $ex->getMessage();
        }
    }

    //getting applicant points based on their passgrade from programmerequirements
    public function getPassPoints($nPassGrade, $applicantID)
    {
        try {
            $applicantPoints = $this->getApplicantPoints($applicantID);
            rsort($applicantPoints);
            $data = array();
            if (!empty($applicantPoints)) {
                for ($x = 0; $x < $nPassGrade; $x++) {
                    $data[] = $applicantPoints[$x];
                }
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    //getting sum of points
    public function getSumPoints($nPassGrade, $applicantID)
    {
        try {
            $passPoints = $this->getPassPoints($nPassGrade, $applicantID);

            if (!empty($passPoints)) {
                $totalSum = 0;
                foreach ($passPoints as $point) {
                    $totalSum += $point['points'];
                }
            }
            return $totalSum;
        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    public function getSubjectCount($applicantID, $studyID)
    {
        try {
            //$data=array();
            if ($studyID == 3) {
                $elevel = "Ordinary";
            } else {
                $elevel = "Advance";
            }
            $query = $this->conn->prepare("SELECT 
            COUNT(DISTINCT(subjectID)) as subjectCount
        from
            applicantsubjects at,
            applicantresults ar
        where
            ar.applicantResultID = at.applicantResultID
                and ar.applicantID = :appID
                and examinationLevel = :level
                and status=:st");
            $query->execute(array(':appID' => $applicantID, ':level' => $elevel, ':st' => 1));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $subjectCount = $row['subjectCount'];
            }

            return $subjectCount;

        } catch (PDOException $ex) {
            echo "Getting Data Error" . $ex->getMessage();

        }
    }

    public function getCampus($programmeMajorID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
            campusName, campusAddress,accountNumber,accountName,bankName
        from
            campus c,
            programs p,
            programmemajor pm
        where c.campusID=p.campusID
                and p.programID=pm.programmeID
                and pm.programmeMajorID=:majorID");
            $query->execute(array(':majorID' => $programmeMajorID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }

            return $data;
        } catch (PDOException $ex) {
            echo "Getting Data Error" . $ex->getMessage();
        }
    }

    //getprogrammecampus
    public function getProgrammeCampus($programID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            c.campusID
        from
            campus c,
            programs p
        where   c.campusID=p.campusID
                and p.programID=:pID");
            $query->execute(array(':pID' => $programID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $campusID = $row['campusID'];
            }
            return $campusID;
        } catch (PDOException $ex) {
            echo "Getting Data Error" . $ex->getMessage();
        }
    }


    //Selection Data
    public function getSelectionSubjects($applicantID, $examinationLevel)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
    s.subjectID, subjectCode, gradeID, points
FROM
    applicantsubjects aps,
    applicantresults ar,
    subjects s
WHERE
    s.subjectID = aps.subjectID
        AND ar.applicantResultID = aps.applicantResultID
        AND ar.applicantID = :appID
        AND ar.examinationLevel = :level
        AND SCode <> :scode");
            $query->execute(array(':appID' => $applicantID, ':level' => $examinationLevel,':scode'=>'111'));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//get Index/RegNumber
    public function getIndexNumber($applicantID, $examinationLevel)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
    indexNumber,schoolName,examinationAuthority,yearTaken
FROM
    applicantresults ar
WHERE 
        ar.applicantID = :appID
        AND ar.examinationLevel = :level
        ORDER BY yearTaken ASC");
            $query->execute(array(':appID' => $applicantID, ':level' => $examinationLevel));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getFormFourIndexNumber($applicantID, $examinationLevel)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
    indexNumber
FROM
    applicantresults ar
WHERE 
        ar.applicantID = :appID
        AND ar.examinationLevel = :level");
            $query->execute(array(':appID' => $applicantID, ':level' => $examinationLevel));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//modified subjects
    public function getApplicantsSubjects($applicantID, $examinationLevel)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
    DISTINCT(subjectID)
FROM
    applicantsubjects aps,
    applicantresults ar
WHERE
        ar.applicantResultID = aps.applicantResultID
        AND ar.applicantID = :appID
        AND ar.examinationLevel = :level");
            $query->execute(array(':appID' => $applicantID, ':level' => $examinationLevel));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    /*public function getApplicantsSubjects($applicantID,$examinationLevel)
{
    try
    {
        $data=array();
        $query=$this->conn->prepare("SELECT
    subjectID
FROM
    applicantsubjects aps,
    applicantresults ar
WHERE
        ar.applicantResultID = aps.applicantResultID
        AND ar.applicantID = :appID
        AND ar.examinationLevel = :level");
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
}*/

    public function listProgramme($schoolID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
            programID,programName
        from
            programs p,
            schools s,
            departments d
        where
                s.schoolID = d.schoolID
                and d.schoolID=:school
                and d.departmentID = p.departmentID
                order by studyLevelID ASC");
            $query->execute(array(':school' => $schoolID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Data Eroor" . $ex->getMessage();
        }
    }

    public function getSelectionPoints($applicantID, $elevel)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
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
            $query->execute(array(':appID' => $applicantID, ':level' => $elevel));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }

            //for not multiples
            $queryresult = $this->conn->prepare("SELECT 
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
            $queryresult->execute(array(':appID' => $applicantID, ':level' => $elevel));
            while ($row = $queryresult->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }

            return $data;

        } catch (PDOException $ex) {
            echo "Data Error:" . $ex->getMessage();
        }
    }
    //End of Selection Data
    //Dashboard
    public function getCountApplicants($remarkID, $applicationYearID, $admissionID, $gender, $entry)
    {
        try {
            $data = 0;
            if ($gender == "all") {
                $query = $this->conn->prepare("SELECT 
                COUNT(*) as number
            from
                applicants
            where
                applicantsRemarksID =:remark
                AND applicationYearID=:appYearID
                AND admissionID=:aid
                AND gender != ''");
                $query->execute(array(':remark' => $remarkID, ':appYearID' => $applicationYearID, ':aid' => $admissionID));
            } else {
                $query = $this->conn->prepare("SELECT 
                COUNT(*) as number
            from
                applicants
            where
                applicantsRemarksID =:remark
                AND applicationYearID=:appYearID
                AND gender=:sex
                AND entryQualification=:entry
                AND admissionID=:aid");
                $query->execute(array(':remark' => $remarkID, ':appYearID' => $applicationYearID, ':sex' => $gender, ':entry' => $entry, ':aid' => $admissionID));
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data = $row['number'];
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Data Error" . $ex->getMessage();
        }
    }


    public function getSchoolCount($schoolID, $remarkID, $applicationYearID, $admissionID)
    {
        try {
            $data = 0;
            $query = $this->conn->prepare("SELECT 
            count(DISTINCT(a.applicantID)) as number
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm,
            schools s,
            departments d
        where
            s.schoolID = d.schoolID
                and d.schoolID=:school
                and d.departmentID = p.departmentID
                and p.programID = pm.programmeID
                and pm.programmeMajorID = aa.programmeMajorID
                and aa.programmeMajorID<>0
                and choice = :chc
		and a.applicantID=aa.applicantID
		and applicantsRemarksID=:remark
                and applicationYearID=:appYearID
                AND admissionID=:adid");
            $query->execute(array(':school' => $schoolID, ':chc' => 1, ':remark' => $remarkID, ':appYearID' => $applicationYearID, ':adid' => $admissionID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data = $row['number'];
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Data Eroor" . $ex->getMessage();
        }
    }

    //Aggregate by Programmes
    public function getProgrammeCount($programmeID, $remarkID, $applicationYearID, $admissionID)
    {
        try {
            $data = 0;
            $query = $this->conn->prepare("SELECT 
            count(DISTINCT(aa.applicantID)) as number
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            p.programID = pm.programmeID
            and p.programID=:prograID
                and pm.programmeMajorID = aa.programmeMajorID
                and aa.programmeMajorID<>0
                and choice = :chc
		and a.applicantID=aa.applicantID
		and applicantsRemarksID=:remark
                and applicationYearID=:appYearID
                AND admissionID=:aid");
            $query->execute(array(':prograID' => $programmeID, ':chc' => 1, ':remark' => $remarkID, ':appYearID' => $applicationYearID, ':aid' => $admissionID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data = $row['number'];
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Data Eroor" . $ex->getMessage();
        }
    }

    //admit fnct
    public function getApproved($progrID, $choice, $remarksID,$entry, $applicationYearID, $admissionID,$roundname)
    {
        try {
            $data = array();
            if($roundname=='all')
            {
                $query = $this->conn->prepare("SELECT 
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,phoneNumber,dateOfBirth as dob,email,citizenship,disabilityStatus,entryQualification
        from
            applicants a,
            programmemajor pm,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND aa.programmeMajorID=:progMajorID
                AND choice = :chc
                AND applicantsRemarksID=:remark
                AND entryQualification=:entry
                AND applicationYearID=:appYearID
                AND admissionID=:aid");
                $query->execute(array(':progMajorID' => $progrID, ':chc' => $choice, ':remark' => $remarksID, ':entry' => $entry, ':appYearID' => $applicationYearID, ':aid' => $admissionID));
            }
            else 
            {
                $query = $this->conn->prepare("SELECT 
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,phoneNumber,dateOfBirth as dob,email,citizenship,disabilityStatus,entryQualification
        from
            applicants a,
            programmemajor pm,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND aa.programmeMajorID=:progMajorID
                AND choice = :chc
                AND applicantsRemarksID=:remark
                AND entryQualification=:entry
                AND applicationYearID=:appYearID
                AND admissionID=:aid
                AND admissionRound=:round");
                $query->execute(array(':progMajorID' => $progrID, ':chc' => $choice, ':remark' => $remarksID,':entry'=>$entry, ':appYearID' => $applicationYearID, ':aid' => $admissionID,':round'=>$roundname));
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex - getMessage();
        }
    }

    //getadmitetd applicants for registration
    public function getAdmittedForRegistration($progrID, $applicationYearID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender, refNumber,phoneNumber,userID
        from
            applicants a,
            programmemajor pm,
            programs p,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND p.programID=pm.programmeID
                AND pm.programmeID=:progMajorID
                AND applicantsRemarksID=:remark
                AND admissionStatus=:admStatus
                AND applicationYearID=:appYearID");
            $query->execute(array(':progMajorID' => $progrID, ':remark' => 3, ':admStatus' => 1, ':appYearID' => $applicationYearID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex - getMessage();
        }
    }


    public function getRegisteredForRegistration($progrID, $applicationYearID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender, registrationNumber,phoneNumber,userID
        from
            applicants a,
            programmemajor pm,
            programs p,
            applicantregistration ar
        where
            a.applicantID = ar.applicantID
                AND pm.programmeMajorID = ar.programmeID
                AND p.programID=pm.programmeID
                AND pm.programmeID=:progMajorID
                AND applicantsRemarksID=:remark
                AND applicationYearID=:appYearID");
            $query->execute(array(':progMajorID' => $progrID, ':remark' => 6, ':appYearID' => $applicationYearID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex - getMessage();
        }
    }


    //Admitted list for the report
    public function getRejected($progrID, $remarksID, $applicationYearID, $admissionID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT
            a.applicantID, firstName, middleName, lastName, gender, refNumber,phoneNumber,userID
        from
            applicants a,
            programmemajor pm,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND aa.programmeMajorID=:progMajorID
                AND applicantsRemarksID=:remark
                AND applicationYearID=:appYearID
                AND admissionID=:adminID");
            $query->execute(array(':progMajorID' => $progrID, ':remark' => $remarksID, ':appYearID' => $applicationYearID, ':adminID' => $admissionID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }

    //Admitted list for the report
    public function getAdmitted($progrID, $remarksID, $admissionStatus, $applicationYearID, $admissionID,$roundname)
    {

        try {
            $data = array();
            if($roundname=='all')
            {
                $query = $this->conn->prepare("SELECT 
            a.applicantID, firstName, middleName, lastName, gender, refNumber,phoneNumber,userID,nacte_status,tcu_final,tcu_message
        from
            applicants a,
            programmemajor pm,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND aa.programmeMajorID=:progMajorID
                AND applicantsRemarksID=:remark
                AND admissionStatus=:admStatus
                AND applicationYearID=:appYearID
                AND admissionID=:aid");
                $query->execute(array(':progMajorID' => $progrID, ':remark' => $remarksID, ':admStatus' => $admissionStatus, ':appYearID' => $applicationYearID, ':aid' => $admissionID));
            }
            else 
            {
                $query = $this->conn->prepare("SELECT 
            a.applicantID, firstName, middleName, lastName, gender, refNumber,phoneNumber,userID,nacte_status,tcu_final,tcu_message
        from
            applicants a,
            programmemajor pm,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND aa.programmeMajorID=:progMajorID
                AND applicantsRemarksID=:remark
                AND admissionStatus=:admStatus
                AND applicationYearID=:appYearID
                AND admissionID=:aid
                AND admissionRound=:round");
                $query->execute(array(':progMajorID' => $progrID, ':remark' => $remarksID, ':admStatus' => $admissionStatus, ':appYearID' => $applicationYearID, ':aid' => $admissionID,':round'=>$roundname));
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }


    public function getAllAdmitted($progrID, $remarksID, $admissionStatus, $applicationYearID, $admissionID,$roundname)
    {
        try {
            $data = array();
            if($roundname=='all')
            {
                $query = $this->conn->prepare("SELECT 
            a.applicantID, firstName, middleName, lastName, gender, refNumber,phoneNumber,userID,nacte_status,tcu_final,tcu_message
        from
            applicants a,
            programmemajor pm,
            programs p,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND p.programID = pm.programmeID
                AND pm.programmeID=:progID
                AND applicantsRemarksID=:remark
                AND admissionStatus=:admStatus
                AND applicationYearID=:appYearID
                AND admissionID=:aid");
                $query->execute(array(':progID' => $progrID, ':remark' => $remarksID, ':admStatus' => $admissionStatus, ':appYearID' => $applicationYearID, ':aid' => $admissionID));
            }
            else{
                $query = $this->conn->prepare("SELECT 
            a.applicantID, firstName, middleName, lastName, gender, refNumber,phoneNumber,userID,nacte_status,tcu_final,tcu_message
        from
            applicants a,
            programmemajor pm,
            programs p,
            applicantapplication aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeMajorID
                AND p.programID = pm.programmeID
                AND pm.programmeID=:progID
                AND applicantsRemarksID=:remark
                AND admissionStatus=:admStatus
                AND applicationYearID=:appYearID
                AND admissionID=:aid
                AND admissionRound=:round");
                $query->execute(array(':progID' => $progrID, ':remark' => $remarksID, ':admStatus' => $admissionStatus, ':appYearID' => $applicationYearID, ':aid' => $admissionID,':round'=>$roundname));
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }


    public function getAllRegistered($progrID,$applicationYearID, $admissionID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
                DISTINCT(aa.applicantID),registrationNumber, firstName, middleName, lastName, gender,dateOfBirth,phoneNumber,userID,entryQualification,hosteller,sponsor,applicationNumber,citizenship
        from
            applicants a,
            programmemajor pm,
            programs p,
            applicantregistration aa
        where
            a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeID
                AND p.programID = pm.programmeID
                AND pm.programmeID=:progID
                AND applicantsRemarksID=:remark
                AND applicationYearID=:appYearID
                AND admissionID=:aid
                AND regNumber IS NOT NULL");
            $query->execute(array(':progID' => $progrID, ':remark' => 6,':appYearID' => $applicationYearID, ':aid' => $admissionID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }


    public function getAllBatchRegistered($progrID, $admissionID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
                DISTINCT(aa.applicantID),registrationNumber, firstName, middleName, lastName, gender,dateOfBirth,phoneNumber,userID,entryQualification,hosteller,sponsor,applicationNumber,citizenship
        from
            applicants a,
            programmemajor pm,
            programs p,
            applicantregistration aa
        where
                 a.applicantID = aa.applicantID
                AND pm.programmeMajorID = aa.programmeID
                AND p.programID = pm.programmeID
                AND pm.programmeID=:progID
                AND applicantsRemarksID=:remark
                AND admissionID=:aid
                AND regNumber IS NOT NULL");
            $query->execute(array(':progID' => $progrID, ':remark' => 6, ':aid' => $admissionID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }

    public function getAllRegisteredNumber($applicantID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT 
                DISTINCT(registrationNumber)
        from
            applicantregistration
        where
            applicantID=:appID");
            $query->execute(array(':appID' => $applicantID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }

    

    public function calculateColor($applicantID, $studyID, $stat)
    {
        $data = array();
        if ($this->isEquavalentEntry($applicantID)) {
            if ($this->getGradeType($applicantID) == "GPA") {
                $gpagrade = $this->getApplicantGPA($applicantID);
                $query = $this->conn->prepare("SELECT pm.programmeMajorID,programmeMajor from programs p,programrequirements pr,programmemajor pm where p.programID=pm.programmeID and pm.programmeMajorID=pr.programmeMajorID and p.studyLevelID=:levelID and :gpa >= pr.equivalentEntryGPA and programStatus=:status");
                $query->execute(array(':levelID' => $studyID, ':gpa' => $gpagrade, 'status' => $stat));
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    // public function getSumFees($feesType, $programmeID)
    // {
    //     $query = $this->conn->prepare("SELECT SUM($feesType) as sumFees from programmefees pf,programs p where p.programID=pf.programID and pf.programID=:pID and programFeesStatus=:status");
    //     $query->execute(array(':pID' => $programmeID, 'status' => 1));
    //     $row = $query->fetch(PDO::FETCH_ASSOC);
    //     $sumFees = $row['sumFees'];

    //     return $sumFees;
    // }

    public function getSumFees($feesType, $programmeID)
    {
        $query = $this->conn->prepare("SELECT SUM($feesType) as sumFees from programmefees pf,programmemajor p where p.programmemajorID=pf.programID and pf.programID=:pID and programFeesStatus=:status");
        $query->execute(array(':pID' => $programmeID, 'status' => 1));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $sumFees = $row['sumFees'];

        return $sumFees;
    }

    public function getProgrammeMajorID($applicantID)
    {
        try {
            $query = $this->conn->prepare("SELECT programmeMajorID from applicantapplication  where applicantID=:appID and choice=:chc");
            $query->execute(array(':appID' => $applicantID, ':chc' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//Programme Batch
    public function getProgrammeBatch()
    {
        try {
            $query = $this->conn->prepare("SELECT programID from programs where programID NOT IN (SELECT programID from programbatch)");
            $query->execute();
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


//getMaximumNumber
    public function getMaxMUMRegNumber($studyLevelID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            MAX(regNumber) as regNumber
        from
            applicantregistration
        where
                studyLevelID =:stdID");
            $query->execute(array(':stdID' => $studyLevelID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $regNumber = $row['regNumber'];
            }
            return $regNumber;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    //getMaximumNumber
    public function getMaxRegNumber($programmeID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            MAX(regNumber) as regNumber
        from
            applicantregistration
        where
                programmeID =:majorID");
            $query->execute(array(':majorID' => $programmeID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $regNumber = $row['regNumber'];
            }
            return $regNumber;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    public function getMaxSUMAITRegNumber($programmeID,$academicYearID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            MAX(regNumber) as regNumber
        from
            applicantregistration
        where
                programmeID =:majorID and academicYearID=:acadID");
            $query->execute(array(':majorID' => $programmeID,':acadID'=>$academicYearID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $regNumber = $row['regNumber'];
            }
            return $regNumber;
        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

    public function getZUMaxRegNumber($schoolCode)
    {
        try {
            $query = $this->conn->prepare("SELECT
            MAX(regNumber) as regNumber
        from
            applicantregistration
        where
                schoolCode =:code");
            $query->execute(array(':code' => $schoolCode));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $regNumber = $row['regNumber'];
            }
            return $regNumber;
        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }

//getRegisteredApplicants
    public function getRegisteredApplicants($progrID, $applicationYearID,$adminID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),applicantRegistrationID,registrationNumber, firstName, middleName, lastName, gender,dateOfBirth,phoneNumber,userID,entryQualification,hosteller,sponsor,applicationNumber,citizenship
        from
            applicants a,
            programmemajor pm,
            applicantregistration ar
        where
            a.applicantID = ar.applicantID
                AND pm.programmeMajorID = ar.programmeID
                AND ar.programmeID=:progMajorID
                AND academicYearID=:appYearID
                AND admissionID=:adID");
            $query->execute(array(':progMajorID' => $progrID, ':appYearID' => $applicationYearID,':adID'=>$adminID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }

    public function getRegisteredBatchApplicants($progrID,$adminID)
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT
            DISTINCT(ar.applicantID),applicantRegistrationID,registrationNumber, firstName, middleName, lastName, gender,dateOfBirth,phoneNumber,userID,entryQualification,hosteller,sponsor,applicationNumber,citizenship
        from
            applicants a,
            programmemajor pm,
            applicantregistration ar
        where
            a.applicantID = ar.applicantID
                AND pm.programmeMajorID = ar.programmeID
                AND ar.programmeID=:progMajorID
                AND admissionID=:adID
                AND regNumber IS NOT NULL");
            $query->execute(array(':progMajorID' => $progrID,':adID'=>$adminID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error-Data" . $ex->getMessage();
        }
    }


//count number of digits
    public function count_digit($number)
    {
        return strlen((string)$number);
    }

    public function isApplicantIDExist($applicantID)
    {
        try {
                $query = $this->conn->prepare("SELECT applicantID from applicantregistration where applicantID=:inumber");
                $query->execute(array(':inumber' => $applicantID));
                $row = $query->fetchAll();
                if (count($row) > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//get Enrollment Data
//get all selected applicants
    public function getEnrollmentList($pID, $acadID)
    {
        try {
            if ($pID == 1) {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,qualificationTypeID,entryQualification,sponsor,studyLevelID
        from
            applicants a,
            applicantstudylevel aps
        where
            a.applicantID=aps.applicantID
            and aps.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID");
                $query->execute(array(':studyID' => 1, 'remarkID' => 6, ':appYearID' => $acadID));
            } else {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,qualificationTypeID,entryQualification,sponsor,studyLevelID
            from
                applicants a,
                applicantstudylevel aps
            where
                a.applicantID=aps.applicantID
                and (aps.studyLevelID=:studyc or aps.studyLevelID=:studyd or aps.studyLevelID=:studydd)
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID");
                $query->execute(array(':studyc' => 2, ':studyd' => 3, ':studydd' => 4, 'remarkID' => 6, ':appYearID' => $acadID));
            }

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getTCUEnrollmentList($acadID, $admID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,sponsor,registrationNumber,ar.tcu_status
        from
            applicants a,
            applicantregistration ar
        where
            a.applicantID=ar.applicantID
            and applicantsRemarksID=:remarkID
            and applicationYearID =:acadID
            and admissionID =:adid");
            $query->execute(array('remarkID' => 6,':acadID'=>$acadID,':adid'=>$admID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getNHIFReport($admID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName,formfour, lastName,phoneNumber,maritalStatus, gender,dateOfBirth,citizenship,programmeID,registrationNumber,ar.createdDate
        from
            applicants a,
            applicantregistration ar
        where
            a.applicantID=ar.applicantID
            and applicantsRemarksID=:remarkID
            and admissionID =:adid AND regNumber IS NOT NULL");
            $query->execute(array('remarkID' => 6, ':adid' => $admID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicantTCUEnrollmentList($applicantID,$acadID, $admID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,date_format(dateOfBirth,'%d-%m-%Y') as dob,citizenship,disabilityStatus,entryQualification,sponsor,registrationNumber,ar.tcu_status
        from
            applicants a,
            applicantregistration ar
        where
            a.applicantID=ar.applicantID
            AND ar.applicantID=:appID
            and applicantsRemarksID=:remarkID
            and applicationYearID =:acadID
            and admissionID =:adid");
            $query->execute(array(':appID'=>$applicantID,'remarkID' => 6,':acadID'=>$acadID,':adid'=>$admID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    


//getEnrollment Programme
    public function getEnrollmentProgramme($appID, $acadYear)
    {
        try {
            $query = $this->conn->prepare("SELECT
   p.programID,registrationNumber,programCode,programName,major,programmeMajor
from
    applicants a,
    applicantregistration ar,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and p.programID=ar.programmeID
        and a.applicantID = ar.applicantID
        and ar.applicantID=:appID
        and academicYearID=:academicYear
        ");
            $query->execute(array(':appID' => $appID, 'academicYear' => $acadYear));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//getTCUEnrollment Programme
    public function getTCUEnrollmentProgramme($appID)
    {
        try {
            $query = $this->conn->prepare("SELECT
   p.programID,registrationNumber,programCode,programName,major,programmeMajor
from
    applicants a,
    applicantregistration ar,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and p.programID=ar.programmeID
        and a.applicantID = ar.applicantID
        and ar.applicantID=:appID");
            $query->execute(array(':appID' => $appID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//zalongwa export
//get all selected applicants
    public function getRegisteredApplicant($academicYearID)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT
    (a.applicantID),
    registrationNumber,
    programmeID,
    firstName,
    middleName,
    lastName,
    gender,
    dateOfBirth,
    placeOfBirth,
    citizenship,
    physicalAddress,
    postalAddress,
    phoneNumber,
    email,
    nextOfKinName,
    nextOfKinAddress,
    nextOfKinPhoneNumber,
    relationship,
    disabilityStatus,
    employmentStatus,
    sponsor,
    religion,
    hosteller,
    entryQualification,
    refNumber,
    admissionNumber
FROM
    applicants a,
    applicantregistration ar
WHERE
    a.applicantID = ar.applicantID
        AND applicantsRemarksID = :remarkID
        AND academicYearID = :acadID");
            $query->execute(array('remarkID' => 6,':acadID'=>$academicYearID));

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getRegisteredProgramme($appID)
    {
        try {
            $query = $this->conn->prepare("SELECT
   p.programID,registrationNumber,programCode,programName,programmeMajorID,major,programmeMajor
from
    applicants a,
    applicantregistration ar,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and p.programID=ar.programmeID
        and a.applicantID = ar.applicantID
        and ar.applicantID=:appID
        ");
            $query->execute(array(':appID' => $appID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

//this function get API Token from NECTA
    /*public function getAPIToken()
    {

        $tokendata = file_get_contents("https://api.necta.go.tz/api/public/auth/=");
        $token_data = json_decode($tokendata, true);
        $token = $token_data['token'];
        if (strlen($token) > 1)
            $token = $token;
        else
            $token = 0;
        return $token;
    }*/

    public function getAPIToken($token)
    {
        $url = "https://api.necta.go.tz/api/public/auth/".$token;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response_json, true);

        $token = $response['token'];
        if (strlen($token) > 1)
            $token = $token;
        else
            $token = 0;
        return $token;

    }

    public function encrypt($sData)
    {
        $id = (double)$sData * 18293823.45;
        return base64_encode($id);
    }

    public function decrypt($sData)
    {
        $url_id = base64_decode($sData);
        $id = (double)$url_id / 18293823.45;
        return $id;
    }


    public function my_simple_crypt($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = 'hmytechnologies@2017_yahya_mam';
        $secret_iv = 'hmytechnologies@2017_yahya_hamida';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

//count number of grades
    public function countGrades($applicantID, $level)
    {
        try {
            $query = $this->conn->prepare("SELECT COUNT(DISTINCT subjectID) as number from applicantsubjects ab,applicantresults ar  where ar.applicantResultID=ab.applicantResultID AND ar.applicantID=:appID AND examinationLevel=:lvl AND status=:st");
            $query->execute(array(':appID' => $applicantID, ':lvl' => $level, ':st' => 1));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['number'];
            return $value;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getStudyLevels($applicantID)
    {
        try {
            //$query = $this->conn->prepare("SELECT DISTINCT(ar.examinationaLevel),rank from applicantresults ar,examination_level_rank al where al.examinationLevel=ar.examinationLevel and applicantID=:appID group by rank HAVING max(rank)");
            //$query = $this->conn->prepare("SELECT rank from applicantresults ar,examination_level_rank al where al.examinationLevel=ar.examinationLevel and applicantID=:appID group by rank HAVING max(rank)");
            $query = $this->conn->prepare("SELECT DISTINCT examinationLevel from applicantresults where applicantID=:appID ");
            $query->execute(array(':appID' => $applicantID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function getEquivalentStudyLevels($applicantID)
    {
        try {
            $query = $this->conn->prepare("SELECT examinationAuthority from applicantresults where examinationLevel=:elevl AND applicantID=:appID");
            $query->execute(array('elevl' => 'Equivalent', ':appID' => $applicantID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['examinationAuthority'];
            return $value;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getPGStudyLevels($applicantID)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT qualificationID from academic_background where applicantID=:appID ");
            $query->execute(array(':appID' => $applicantID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function getOrganizationValue($value)
    {
        try {
            $query = $this->conn->prepare("SELECT " . $value . " FROM organization");
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row[$value];
            return $value;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function getGradeID($gradeCode, $indexYear, $level)
    {
        try {
            if ($indexYear == 2014 or $indexYear == 2015)
                $gradeRange = 2014;
            else
                $gradeRange = 2013;
            if ($level == "alevel")
                $gradeLevel = 2;
            else
                $gradeLevel = 1;
            $grade = $this->getRows('grades', array('where' => array('gradeCode' => $gradeCode, 'gradeRangeYear' => $gradeRange, 'gradeLevel' => $gradeLevel), 'order_by' => 'gradeID ASC'));
            if (!empty($grade)) {
                //echo "<option value=''>Please Select Here</option>";
                foreach ($grade as $gd) {
                    $gradeID = $gd['gradeID'];
                    return $gradeID;
                }
            }
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function getSubjectID($scode, $stream)
    {
        try {
            $grade = $this->getRows('subjects', array('where' => array('sCode' => $scode, 'stream' => $stream), 'order_by' => 'subjectID ASC'));
            if (!empty($grade)) {
                //echo "<option value=''>Please Select Here</option>";
                foreach ($grade as $gd) {
                    $subjectID = $gd['subjectID'];
                    return $subjectID;
                }
            }
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function getEquivalentData()
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT qualificationTypeID,qualificationName from qualificationtype where qualificationTypeID !=:diploma AND qualificationTypeID!=:ol AND qualificationTypeID!=:al");
            $query->execute(array(':diploma' => 2, ':ol' => 6, ':al' => 7));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function generate_password($length = 20)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789=!@#$%&*';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++)
            $str .= $chars[random_int(6, $max)];

        return $str;
    }

    /*public function getTCUStatus($url, $inumber)
    {
        $user = "SUM";
        $token = "EsQIA1agz9q8UxNSk3DZ";
        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$user&SessionToken=$token&f4indexno=" . $inumber);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }*/

    public function getTCUStatus($url, $xml)
    {

        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data_tcu = curl_exec($ch);
        curl_close($ch);
        return $data_tcu;
    }

    public function sendXmlOverPost($url, $xml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function addApplicantTCU($url, $xml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function SubmitProgrammeChoiceTCU($url, $xml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

    public function checkApplicantStudyLevel($appID)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT
                studyLevelID
            FROM
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            WHERE
                a.applicantID = aa.applicantID
                    AND a.applicantID = :appID
                    AND aa.programmeMajorID = pm.programmeMajorID
                    AND aa.choice = :chc
                    AND p.programID = pm.programmeID");
            $query->execute(array(':appID' => $appID, ':chc' => 1));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $data = $row['studyLevelID'];
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getPublishedProgramme()
    {
        try {
            $query = $this->conn->prepare("SELECT programmeMajorID,programmeMajor from programs p,programmemajor pm where p.programID=pm.programmeID AND p.programStatus=:st order by studyLevelID ASC");
            $query->execute(array(':st' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }


    public function getProgrammeProg($progMajorID)
    {
        try {
            $query = $this->conn->prepare("SELECT 
            programCode
        FROM
            programmemajor pm,
            programs p
        WHERE
            p.programID = pm.programmeID
            AND pm.programmeMajorID=:progMjrID");
            $query->execute(array(':progMjrID' => $progMajorID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $programmeCode = $row['programCode'];
            return $programmeCode;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    //getallprogrammesin
    public function getProgrammeTCU()
    {
        try {
            $query = $this->conn->prepare("SELECT programID,programCode,programName from programs WHERE  studyLevelID=:studyID AND programStatus=:status order by programCode ASC");
            $query->execute(array('studyID' => 1, ':status' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    //Aggregate by Programmes
    public function getProgrammeCountTCU($programmeID, $remarkID, $applicationYearID, $admissionID,$gender)
    {
        try {
            $data = 0;
            $query = $this->conn->prepare("SELECT 
            count(DISTINCT(aa.applicantID)) as number
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            p.programID = pm.programmeID
            and p.programID=:prograID
                and pm.programmeMajorID = aa.programmeMajorID
                and aa.programmeMajorID<>0
		and a.applicantID=aa.applicantID
		and applicantsRemarksID >=:remark
		and gender=:sex
		and choice=:chc
                and applicationYearID=:appYearID
                AND admissionID=:aid");
            $query->execute(array(':prograID' => $programmeID, ':remark' => $remarkID,':sex'=>$gender,':chc'=>1,':appYearID' => $applicationYearID, ':aid' => $admissionID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data = $row['number'];
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Data Eroor" . $ex->getMessage();
        }
    }


    public function checkStatusTCU($programmeID,$acadID, $adminID)
    {
        try {
            $query = $this->conn->prepare("
                SELECT DISTINCT
                (a.applicantID),
                firstName,
                middleName,
                lastName,
                gender,
                phoneNumber,
                entryQualification,
                formfour
                
            FROM
                applicants a,
                applicantapplication aa,
                programmemajor pm
            WHERE
                a.applicantID = aa.applicantID
                    AND pm.programmeMajorID=aa.programmeMajorID
                    AND pm.programmeMajorID=:progID
                    AND choice=:chc
                    AND applicationYearID = :appYearID
                    AND admissionID = :adminID");
            $query->execute(array(':progID'=>$programmeID,':chc'=>1,':appYearID' => $acadID, ':adminID' => $adminID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getDegreePublishedProgramme()
    {
        try {
            $query = $this->conn->prepare("SELECT programmeMajorID,programmeMajor from programs p,programmemajor pm where p.programID=pm.programmeID AND studyLevelID=:studyID and p.programStatus=:st order by studyLevelID ASC");
            $query->execute(array(':studyID'=>1,':st' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }

    public function getApplicantDetails($applicantID)
    {
        try {
            $query = $this->conn->prepare("SELECT
                firstName,
                lastName,
                dateOfBirth as dob,
                phoneNumber,
                email,
                gender,
                disabilityStatus,
                citizenship,
                entryQualification
            FROM
                applicants
            WHERE applicantID=:appID");
            $query->execute(array(':appID'=>$applicantID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Error" . $ex->getMessage();
        }

    }


    public function getAdmittedProgrammeMajor($appID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            programCode,programmeMajor
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        and a.applicantID = aa.applicantID
        and aa.applicantID=:appID
        and admissionStatus=:status
        ");
            $query->execute(array(':appID' => $appID, ':status' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getMainProgrammes()
    {
        try {
                $query = $this->conn->prepare("SELECT programID,programCode,programName from programs  where programStatus=:status and studyLevelID = :std order by studyLevelID ASC");
                $query->execute(array(':status' => 1, ':std' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getStudyLevelProgrammes($studyLevelID)
    {
        try {
            if($studyLevelID==1) {
                $query = $this->conn->prepare("SELECT programID,programCode,programName from programs  where programStatus=:status and studyLevelID=:std order by studyLevelID ASC");
                $query->execute(array(':status' => 1, ':std' => $studyLevelID));
            }
            else
            {
                $query = $this->conn->prepare("SELECT programID,programCode,programName from programs  where programStatus=:status and studyLevelID != :std order by studyLevelID ASC");
                $query->execute(array(':status' => 1, ':std' => 1));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getAllProgrammes()
    {
        try {
            $query = $this->conn->prepare("SELECT programID,programCode,programName from programs  where programStatus=:status order by studyLevelID ASC");
            $query->execute(array(':status' => 1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getApplicantID($formfour)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT
                applicantID
            FROM
                applicantresults
            WHERE
                indexNumber=:ffour
                AND admissionID=:adID
                AND applicationYearID=:appID");
            $query->execute(array(':ffour' => $formfour, ':adID' => 9,':appID'=>2));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getFormFour($appID)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT
                indexNumber
            FROM
                applicantresults
            WHERE
                applicantID=:applicantID
                AND admissionID=:adID");
            $query->execute(array(':applicantID' => $appID, ':adID' => 9));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $data = $row['indexNumber'];
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getDataTCUView($programmeCode,$acadID,$admID)
    {
        try {
            /*$query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),firstName,middleName,lastName,tcu_final,phoneNumber,formfour
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
       
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        AND a.applicantID=aa.applicantID
        and p.programCode=:proCode
        and a.applicantsRemarksID=:appID
        and applicationYearID=:ayID
        and admissionID=:adID
        and admissionStatus=:adSt
        and choice=:chc
        ");
            $query->execute(array(':proCode'=>$programmeCode,':appID'=>3,':ayID'=>2,':adID'=>9,':adSt'=>1,':chc'=>1));*/
            if($programmeCode=='all')
            {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),firstName,middleName,lastName,tcu_final,tcu_message,phoneNumber,formfour,applicantsRemarksID
from
    applicants a,
    applicantapplication aa,
    programs p,
    studylevels st,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        and st.studyLevelID=p.studyLevelID
        and p.studyLevelID=:std
        AND a.applicantID=aa.applicantID
        and applicationYearID=:ayID
        and admissionID=:adID
        and applicantsRemarksID >= :rmk
        and applicantsRemarksID < :rmk2
        ");
                $query->execute(array('std'=>1,':ayID' => $acadID, ':adID' => $admID,':rmk'=>2,'rmk2'=>7));
            }
            else {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),firstName,middleName,lastName,tcu_final,tcu_message,phoneNumber,formfour,applicantsRemarksID
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        AND a.applicantID=aa.applicantID
        and p.programCode=:proCode
        and applicationYearID=:ayID
        and admissionID=:adID
        and applicantsRemarksID >= :rmk
        and applicantsRemarksID < :rmk2
        ");
                $query->execute(array(':proCode' => $programmeCode, ':ayID' => $acadID, ':adID' => $admID,':rmk'=>2,'rmk2'=>7));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getDataFormFour($programmeCode)
    {
        try {
            $query = $this->conn->prepare("SELECT
            indexNumber,programCode,programName,a.applicantID
from
    applicants a,
    applicantapplication aa,
    applicantresults ar,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        and p.programCode=:proCode
        and a.applicantID=ar.applicantID
        and a.applicantID=aa.applicantID
        and a.applicantsRemarksID=:appID
        and a.applicationYearID=:ayID
        and a.admissionID=:adID
        and admissionStatus=:adSt
        and choice=:chc
        ");
            $query->execute(array(':proCode'=>$programmeCode,':appID'=>3,':ayID'=>2,':adID'=>9,':adSt'=>1,':chc'=>1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

/*AND (tcu_final<>:qual AND tcu_final<>:mpt AND tcu_final<>:conf)*/
/*$query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID,':qual'=>'Qualified',':mpt'=>'Multiple Admission',':conf'=>'%%'));*/


    //NACTE VIEW DATA
    public function getDataNACTEView($programmeID,$acadID,$admissionID)
    {
        try {
            if($programmeID=='all')
            {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),firstName,middleName,lastName,nacte_status,phoneNumber,applicantsRemarksID,nacte_status
from
    applicants a,
    applicantapplication aa,
    programs p,
    studylevels st,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        AND st.studyLevelID=p.studyLevelID
        AND p.studyLevelID != :std
        AND a.applicantID=aa.applicantID
        and applicationYearID=:ayID
        and admissionID=:adID
        and applicantsRemarksID >= :rmk
        and applicantsRemarksID < :rmk2
        ");
                $query->execute(array(':std'=>1,':ayID' => $acadID, ':adID' => $admissionID,':rmk'=>2,'rmk2'=>7));
            }
            else {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),firstName,middleName,lastName,phoneNumber,applicantsRemarksID,nacte_status
from
    applicants a,
    applicantapplication aa,
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=aa.programmeMajorID
        AND a.applicantID=aa.applicantID
        and p.programID=:proCode
        and applicationYearID=:ayID
        and admissionID=:adID
        and applicantsRemarksID >= :rmk
        and applicantsRemarksID < :rmk2
        ");
                $query->execute(array(':proCode' => $programmeID, ':ayID' => $acadID, ':adID' => $admissionID,':rmk'=>2,':rmk2'=>7 ));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getSelectedResubmit($acadID, $admID,$round)
    {
        
        try {
            if ($round == 'all') {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,tcu_final,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID
            AND (NULLIF(tcu_final, '') IS NULL OR tcu_final NOT LIKE :qual AND tcu_final NOT LIKE :mpt AND tcu_final NOT LIKE :conf AND tcu_final  NOT LIKE :addm )
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID, ':qual' => '%Qualified%', ':mpt' => '%Multiple Admission%', ':conf' => '%confirmed%', ':addm' => '%Admitt%'));
            } else {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID), firstName, middleName, lastName, gender,dateOfBirth as dob,citizenship,tcu_final,disabilityStatus,entryQualification,phoneNumber,email,districtID,tcu_status
        from
            applicants a,
            applicantapplication aa,
            programs p,
            programmemajor pm
        where
            a.applicantID=aa.applicantID
            AND aa.programmeMajorID = pm.programmeMajorID
            AND aa.admissionStatus = :adminStatus
            AND p.programID = pm.programmeID
            AND p.studyLevelID=:studyID
            and applicantsRemarksID=:remarkID
            and applicationYearID=:appYearID
            and admissionID=:adminID and roundName=:round
            AND (NULLIF(tcu_final, '') IS NULL OR tcu_final NOT LIKE :qual AND tcu_final NOT LIKE :mpt AND tcu_final NOT LIKE :conf AND tcu_final  NOT LIKE :addm )
            order by tcu_status ASC");
                $query->execute(array(':adminStatus' => 1, ':studyID' => 1, 'remarkID' => 3, ':appYearID' => $acadID, ':adminID' => $admID,':qual'=>'%Qualified%',':mpt'=>'%Multiple Admission%',':conf'=>'%confirmed%',':addm'=>'%Admitt%',':round'=>$round));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function searchApplicant($search_text)
    {
        try {
                $query = $this->conn->prepare("SELECT
            DISTINCT(a.applicantID),applicationNumber,a.applicationYearID,refNumber,indexNumber,firstName, middleName, lastName, gender,applicantsRemarksID,tcu_final,tcu_message,nacte_status,phoneNumber,email
            FROM applicants a,applicantresults ar,academicyears ay
            WHERE a.applicantID=ar.applicantID
            AND ay.academicYearID=a.applicationYearID
            AND (applicationNumber LIKE :search OR refNumber LIKE :search OR firstName LIKE :search OR lastName LIKE :search OR indexNumber LIKE :search)
            AND examinationLevel=:elevl
            AND ay.academicYearStatus=:st");
                $query->execute(array(':search' => '%' . $search_text . '%', ':elevl' => 'Ordinary',':st'=>1));
            

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getApplicantByAgent($acadID, $admID,$remarksID)
    {
        try {
            if($remarksID==1) {
                    $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,phoneNumber,agentID,entryQualification
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND p.programID = pm.programmeID
                and (applicantsRemarksID >=:remarkID OR applicantsRemarksID<=:remark)
                and applicationYearID=:appYearID
                and admissionID=:adminID
                AND agentID <>:agent");
                $query->execute(array('remarkID' => 1,'remark' => 6, ':appYearID' => $acadID, ':adminID' => $admID,':agent'=>0));
            }
            else
            {
                $query = $this->conn->prepare("SELECT
                DISTINCT(a.applicantID), firstName, middleName, lastName, gender,phoneNumber,agentID,entryQualification
            from
                applicants a,
                applicantapplication aa,
                programs p,
                programmemajor pm
            where
                a.applicantID=aa.applicantID
                AND aa.programmeMajorID = pm.programmeMajorID
                AND p.programID = pm.programmeID
                and applicantsRemarksID=:remarkID
                and applicationYearID=:appYearID
                and admissionID=:adminID
                AND agentID <>:agent");
                $query->execute(array('remarkID' => $remarksID, ':appYearID' => $acadID, ':adminID' => $admID,':agent'=>0));

            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getProgrammeCode($programmemajorID)
    {
        try {
            $query = $this->conn->prepare("SELECT
   p.programID,pm.programmeMajorID,pm.programmeMajor,programCode,programName
from
    programs p,
    programmemajor pm
where
        p.programID=pm.programmeID
        and pm.programmeMajorID=:progMID
        ");
            $query->execute(array(':progMID' => $programmemajorID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getTransferredListProgrammeCode($transferType)
    {
        try {
            $query = $this->conn->prepare("SELECT
   applicantID,formFour,formSix,bProgrammeCode,aProgrammeCode,transferType,tcu_status
from applicant_transfer
where
            transferType=:transfer");
            $query->execute(array(':transfer' => $transferType));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getApplicationFees($studyLevelID)
    {
        try {
            //$data = array();
            $query = $this->conn->prepare("SELECT 
            fees
        from
            applicationfees
        where studyLevelID=:study");
            $query->execute(array(':study' => $studyLevelID));

            $row = $query->fetch(PDO::FETCH_ASSOC);
            $value = $row['fees'];
            return $value;
            /* while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }

            return $data; */
        } catch (PDOException $ex) {
            echo "Getting Data Error" . $ex->getMessage();
        }
    }

    public function getAPI($orgName,$tType)
    {
        try {
            $data=array();
            $query = $this->conn->prepare("SELECT
                userName,token,url
            from
                api_setting
            where organizationName=:org and tokenType=:ttype");
            $query->execute(array(':org' => $orgName,':ttype'=>$tType));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data; 
            } catch (PDOException $ex) {
                echo "Getting Data Error" . $ex->getMessage();
            }
    }

    public function getAdmissionSetting()
    {
        try {
            $data = array();
            $query = $this->conn->prepare("SELECT
                ay.academicYearID,academicYear,admissionID,admissionName,admissionRound,ar.startDate,ar.endDate
            from
                academicyears ay,admission_setting ast,admission_round ar
            where ast.admissionID=ar.admissionSettingID and ay.academicYearID=ast.academicYearID and roundStatus=:st");
            $query->execute(array(':st' => 1));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Getting Data Error" . $ex->getMessage();
        }
    }

    //tcu
    //getprogrammeswithadmitted


//end of DBHelper Class
}