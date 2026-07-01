-- ICHAS admission rule engine — schema addition.
--
-- Adds two columns to programrequirements so each programme can express
-- its admissions rules as an AND/OR tree instead of a flat list of fields.
--
-- ruleGroups JSON shape:
--   [
--     { "match": "all",
--       "rules": [ { "type": "subject_grade", "subject": "Biology",   "min_grade": "D" },
--                  { "type": "subject_grade", "subject": "Chemistry", "min_grade": "D" } ] },
--     { "match": "all",
--       "rules": [ { "type": "subject_grade", "subject": "Biology", "min_grade": "C" },
--                  { "type": "gpa",           "min_gpa": 3.0 } ] }
--   ]
--
-- Groups are joined by OR, rules within a group are joined by AND.
--
-- Rule types supported by DB::meetsRequirements():
--   subject_grade   { subject: str, min_grade: str }
--   gpa             { min_gpa: float }
--   subject_list    { subjects: [str], min_count: int, min_grade: str }
--
-- requiresPriorLevel gates the whole rule tree behind a prior-qualification
-- check: applicants without a saved BC or TC row are rejected outright before
-- any rule matching runs. Valid values: 'None' | 'BC' | 'TC'.

USE ichas_admission;

-- Add the two columns. MySQL 8 doesn't support IF NOT EXISTS on ALTER, so
-- guard via a stored procedure that only runs the ALTER if the column is missing.
DROP PROCEDURE IF EXISTS _ichas_add_rule_engine_columns;
DELIMITER //
CREATE PROCEDURE _ichas_add_rule_engine_columns()
BEGIN
  IF NOT EXISTS (
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME   = 'programrequirements'
        AND COLUMN_NAME  = 'requiresPriorLevel'
  ) THEN
      ALTER TABLE programrequirements
        ADD COLUMN requiresPriorLevel VARCHAR(20) NULL
          COMMENT 'Prior qualification ladder gate: None | BC | TC';
  END IF;
  IF NOT EXISTS (
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME   = 'programrequirements'
        AND COLUMN_NAME  = 'ruleGroups'
  ) THEN
      ALTER TABLE programrequirements
        ADD COLUMN ruleGroups TEXT NULL
          COMMENT 'JSON: groups OR-joined, rules within a group AND-joined';
  END IF;
END//
DELIMITER ;
CALL _ichas_add_rule_engine_columns();
DROP PROCEDURE _ichas_add_rule_engine_columns;

-- Seed the rule for BC Nursing (programmeMajorID = 2, programID = 56) as a
-- proof-of-concept. Two ways in: strong sciences on Form IV, OR Biology plus
-- a Diploma-level GPA >= 3.0.
INSERT INTO programrequirements
  (programmeMajorID, academicYearID, requiresPriorLevel, ruleGroups, createdDate, modifiedDate)
VALUES
  (2, 5, 'None',
   '[{"match":"all","rules":[{"type":"subject_grade","subject":"Biology","min_grade":"D"},{"type":"subject_grade","subject":"Chemistry","min_grade":"D"}]},{"match":"all","rules":[{"type":"subject_grade","subject":"Biology","min_grade":"C"},{"type":"gpa","min_gpa":3.0}]}]',
   NOW(), NOW())
ON DUPLICATE KEY UPDATE
  requiresPriorLevel = VALUES(requiresPriorLevel),
  ruleGroups         = VALUES(ruleGroups),
  modifiedDate       = NOW();

SELECT programRequirementID, programmeMajorID, requiresPriorLevel,
       LEFT(ruleGroups, 100) AS ruleGroups_preview
FROM programrequirements
WHERE programmeMajorID = 2;
