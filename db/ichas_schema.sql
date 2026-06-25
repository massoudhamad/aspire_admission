
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `academic_background`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_background` (
  `academicID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `institutionName` varchar(245) DEFAULT NULL,
  `startYear` year DEFAULT NULL,
  `endYear` year DEFAULT NULL,
  `programmeName` varchar(245) DEFAULT NULL,
  `gpa` varchar(45) DEFAULT NULL,
  `qualificationID` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `registrationNumber` varchar(100) DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`academicID`)
) ENGINE=MyISAM AUTO_INCREMENT=667 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `academicyears`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academicyears` (
  `academicYearID` int NOT NULL AUTO_INCREMENT,
  `academicYear` varchar(50) DEFAULT NULL,
  `academicYearStatus` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`academicYearID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admission_intake`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admission_intake` (
  `admissionInTakeID` int NOT NULL AUTO_INCREMENT,
  `admissionInTake` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`admissionInTakeID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admission_letter_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admission_letter_setting` (
  `admissionID` int NOT NULL AUTO_INCREMENT,
  `admissionSettingID` int DEFAULT NULL,
  `studyLevelID` varchar(45) DEFAULT NULL,
  `orientationDate` date DEFAULT NULL,
  `registrationDate` date DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`admissionID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admission_round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admission_round` (
  `admissionRoundID` int NOT NULL AUTO_INCREMENT,
  `admissionSettingID` int DEFAULT NULL,
  `admissionRound` varchar(45) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `roundStatus` int NOT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`admissionRoundID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admission_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admission_setting` (
  `admissionID` int NOT NULL AUTO_INCREMENT,
  `academicYearID` int DEFAULT NULL,
  `admissionInTakeID` int NOT NULL,
  `admissionName` varchar(240) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `yearStatus` tinyint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`admissionID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agents` (
  `agentID` int NOT NULL AUTO_INCREMENT,
  `agentName` varchar(145) DEFAULT NULL,
  `agentAddress` varchar(145) DEFAULT NULL,
  `phoneNumber` varchar(145) DEFAULT NULL,
  `regionID` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`agentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `api_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_setting` (
  `apiSettingID` int NOT NULL AUTO_INCREMENT,
  `userName` varchar(45) DEFAULT NULL,
  `tokenType` varchar(45) DEFAULT NULL,
  `token` longtext,
  `organizationName` varchar(45) DEFAULT NULL,
  `url` longtext,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`apiSettingID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicant_identification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicant_identification` (
  `identificationID` int NOT NULL AUTO_INCREMENT,
  `applicantID` varchar(45) DEFAULT NULL,
  `zanzibarID` varchar(45) DEFAULT NULL,
  `nationalID` varchar(145) DEFAULT NULL,
  `passportNumber` varchar(45) DEFAULT NULL,
  `resident_permit` varchar(45) DEFAULT NULL,
  `residentPermit` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`identificationID`)
) ENGINE=MyISAM AUTO_INCREMENT=3681 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicant_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicant_payment` (
  `applicantPaymentID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `paymentMethod` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `token` varchar(45) DEFAULT NULL,
  `paymentStatus` tinyint(1) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantPaymentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicant_transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicant_transfer` (
  `appTransferID` int NOT NULL AUTO_INCREMENT,
  `applicantID` varchar(45) DEFAULT NULL,
  `formFour` varchar(45) DEFAULT NULL,
  `formSix` varchar(45) DEFAULT NULL,
  `bProgrammeCode` varchar(45) DEFAULT NULL,
  `aProgrammeCode` varchar(45) DEFAULT NULL,
  `transferType` varchar(45) DEFAULT NULL,
  `tcu_status` varchar(45) NOT NULL,
  `statusCode` varchar(45) NOT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`appTransferID`)
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantapplication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantapplication` (
  `applicantApplicationID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `programID` int DEFAULT NULL,
  `programmeMajorID` int DEFAULT NULL,
  `choice` tinyint DEFAULT NULL,
  `admissionStatus` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantApplicationID`),
  KEY `fk_applicant_applications_idx` (`applicantID`),
  KEY `firstChoice` (`programID`)
) ENGINE=InnoDB AUTO_INCREMENT=12094 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantregistration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantregistration` (
  `applicantRegistrationID` int NOT NULL AUTO_INCREMENT,
  `academicYearID` int NOT NULL,
  `studyLevelID` int DEFAULT NULL,
  `programmeMajorID` int DEFAULT NULL,
  `programmeID` int NOT NULL,
  `applicantID` int NOT NULL,
  `registrationNumber` varchar(100) NOT NULL,
  `regNumber` int DEFAULT NULL,
  `schoolCode` varchar(45) DEFAULT NULL,
  `tcu_status` tinyint(1) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantRegistrationID`),
  KEY `fk_application_idx` (`applicantID`),
  KEY `fk_academic_idx` (`academicYearID`)
) ENGINE=InnoDB AUTO_INCREMENT=2156 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantremarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantremarks` (
  `applicantRemarkID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int NOT NULL,
  `remarkID` int NOT NULL,
  `processDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `programID` int DEFAULT '0',
  `receiptNumber` int DEFAULT NULL,
  `comments` text,
  `userID` int NOT NULL,
  `activeStatus` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantRemarkID`)
) ENGINE=InnoDB AUTO_INCREMENT=21323 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantresults` (
  `applicantResultID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `applicationYearID` int DEFAULT NULL,
  `admissionID` int DEFAULT NULL,
  `schoolName` varchar(150) DEFAULT NULL,
  `yearTaken` varchar(45) DEFAULT NULL,
  `indexNumber` varchar(50) DEFAULT NULL,
  `avn_number` varchar(150) DEFAULT NULL,
  `examinationAuthority` varchar(150) DEFAULT NULL,
  `examinationLevel` varchar(50) DEFAULT NULL,
  `award` varchar(45) DEFAULT NULL,
  `gradeType` varchar(45) DEFAULT NULL,
  `gradePoints` varchar(45) DEFAULT NULL,
  `division` varchar(45) DEFAULT NULL,
  `points` int DEFAULT NULL,
  `entryqualification` int DEFAULT NULL,
  `applicantResultStatus` int DEFAULT NULL,
  `levelStatus` int DEFAULT NULL,
  `resultStatus` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantResultID`),
  KEY `fk_applicantID_idx` (`applicantID`)
) ENGINE=InnoDB AUTO_INCREMENT=12284 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicants` (
  `applicantID` int NOT NULL AUTO_INCREMENT,
  `applicationNumber` varchar(145) DEFAULT NULL,
  `applicationYearID` int DEFAULT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `middleName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `otherNames` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `placeOfBirth` varchar(150) DEFAULT NULL,
  `maritalStatus` varchar(50) DEFAULT NULL,
  `citizenship` varchar(150) DEFAULT NULL,
  `residencyStatus` varchar(50) DEFAULT NULL,
  `postalAddress` varchar(150) DEFAULT NULL,
  `physicalAddress` varchar(150) DEFAULT NULL,
  `phoneNumber` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nextOfKinName` varchar(100) DEFAULT NULL,
  `nextOfKinPhoneNumber` varchar(50) DEFAULT NULL,
  `nextOfKinAddress` varchar(150) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `disabilityStatus` varchar(50) DEFAULT NULL,
  `employmentStatus` varchar(45) DEFAULT NULL,
  `sponsor` varchar(45) DEFAULT NULL,
  `districtID` int DEFAULT NULL,
  `agentID` int DEFAULT NULL,
  `refNumber` varchar(100) DEFAULT NULL,
  `entryQualification` int DEFAULT '0',
  `applicantsRemarksID` int DEFAULT '0',
  `publish_status` tinyint(1) DEFAULT '0',
  `userID` int DEFAULT NULL,
  `admissionID` int NOT NULL,
  `admissionLevel` char(2) DEFAULT NULL,
  `eauthority` varchar(45) DEFAULT NULL,
  `appinfostatus` tinyint(1) DEFAULT '0',
  `tcu_status` tinyint(1) DEFAULT NULL,
  `tcu_final` varchar(145) DEFAULT NULL,
  `tcu_message` varchar(225) DEFAULT NULL,
  `formfour` varchar(100) DEFAULT NULL,
  `tuc_confirm` int DEFAULT NULL,
  `nacte_status` int DEFAULT NULL,
  `religion` varchar(120) DEFAULT NULL,
  `hosteller` varchar(120) DEFAULT NULL,
  `transferStatus` tinyint(1) DEFAULT NULL,
  `admissionRound` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantID`),
  KEY `userID_idx` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=6895 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicants_nacte_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicants_nacte_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admissionID` int DEFAULT NULL,
  `student_verification_id` varchar(45) DEFAULT NULL,
  `programme_id` varchar(145) DEFAULT NULL,
  `firstname` varchar(245) DEFAULT NULL,
  `secondname` varchar(245) DEFAULT NULL,
  `surname` varchar(245) DEFAULT NULL,
  `mobile_number` varchar(45) DEFAULT NULL,
  `email_address` varchar(245) DEFAULT NULL,
  `form_four_indexnumber` varchar(145) DEFAULT NULL,
  `form_four_year` varchar(145) DEFAULT NULL,
  `form_six_indexnumber` varchar(145) DEFAULT NULL,
  `form_six_year` varchar(145) DEFAULT NULL,
  `NTA4_reg` varchar(145) DEFAULT NULL,
  `NTA4_grad_year` varchar(145) DEFAULT NULL,
  `NTA5_reg` varchar(145) DEFAULT NULL,
  `NTA5_grad_year` varchar(145) DEFAULT NULL,
  `nacte_status` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicants_non_degree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicants_non_degree` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(245) DEFAULT NULL,
  `formfour` varchar(45) DEFAULT NULL,
  `formsix` varchar(45) DEFAULT NULL,
  `certreg` varchar(45) DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `nationality` varchar(45) DEFAULT NULL,
  `impairment` varchar(45) DEFAULT NULL,
  `dob` varchar(45) DEFAULT NULL,
  `progcode` varchar(45) DEFAULT NULL,
  `progname` varchar(245) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  `tcu_status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantstudylevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantstudylevel` (
  `applicantStudyLevelID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `studyLevelID` int DEFAULT NULL,
  `qualificationTypeID` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantStudyLevelID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantsubjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantsubjects` (
  `applicantSubjectID` int NOT NULL AUTO_INCREMENT,
  `applicantResultID` int DEFAULT NULL,
  `subjectID` varchar(45) DEFAULT NULL,
  `gradeID` int DEFAULT NULL,
  `points` float DEFAULT NULL,
  `subjectName` varchar(145) DEFAULT NULL,
  `subjectCode` varchar(45) DEFAULT NULL,
  `grade` varchar(45) DEFAULT NULL,
  `status` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicantSubjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=75153 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicantsubjects_equivalence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicantsubjects_equivalence` (
  `equivalenceResultID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `applicantResultID` int DEFAULT NULL,
  `subjectName` varchar(145) DEFAULT NULL,
  `gradeCode` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`equivalenceResultID`)
) ENGINE=MyISAM AUTO_INCREMENT=706 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applicationfees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicationfees` (
  `applicationFeesID` int NOT NULL AUTO_INCREMENT,
  `studyLevelID` int DEFAULT NULL,
  `academicYearID` int DEFAULT NULL,
  `fees` decimal(10,2) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`applicationFeesID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachment` (
  `attachmentID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `applicationNumber` int DEFAULT NULL,
  `documentType` longtext,
  `fileUrl` longtext,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`attachmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=1533 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campus` (
  `campusID` int NOT NULL AUTO_INCREMENT,
  `campusName` varchar(50) DEFAULT NULL,
  `campusAddress` varchar(50) DEFAULT NULL,
  `accountNumber` varchar(45) DEFAULT NULL,
  `accountName` varchar(145) DEFAULT NULL,
  `bankName` varchar(45) DEFAULT NULL,
  `swiftCode` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`campusID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `departmentID` int NOT NULL AUTO_INCREMENT,
  `departmentName` varchar(100) DEFAULT NULL,
  `departmentCode` varchar(50) DEFAULT NULL,
  `schoolID` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`departmentID`),
  KEY `schoolID_idx` (`schoolID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `disability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disability` (
  `disabilityID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `disabilityName` varchar(100) DEFAULT NULL,
  `disabilityDescription` text,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`disabilityID`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `district` (
  `districtID` int NOT NULL AUTO_INCREMENT,
  `regionID` int DEFAULT NULL,
  `districtName` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`districtID`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `documentID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `medical` varchar(45) DEFAULT NULL,
  `certificate` varchar(45) DEFAULT NULL,
  `formsixcertificate` varchar(45) DEFAULT NULL,
  `other_document` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`documentID`)
) ENGINE=InnoDB AUTO_INCREMENT=2298 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `education_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `education_levels` (
  `educationLevelsID` int NOT NULL AUTO_INCREMENT,
  `studyLevelID` int DEFAULT NULL,
  `qualificationTypeID` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`educationLevelsID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employmentstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employmentstatus` (
  `employmentStatusID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `employer` varchar(250) DEFAULT NULL,
  `employerAddress` varchar(150) DEFAULT NULL,
  `placeOfWork` varchar(150) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`employmentStatusID`),
  KEY `applicantID_idx` (`applicantID`)
) ENGINE=InnoDB AUTO_INCREMENT=719 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `equivalentresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equivalentresults` (
  `equivalentResultsID` int NOT NULL AUTO_INCREMENT,
  `applicantResultID` int DEFAULT NULL,
  `subjectName` varchar(100) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`equivalentResultsID`),
  KEY `applicantID_idx` (`applicantResultID`),
  KEY `applicantID_EquivalentResults_idx` (`applicantResultID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `examination_level_rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examination_level_rank` (
  `examinationLevelRankID` int NOT NULL AUTO_INCREMENT,
  `examinationLevel` varchar(45) DEFAULT NULL,
  `rank` int DEFAULT NULL,
  PRIMARY KEY (`examinationLevelRankID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `feestype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feestype` (
  `feesTypeID` int NOT NULL AUTO_INCREMENT,
  `feesType` varchar(50) DEFAULT NULL,
  `feesTypeDesc` varchar(245) DEFAULT NULL,
  `feesTypeStatus` tinyint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`feesTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `gradeID` int NOT NULL AUTO_INCREMENT,
  `gradeCode` varchar(20) DEFAULT NULL,
  `gradePoint` float DEFAULT NULL,
  `gradeRangeYear` int DEFAULT NULL,
  `gradeLevel` varchar(45) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`gradeID`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organization` (
  `organizationID` int NOT NULL AUTO_INCREMENT,
  `organizationName` varchar(250) DEFAULT NULL,
  `organizationCode` varchar(50) DEFAULT NULL,
  `organizationReference` varchar(145) DEFAULT NULL,
  `organizationAddress` varchar(200) DEFAULT NULL,
  `organizationPostal` varchar(145) DEFAULT NULL,
  `organizationPhone` varchar(145) DEFAULT NULL,
  `organizationEmail` varchar(150) DEFAULT NULL,
  `organizationWebsite` varchar(145) DEFAULT NULL,
  `starLink` varchar(240) NOT NULL,
  `student_support` varchar(245) DEFAULT NULL,
  `organizationPicture` text,
  `office_name` varchar(245) DEFAULT NULL,
  `contact_person` varchar(245) DEFAULT NULL,
  `title` varchar(245) DEFAULT NULL,
  `signature` text,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`organizationID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `professionalcourses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professionalcourses` (
  `professionalCourseID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `nameOfCourse` varchar(150) DEFAULT NULL,
  `institutionName` varchar(150) DEFAULT NULL,
  `yearTaken` int DEFAULT NULL,
  PRIMARY KEY (`professionalCourseID`),
  KEY `fk_professionalcourse_applicants_idx` (`applicantID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programbatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programbatch` (
  `programBatchID` int NOT NULL AUTO_INCREMENT,
  `programID` int NOT NULL,
  `batchNumber` int NOT NULL,
  `batchYear` int NOT NULL,
  PRIMARY KEY (`programBatchID`),
  KEY `fk_program_batch_idx` (`programID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programme_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_level` (
  `programmeLevelID` int NOT NULL AUTO_INCREMENT,
  `programmeLevelCode` varchar(45) NOT NULL,
  `programmeLevel` varchar(145) NOT NULL,
  `status` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`programmeLevelID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programmefees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programmefees` (
  `programFeeID` int NOT NULL AUTO_INCREMENT,
  `programID` int NOT NULL,
  `academicYearID` int NOT NULL,
  `feesTypeID` int DEFAULT NULL,
  `feesTz` varchar(20) NOT NULL,
  `feesUsa` varchar(11) NOT NULL,
  `programFeesStatus` tinyint DEFAULT NULL,
  `paidOnce` tinyint(1) NOT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`programFeeID`),
  KEY `fk_program_fee_idx` (`programID`),
  KEY `fk_academicyear_fee_idx` (`academicYearID`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programmemajor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programmemajor` (
  `programmeMajorID` int NOT NULL AUTO_INCREMENT,
  `programmeID` int DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL,
  `majorCode` varchar(45) DEFAULT NULL,
  `programmeMajor` varchar(245) DEFAULT NULL,
  `publishStatus` tinyint(1) NOT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`programmeMajorID`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programrequirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programrequirements` (
  `programRequirementID` int NOT NULL AUTO_INCREMENT,
  `programmeMajorID` int DEFAULT NULL,
  `academicYearID` int DEFAULT NULL,
  `allowedSubject` text,
  `excludedSubject` text,
  `compulsorySubject` text,
  `compulsorySubjectGrade` varchar(45) DEFAULT NULL,
  `entryQualification` text,
  `passGrade` varchar(5) DEFAULT NULL,
  `numberOfPassGrade` int DEFAULT NULL,
  `pointsRequired` double DEFAULT NULL,
  `equivalentEntryGPA` double DEFAULT NULL,
  `equivalentEntryNonGPA` varchar(20) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`programRequirementID`),
  KEY `fk_programID_idx` (`programmeMajorID`),
  KEY `fk_academicYearID_idx` (`academicYearID`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `programID` int NOT NULL AUTO_INCREMENT,
  `programCode` varchar(50) DEFAULT NULL,
  `organizationID` int DEFAULT NULL,
  `organizationCode` varchar(150) DEFAULT NULL,
  `programName` varchar(150) DEFAULT NULL,
  `programDuration` tinyint DEFAULT NULL,
  `departmentID` int DEFAULT NULL,
  `studyLevelID` int DEFAULT NULL,
  `campusID` int DEFAULT NULL,
  `programStatus` varchar(45) DEFAULT 'Active',
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`programID`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `programsfees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programsfees` (
  `programFeeID` int NOT NULL AUTO_INCREMENT,
  `programID` int NOT NULL,
  `academicYearID` int NOT NULL,
  `feestz` varchar(20) NOT NULL,
  `feesUsa` varchar(11) NOT NULL,
  PRIMARY KEY (`programFeeID`),
  KEY `fk_program_fee_idx` (`programID`),
  KEY `fk_academicyear_fee_idx` (`academicYearID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `qualification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualification` (
  `qualificationID` int NOT NULL AUTO_INCREMENT,
  `qualificationTypeID` varchar(45) DEFAULT NULL,
  `qualification` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`qualificationID`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `qualificationtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualificationtype` (
  `qualificationTypeID` int NOT NULL AUTO_INCREMENT,
  `qualificationName` varchar(50) NOT NULL,
  `qualificationTypeRank` int DEFAULT NULL,
  `rank` int DEFAULT NULL,
  `applicant_category` tinyint(1) NOT NULL,
  PRIMARY KEY (`qualificationTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `recovery_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recovery_keys` (
  `rid` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `token` varchar(50) NOT NULL,
  `valid` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `referees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referees` (
  `refereeID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `position` varchar(245) DEFAULT NULL,
  `fullName` varchar(245) DEFAULT NULL,
  `address` varchar(245) DEFAULT NULL,
  `email` varchar(245) DEFAULT NULL,
  `phoneNumber` varchar(145) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`refereeID`)
) ENGINE=MyISAM AUTO_INCREMENT=512 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `region` (
  `regionID` int NOT NULL AUTO_INCREMENT,
  `regionName` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`regionID`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `remarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remarks` (
  `remarkID` int NOT NULL AUTO_INCREMENT,
  `remark` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`remarkID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `roleID` int NOT NULL AUTO_INCREMENT,
  `roleName` varchar(50) DEFAULT NULL,
  `roleDescription` text,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `round` (
  `roundID` int NOT NULL AUTO_INCREMENT,
  `roundName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`roundID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schools` (
  `schoolID` int NOT NULL AUTO_INCREMENT,
  `schoolName` varchar(100) DEFAULT NULL,
  `schoolCode` varchar(50) DEFAULT NULL,
  `campusID` int DEFAULT NULL,
  `regCode` varchar(45) DEFAULT NULL,
  `status` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`schoolID`),
  UNIQUE KEY `schoolCode_UNIQUE` (`schoolCode`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sector` (
  `sectorID` int NOT NULL AUTO_INCREMENT,
  `sectorName` varchar(205) DEFAULT NULL,
  PRIMARY KEY (`sectorID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sponsor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsor` (
  `sponsorID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `sponsorName` varchar(100) DEFAULT NULL,
  `sponsorAddress` varchar(100) DEFAULT NULL,
  `sponsorPhoneNumber` varchar(100) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`sponsorID`)
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `studylevelfees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `studylevelfees` (
  `studyLevelFeeID` int NOT NULL AUTO_INCREMENT,
  `studyLevelID` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `amountout` decimal(10,2) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`studyLevelFeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `studylevels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `studylevels` (
  `studyLevelID` int NOT NULL AUTO_INCREMENT,
  `studyLevelName` varchar(150) DEFAULT NULL,
  `studyLevelCode` varchar(40) DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`studyLevelID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subjectrequirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjectrequirements` (
  `subjectRequirementID` int NOT NULL AUTO_INCREMENT,
  `programmeRequirementID` int DEFAULT NULL,
  `programmeMajorID` int DEFAULT NULL,
  `subjectID` int DEFAULT NULL,
  `subjectType` varchar(150) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`subjectRequirementID`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `subjectID` int NOT NULL AUTO_INCREMENT,
  `SCode` varchar(45) DEFAULT NULL,
  `subjectName` varchar(50) DEFAULT NULL,
  `subjectCode` varchar(45) DEFAULT NULL,
  `stream` varchar(45) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`subjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `upload` (
  `uploadID` int NOT NULL AUTO_INCREMENT COMMENT '		',
  `schoolID` int DEFAULT NULL,
  `academicYearID` int DEFAULT NULL,
  `title` longtext,
  `url` longtext,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`uploadID`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `uploaded_enrolled`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploaded_enrolled` (
  `fname` text,
  `mname` text,
  `surname` text,
  `gender` text,
  `nationality` text,
  `date_of_birth` text,
  `award_category` text,
  `field_specialization` text,
  `year_of_study` text,
  `study_mode` text,
  `is_year_repeat` text,
  `entry_qualification` text,
  `sponsorship` text,
  `physical_challenges` text,
  `enrolement_year` int DEFAULT NULL,
  `f4_index_number` text,
  `f6_index_number` text,
  `award_name` text,
  `registration_number` int DEFAULT NULL,
  `institution_code` text,
  `programme_code` text,
  `tcu_status` tinyint(1) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `userroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userroles` (
  `userRoleID` int NOT NULL AUTO_INCREMENT,
  `userID` int DEFAULT NULL,
  `roleID` int DEFAULT NULL,
  `sectionID` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`userRoleID`),
  KEY `userID_idx` (`userID`),
  KEY `roleID_idx` (`roleID`)
) ENGINE=InnoDB AUTO_INCREMENT=6907 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `firstName` varchar(150) NOT NULL,
  `middleName` varchar(150) NOT NULL,
  `lastName` varchar(150) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `phoneNumber` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `sectionID` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `login` int DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=6907 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `working_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `working_experience` (
  `workingExperienceID` int NOT NULL AUTO_INCREMENT,
  `applicantID` int DEFAULT NULL,
  `positionName` varchar(245) DEFAULT NULL,
  `employerName` varchar(245) DEFAULT NULL,
  `employerAddress` varchar(245) DEFAULT NULL,
  `startYear` year DEFAULT NULL,
  `endYear` year DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int DEFAULT NULL,
  PRIMARY KEY (`workingExperienceID`)
) ENGINE=MyISAM AUTO_INCREMENT=351 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

