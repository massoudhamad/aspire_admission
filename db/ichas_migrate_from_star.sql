-- Migrate campus / schools / departments / programme_level / programs
-- from ichas_star_2025 (the STAR student-records app) into ichas_admission.
--
-- studyLevelID on each program is left NULL — set via the admin UI later.
--
-- Safe to re-run: truncates target tables, source IDs preserved so FKs line up.

USE ichas_admission;

START TRANSACTION;

-- campus
TRUNCATE TABLE campus;
INSERT INTO campus (campusID, campusName, campusAddress, accountNumber, accountName, bankName, swiftCode, createdDate, modifiedDate, createdBy)
SELECT campusID, campusName, campusAddress, accountNumber, accountName, bankName, swiftCode, createdDate, modifiedDate, createdBy
FROM ichas_star_2025.campus;

-- schools (clear inherited Zanzibar Univ faculty list first)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE schools;
INSERT INTO schools (schoolID, schoolName, schoolCode, campusID, regCode, status, createdDate, modifiedDate, createdBy)
SELECT schoolID, schoolName, schoolCode, campusID, NULL, status, createdDate, modifiedDate, createdBy
FROM ichas_star_2025.schools;
SET FOREIGN_KEY_CHECKS = 1;

-- programme_level
TRUNCATE TABLE programme_level;
INSERT INTO programme_level (programmeLevelID, programmeLevelCode, programmeLevel, status, createdDate, modifiedDate, createdBy)
SELECT programmeLevelID, programmeLevelCode, programmeLevel, status, createdDate, modifiedDate, createdBy
FROM ichas_star_2025.programme_level;

-- departments
TRUNCATE TABLE departments;
INSERT INTO departments (departmentID, departmentName, departmentCode, schoolID, status, createdDate, modifiedDate, createdBy)
SELECT departmentID, departmentName, departmentCode, schoolID, status, createdDate, modifiedDate, createdBy
FROM ichas_star_2025.departments;

-- programs — studyLevelID intentionally NULL; set via UI
TRUNCATE TABLE programs;
INSERT INTO programs (programID, programCode, organizationID, organizationCode, programName,
                      programDuration, departmentID, studyLevelID, campusID, programStatus,
                      createdDate, modifiedDate, createdBy)
SELECT
  programmeID,
  programmeCode,
  1                                              AS organizationID,
  'ICHAS'                                        AS organizationCode,
  programmeName,
  programmeDuration,
  departmentID,
  NULL                                           AS studyLevelID,
  campusID,
  CASE WHEN status = 1 THEN 'Active' ELSE 'Inactive' END AS programStatus,
  createdDate,
  modifiedDate,
  createdBy
FROM ichas_star_2025.programmes;

COMMIT;

SELECT 'campus'          AS tbl, COUNT(*) AS rows_migrated FROM campus
UNION ALL SELECT 'schools',         COUNT(*) FROM schools
UNION ALL SELECT 'programme_level', COUNT(*) FROM programme_level
UNION ALL SELECT 'departments',     COUNT(*) FROM departments
UNION ALL SELECT 'programs',        COUNT(*) FROM programs;
