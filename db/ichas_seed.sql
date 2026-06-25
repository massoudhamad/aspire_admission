-- ichas_admission seed data
-- Run AFTER ichas_schema.sql and ichas_lookup_data.sql
-- Source of truth: /Users/masoudhamad/workspace/php_dev/ichas/.env + ichas_star_2025.organization

USE ichas_admission;

-- Organization: Imperial College of Health and Allied Sciences
INSERT INTO organization
  (organizationID, organizationName, organizationCode, organizationReference,
   organizationAddress, organizationPostal, organizationPhone, organizationEmail,
   organizationWebsite, starLink, student_support, organizationPicture,
   office_name, contact_person, title, signature, createdDate, modifiedDate, createdBy)
VALUES
  (1, 'Imperial College of Health and Allied Sciences', 'ICHAS', 'ICHAS/001',
   'P.O. Box, Tanzania', NULL, '+255000000000', 'info@ichas.ac.tz',
   'https://ichas.ac.tz', '', '+255000000000', 'ichas-logo.png',
   'Office of the Principal', 'Principal', 'Principal', NULL,
   NOW(), NOW(), 1);

-- Admin user: username=admin, password=ichas2025 (CHANGE ON FIRST LOGIN)
-- Hash format matches DB.php:32 ( salt . sha1(password . salt) )
INSERT INTO users
  (userID, userName, password, firstName, middleName, lastName, gender,
   phoneNumber, email, sectionID, status, login, createdDate, modifiedDate, createdBy)
VALUES
  (1, 'admin',
   '432740e3245a8c3902b00191f4d09095e43b030515ba6e595',
   'ICHAS', '', 'Administrator', 'Male',
   '+255000000000', 'admin@ichas.ac.tz', NULL, 1, 0, NOW(), NOW(), NULL);

-- Map admin user to Administrator role (roleID=1 from copied roles table)
INSERT INTO userroles
  (userRoleID, userID, roleID, sectionID, createdDate, modifiedDate, createdBy)
VALUES
  (1, 1, 1, NULL, NOW(), NOW(), NULL);

-- API placeholder rows. Fill in real tokens via the UI (api_setting.php / tcu_api_setting.php).
INSERT INTO api_setting
  (userName, tokenType, token, organizationName, url, createdDate, modifiedDate, createdBy)
VALUES
  ('ICHAS', 'token', 'REPLACE_ME', 'NACTE', NULL, NOW(), NOW(), 1),
  ('ICHAS', 'token', 'REPLACE_ME', 'NECTA', '', NOW(), NOW(), 1),
  ('ICHAS', 'token', 'REPLACE_ME', 'TCU',   'https://api.tcu.go.tz', NOW(), NOW(), 1);
