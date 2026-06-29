-- Map STAR-imported programs to admission study levels + seed programmemajor
-- so the applicant program-choice flow can find programs to offer.
--
-- Run AFTER ichas_seed.sql and after the STAR program import. Idempotent
-- (UPDATEs are deterministic on programCode; INSERTs skip duplicates).

USE ichas_admission;

START TRANSACTION;

-- 1. Map programs.studyLevelID from program code prefix.
--    BC → 3 (Certificate/NTA Level 4)
--    TC → 2 (Diploma/NTA Level 5)
--    OD → 8 (Diploma Three Years / NTA Level 6 closest match)
UPDATE programs SET studyLevelID = 3 WHERE programCode LIKE 'BC%' AND studyLevelID IS NULL;
UPDATE programs SET studyLevelID = 2 WHERE programCode LIKE 'TC%' AND studyLevelID IS NULL;
UPDATE programs SET studyLevelID = 8 WHERE programCode LIKE 'OD%' AND studyLevelID IS NULL;

-- 2. Normalize programStatus to '1' so it matches the integer comparisons
--    used by getProgrammeChoice / getProgrammeMajor in DB.php.
UPDATE programs SET programStatus = '1' WHERE programStatus = 'Active';

-- 3. Seed one programmemajor row per program. ICHAS programs don't have
--    sub-majors, so major name = program name. publishStatus=1 = visible.
INSERT INTO programmemajor
  (programmeID, major, majorCode, programmeMajor, publishStatus,
   createdDate, modifiedDate, createdBy)
SELECT
  programID, programName, programCode, programName, 1, NOW(), NOW(), 1
FROM programs
WHERE programID NOT IN (
  SELECT programmeID FROM programmemajor WHERE programmeID IS NOT NULL
);

COMMIT;

-- Sanity report
SELECT 'programs by study level' AS info;
SELECT studyLevelID, COUNT(*) AS programs FROM programs GROUP BY studyLevelID;

SELECT 'programmemajor seeded' AS info;
SELECT COUNT(*) AS programmemajor_rows FROM programmemajor;
