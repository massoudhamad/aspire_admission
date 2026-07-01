-- ICHAS Ordinary Diploma admission rules (from the official requirements table).
--
-- Programme -> programmeMajorID mapping (verified against ichas_admission):
--   Ordinary Diploma in Clinical Dentistry       -> 7
--   Ordinary Diploma in Nursing and Midwifery    -> 9
--   Ordinary Diploma in Pharmaceutical Sciences  -> 5

USE ichas_admission;

-- ==================================================================
-- Ordinary Diploma in Clinical Dentistry (programmeMajorID = 7)
-- Requirement:
--   CSEE + at least 4 passes in non-religious subjects
--   INCLUDING Chemistry, Biology and Physics/Engineering Science.
--
-- Model:
--   (Chemistry >= D AND Biology >= D AND Physics >= D)
--   OR
--   (Chemistry >= D AND Biology >= D AND Engineering Science >= D)
-- ==================================================================
INSERT INTO programrequirements
  (programmeMajorID, requiresPriorLevel, ruleGroups, createdDate, modifiedDate)
VALUES
  (7, 'None',
   '[{"match":"all","rules":[{"type":"subject_grade","subject":"Chemistry","min_grade":"D"},{"type":"subject_grade","subject":"Biology","min_grade":"D"},{"type":"subject_grade","subject":"Physics","min_grade":"D"}]},{"match":"all","rules":[{"type":"subject_grade","subject":"Chemistry","min_grade":"D"},{"type":"subject_grade","subject":"Biology","min_grade":"D"},{"type":"subject_grade","subject":"Engineering Science","min_grade":"D"}]}]',
   NOW(), NOW())
ON DUPLICATE KEY UPDATE
  requiresPriorLevel = VALUES(requiresPriorLevel),
  ruleGroups         = VALUES(ruleGroups),
  modifiedDate       = NOW();

-- ==================================================================
-- Ordinary Diploma in Nursing and Midwifery (programmeMajorID = 9)
-- Requirement:
--   CSEE + minimum grade C in Biology & Chemistry,
--   Pass in Physics/Engineering Science and English.
--   (Maths is a plus, not required)
--
-- Model:
--   (Biology >= C AND Chemistry >= C AND Physics >= D AND English >= D)
--   OR
--   (Biology >= C AND Chemistry >= C AND Engineering Science >= D AND English >= D)
-- ==================================================================
INSERT INTO programrequirements
  (programmeMajorID, requiresPriorLevel, ruleGroups, createdDate, modifiedDate)
VALUES
  (9, 'None',
   '[{"match":"all","rules":[{"type":"subject_grade","subject":"Biology","min_grade":"C"},{"type":"subject_grade","subject":"Chemistry","min_grade":"C"},{"type":"subject_grade","subject":"Physics","min_grade":"D"},{"type":"subject_grade","subject":"English","min_grade":"D"}]},{"match":"all","rules":[{"type":"subject_grade","subject":"Biology","min_grade":"C"},{"type":"subject_grade","subject":"Chemistry","min_grade":"C"},{"type":"subject_grade","subject":"Engineering Science","min_grade":"D"},{"type":"subject_grade","subject":"English","min_grade":"D"}]}]',
   NOW(), NOW())
ON DUPLICATE KEY UPDATE
  requiresPriorLevel = VALUES(requiresPriorLevel),
  ruleGroups         = VALUES(ruleGroups),
  modifiedDate       = NOW();

-- ==================================================================
-- Ordinary Diploma in Pharmaceutical Sciences (programmeMajorID = 5)
-- Requirement:
--   CSEE + minimum of 5 passes in Physics, Chemistry, Biology,
--   Mathematics and English.
--
-- NECTA CSEE uses "Basic Mathematics" as the subject name.
--
-- Model:
--   subject_list: any 5 of {Physics, Chemistry, Biology, Basic Mathematics, English} >= D
-- ==================================================================
INSERT INTO programrequirements
  (programmeMajorID, requiresPriorLevel, ruleGroups, createdDate, modifiedDate)
VALUES
  (5, 'None',
   '[{"match":"all","rules":[{"type":"subject_list","subjects":["Physics","Chemistry","Biology","Basic Mathematics","English"],"min_count":5,"min_grade":"D"}]}]',
   NOW(), NOW())
ON DUPLICATE KEY UPDATE
  requiresPriorLevel = VALUES(requiresPriorLevel),
  ruleGroups         = VALUES(ruleGroups),
  modifiedDate       = NOW();

-- Sanity report
SELECT programRequirementID, programmeMajorID, requiresPriorLevel,
       LEFT(ruleGroups, 130) AS ruleGroups_preview
FROM programrequirements
WHERE programmeMajorID IN (5, 7, 9)
ORDER BY programmeMajorID;
