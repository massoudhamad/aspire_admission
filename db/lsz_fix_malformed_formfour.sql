-- Strip trailing "/YYYY" (and stray "/") slugs from applicants.formfour so
-- NECTA lookups (which require a clean 3-segment index like S0187/0012/2013)
-- stop returning "Server error has occurred cannot get candidate results".
--
-- Root cause: the PG branch of action_confirm_register.php stored $username
-- (which has academicYear suffix appended) into applicants.formfour instead
-- of $indexNumber. Fixed forward in the code; this is the historical cleanup.
--
-- Idempotent: only touches rows whose formfour doesn't already match the
-- canonical pattern AND starts with a valid 3-segment prefix.

USE lsz_admission;

START TRANSACTION;

-- Snapshot the rows we're about to change, in case we need to revert.
DROP TABLE IF EXISTS _formfour_cleanup_backup;
CREATE TABLE _formfour_cleanup_backup AS
SELECT applicantID, formfour AS formfour_before, NOW() AS backed_up_at
FROM applicants
WHERE formfour IS NOT NULL AND formfour != ''
  AND formfour NOT REGEXP '^[A-Z][0-9]+/[0-9]+/[0-9]{4}$'
  AND formfour REGEXP '^[A-Z][0-9]+/[0-9]+/[0-9]{4}';

UPDATE applicants
SET formfour = SUBSTRING_INDEX(formfour, '/', 3)
WHERE formfour IS NOT NULL AND formfour != ''
  AND formfour NOT REGEXP '^[A-Z][0-9]+/[0-9]+/[0-9]{4}$'
  AND formfour REGEXP '^[A-Z][0-9]+/[0-9]+/[0-9]{4}';

COMMIT;

-- Sanity report
SELECT 'rows_cleaned' AS what, COUNT(*) FROM _formfour_cleanup_backup
UNION ALL
SELECT 'still_malformed', COUNT(*) FROM applicants
WHERE formfour IS NOT NULL AND formfour != ''
  AND formfour NOT REGEXP '^[A-Z][0-9]+/[0-9]+/[0-9]{4}$';
