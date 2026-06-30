# Applicant Application Process — Endpoint Reference

End-to-end documentation of every step an applicant goes through, with the
HTTP endpoints, POST fields, DB writes, and redirect targets at each step.

This is for developers maintaining the admission system and admins who need to
understand what happens at each page transition.

**Base path conventions**
- `/index.php` etc. — public/admin pages at webroot.
- `/app/index.php?sz=…` — applicant-facing pages routed through the
  `app/main_index.php` switch (`sz` = sub-page).
- All applicant-facing endpoints require `$_SESSION['user_session']` (a logged-in
  applicant whose `users.roleID = 2`).
- Form posts use `application/x-www-form-urlencoded` unless otherwise noted.

---

## Step 0 — Register (create the account)

The public registration form on the landing page.

| | |
|---|---|
| **Form page** | `GET /index.php` (renders the "Registration for new applicants …" panel when an `admission_setting` row has `yearStatus=1`) |
| **Form action** | `POST /confirm_profile.php` (the NECTA-verification preview step) |
| **Final commit** | `POST /action_confirm_register.php?action_type=proceed` |

### POST `/confirm_profile.php` — Step 0a: NECTA lookup + preview

Pulls the applicant's NECTA particulars and renders a confirm-profile page so
the user can verify their details before committing.

| Field | Type | Required | Notes |
|---|---|---|---|
| `action_type` | `confirm` | yes | literal |
| `admission_level` | `UG \| BC \| OD \| TC \| PG` | yes | from the level dropdown |
| `exam_body` | `NECTA \| Others` | yes | |
| `indexNumber` | `S<centre>/<num>/<year>` | yes for NECTA | NECTA index, e.g. `S1291/0020/2020` |
| `equivalence_number` | string | yes for Others | when `exam_body=Others` |
| `exam_year` | int | yes for Others | |
| `phoneNumber` | string | yes | |
| `email` | string | yes | |

**Behaviour**
- Calls `POST https://api.necta.go.tz/api/results/individual` with the api_key
  from `api_setting WHERE organizationName='NECTA' AND tokenType='token'`.
- On success → renders the confirm-profile page with hidden fields
  pre-populated (`fname`, `mname`, `lname`, `gender`, `applicationNumber`,
  `admissionID`, `applicationYearID`, `admissionRound`, `indexYear`).
- On `status.code != 1` → `302 /index.php?msg=apierror`.

### POST `/action_confirm_register.php` — Step 0b: actually create the user

Triggered by the "Proceed to Application" button on the confirm-profile page.

| Field | Source | Notes |
|---|---|---|
| `action_type=proceed` | hidden | literal |
| `doProceed=Proceed to Application` | submit button | literal |
| `admission_level`, `exam_body`, `indexNumber` | hidden | carried over from step 0a |
| `fname`, `mname`, `lname`, `gender` | hidden | from NECTA |
| `phoneNumber`, `email` | hidden | from step 0a input |
| `applicationNumber`, `admissionID`, `applicationYearID`, `admissionRound`, `indexYear` | hidden | from confirm_profile |

**DB writes (in order)**

| Table | Purpose |
|---|---|
| `users` | new row, `roleID via userroles=2`, `status=1`, `login=0` (forces password change). Password hash = `PwdHash(strtoupper($lname))`. |
| `applicants` | new row, links `userID`, copies name + admission window (`admissionID`, `applicationYearID`, `admissionRound`), stores raw NECTA index in `formfour`. |
| `userroles` | one row mapping the new userID to `roleID=2` (Applicant). |
| `applicantresults` | initial O-level record with `examinationLevel='Ordinary'`, `applicantResultStatus=0` (not yet verified). |

**Redirects**
- Success → `302 /app/index.php` (logs the applicant in; session set).
- Duplicate index → `302 /index.php?msg=indexexists`.
- Duplicate email → `302 /index.php?msg=emailexists`.
- DB error → `302 /index.php?msg=error`.

**Login credentials produced**: username = the index number; initial password = the LAST NAME (uppercase).

---

## Step 1 — Choose Study Level

| | |
|---|---|
| **Form page** | `GET /app/index.php?sz=level` (renders `app/level.php`) |
| **Form action** | `POST /app/action_study_level.php` |
| **Required session** | `$_SESSION['applicantID']` |

### POST `/app/action_study_level.php`

| Field | Type | Notes |
|---|---|---|
| `action_type` | `add \| edit` | `edit` if the applicant already saved a row and is changing it |
| `qualificationTypeID` | int | from `qualificationtype` table |
| `studyLevelID` | int | from `studylevels` table |
| `doSubmit` or `doExit` | submit button | `doExit` saves and goes back to the home page |
| `applicantStudyLevelID` | hidden (edit only) | the PK of the existing row |

**DB writes**

| Table | Operation |
|---|---|
| `applicantstudylevel` | INSERT or UPDATE (one row per applicant) |

**Redirect**
- Success → `302 /app/index.php?sz=education_background`
- Exit → `302 /app/index.php?sz=home`

---

## Step 2 — Education Background (O-level + A-level + equivalence)

This step is a multi-page sub-flow. The router for the page is `?sz=education_background`,
but the actual data entry happens via two AJAX endpoints + their confirm action.

### Page `GET /app/index.php?sz=education_background`

Renders `app/education_background.php`. If the applicant has NO confirmed
results yet (`applicantresults.applicantResultStatus=0`), the page emits a
client-side `window.location` redirect to `?sz=confirm_ordinary_results`.

### Page `GET /app/index.php?sz=confirm_ordinary_results`

Renders the Form-IV index-entry form. Form action is JavaScript-driven:
`onsubmit="return ajax_ordinary_level();"` (defined in `app/js/script.js:130`).

### AJAX `POST /app/ajax_o_level.php` — fetch NECTA results for display

| Field | Type | Notes |
|---|---|---|
| `indexNumber` | string | e.g. `S1291/0020/2020` |

Returns: HTML table of subjects + division + points to inject into the page.
Internally calls NECTA's `POST /api/results/individual`.

### POST `/app/action_confirm_ordinary_results.php` — persist results

Submitted via the "Save Records" button on the rendered AJAX response.

| Field | Type | Notes |
|---|---|---|
| `action_type=add` | hidden | literal |
| `applicantID` | hidden | |
| `examinationlevel` | `Ordinary \| Advance` | |
| `examinationaward` | `formfour \| formsix` | |
| `indexNumber` | string | the typed NECTA index |
| `yearTaken` | int | exam year |
| `schoolName` | string | from NECTA |
| `division` | `I \| II \| III \| IV \| 0` | |
| `points` | int | |
| `numbersubjects` | int | count of `subjectCode[]` entries |
| `subjectCode[]` | string[] | parallel arrays — one entry per subject |
| `gradeCode[]` | string[] | A–F (or equivalent) |

**DB writes**

| Table | Operation |
|---|---|
| `applicantresults` | UPDATE: sets `applicantResultStatus=1`, fills `division`, `points`, `schoolName`, `examinationLevel`, `award`, `levelStatus=1`, `resultStatus=1`. |
| `applicantsubjects` | INSERT one row per subject (unique on `applicantResultID + subjectCode`). |

**Redirect**
- Success → `302 /app/index.php?sz=education_background&msg=succ`
- Error → `302 /app/index.php?sz=confirm_ordinary_results&msg=error` (caught
  PDO exception is now logged via `error_log` for debugging).

### Equivalence path (NACTE / Others) — `POST /app/ajax_equivalence_results.php`

Same shape as the O-level AJAX, but for applicants whose qualifications are
verified through NACTE rather than NECTA. Persists through
`/app/action_equivalent_results.php`.

### Confirm and proceed `POST /app/action_education_background.php`

| Field | Type |
|---|---|
| `doProceed=Proceed to Application` | submit button |

**Redirect** → `302 /app/index.php?sz=programmechoice`

---

## Step 3 — Choose Programmes

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=programmechoice` (renders `app/programmechoice.php`) |
| **AJAX** | `POST /app/ajax_programmechoice.php` (loads programs by study level) |
| **Form action** | `POST /app/action_programme_choice.php` |

### AJAX `POST /app/ajax_programmechoice.php`

| Field | Notes |
|---|---|
| `studyLevelID` | int |
| `applicantID` | int |

Returns `<option>` tags. Filters via `DB::getProgrammeChoice()` joining
`programs`, `programrequirements`, `programmemajor` — only returns programs
where the applicant meets the requirements and the program is published.

### POST `/app/action_programme_choice.php`

| Field | Type | Notes |
|---|---|---|
| `action_type` | `add \| edit` | |
| `programmeID` | int | first choice = a `programmemajor.programmeMajorID` |
| `progID` | int | second choice |
| `firstID` | int (edit) | existing `applicantApplicationID` for choice=1 |
| `secondID` | int (edit) | existing `applicantApplicationID` for choice=2 |

**DB writes**

| Table | Operation |
|---|---|
| `applicantapplication` | Two rows inserted (or updated): one per choice, `admissionStatus=0`. |

**Redirect**
- Success → `302 /app/index.php?sz=personalinfo`
- Logout → `302 /app/logout.php?logout=true`
- Error → `302 /app/index.php?sz=programme_choice&msg=error`

---

## Step 4 — Personal Information

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=personalinfo` (renders `app/personalinfo.php`) |
| **Form action** | `POST /app/action_personalinfo.php` |

### POST `/app/action_personalinfo.php`

Captures the full identification details. The form has ~29 fields covering
identity, address, next of kin, citizenship, disability status, etc.

| Field group | Examples |
|---|---|
| Identity | `dateOfBirth`, `placeOfBirth`, `maritalStatus`, `citizenship`, `residencyStatus` |
| Contact | `postalAddress`, `physicalAddress`, `districtID` (FK → `district`) |
| Next of kin | `nextOfKinName`, `nextOfKinPhoneNumber`, `nextOfKinAddress`, `relationship` |
| Status | `disabilityStatus` (FK → `disability`), `employmentStatus` (FK → `employmentstatus`), `sponsor` |
| Identification | `nida`, `passportNumber`, `passportExpiry` (one of these is required by citizenship) |

**DB writes**

| Table | Operation |
|---|---|
| `applicants` | UPDATE — sets all of the above plus `publish_status=1`. |
| `applicant_identification` | INSERT/UPDATE — NIDA/passport row keyed by `applicantID`. |

**Redirect** → `302 /app/index.php?sz=working` (UG / BC / OD / TC) or
`?sz=referees` (PG) depending on level.

---

## Step 5 — Working Experience  *(PG and certain UG levels)*

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=working` (renders `app/working_experience.php`) |
| **Form action** | `POST /app/action_working_experience.php` |

### POST `/app/action_working_experience.php`

| Field | Type | Notes |
|---|---|---|
| `action_type` | `add \| edit \| delete` | |
| `employer` | string | |
| `position` | string | |
| `fromDate`, `toDate` | date | `YYYY-MM-DD` |
| `responsibilities` | text | |
| `applicantWorkingID` | hidden (edit/delete) | PK of the row being changed |

Each submission appends one row; applicants can add multiple entries.

**DB writes**

| Table | Operation |
|---|---|
| `working_experience` | INSERT / UPDATE / DELETE |

**Redirect**
- `doSubmit` → reload `?sz=working` (so the applicant can add another).
- `doProceed` → `302 /app/index.php?sz=referees`.

---

## Step 6 — Referees

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=referees` (renders `app/referees.php`) |
| **Form action** | `POST /app/action_referee.php` |

### POST `/app/action_referee.php`

Two referees are typically captured per applicant.

| Field | Type | Notes |
|---|---|---|
| `action_type` | `add \| edit \| delete` | |
| `name` | string | full name |
| `position` | string | job title |
| `institution` | string | |
| `email` | string | |
| `phone` | string | |
| `address` | string | |
| `applicantRefereeID` | hidden (edit/delete) | |

Validation: a JavaScript `validateForm()` runs client-side; the server still
checks for duplicate referees (same email).

**DB writes**

| Table | Operation |
|---|---|
| `referees` | INSERT / UPDATE / DELETE |

**Redirect**
- `doSubmit` → reload `?sz=referees`.
- `doProceed` → `302 /app/index.php?sz=attachment`.

---

## Step 7 — Attachments / Document Upload

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=attachment` (renders `app/attachment.php`) |
| **Form action** | `POST /app/action_upload_document.php` (multipart form-data) |

### POST `/app/action_upload_document.php`

| Field | Type | Notes |
|---|---|---|
| `documentTypeID` | int (FK → `documenttype`) | what kind of doc this is (Form-IV cert, ID, etc.) |
| `document` | file | the actual file upload; restricted to PDF + image MIME types |

**Behaviour**
- File written to `upload_doc/<applicantID>_<documentTypeID>_<timestamp>.<ext>`.
- `applicant_documents` row inserted with the path + verification status.

**Redirect** → reload `?sz=attachment` (so applicant can upload more). When the
required document types are all present, the applicant can click "Proceed" to
move on.

---

## Step 8 — Application Fees / Payment

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=payments` (renders `app/payment.php`) |
| **Form action** | `POST /app/action_payments.php` |

### POST `/app/action_payments.php`

| Field | Type | Notes |
|---|---|---|
| `controlNumber` | string | bank-issued payment control number |
| `paymentDate` | date | |
| `amount` | decimal | the application fee from `applicationfees` table |
| `payerName` | string | optional |
| `bankName` | string | optional |

**DB writes**

| Table | Operation |
|---|---|
| `applicant_payment` | INSERT one row per payment; status starts as `0` (pending). |

**Redirect** → reload `?sz=payments` showing the payment receipt + status.

(LSZ note: payments are currently recorded manually by the admin via the same
page; the public-facing payment-gateway flow is not wired up.)

---

## Step 9 — Submit Application

The terminal step. After all of the above are complete, the applicant clicks
"Submit Application" on `?sz=submit`.

| | |
|---|---|
| **Page** | `GET /app/index.php?sz=submit` (renders `app/submit_application.php`) |
| **Form action** | `POST /app/action_submitapplication.php` |

### POST `/app/action_submitapplication.php`

| Field | Type | Notes |
|---|---|---|
| `applicantID` | hidden | |

**DB writes**

| Table | Operation |
|---|---|
| `applicantremarks` | INSERT — `remarkID=1` (Submitted), `processDate=now()`, `activeStatus=1`. |
| `applicants` | UPDATE — sets `refNumber` to `<orgReference>/<applicantID>/<yy>-<yy+1>` (e.g. `LSZ/ACAD/486/26-27`). |

After this point the applicant is read-only and shows up to admins in the
**Pending → Approve → Admit** queues:
- `/index3.php?sp=pg_applicants` (admin: applicants awaiting review)
- `/index3.php?sp=approvedlist` (admin: approved applicants)
- `/index3.php?sp=admitapplicants` (admin: admit + generate letter)

**Redirect** → `302 /app/index.php?sz=submitted_successfully`

---

## End — Applicant lifecycle states

After submission, the applicant's state is tracked by `applicantremarks.remarkID`:

| remarkID | Meaning | Set by |
|---|---|---|
| 1 | Submitted | `action_submitapplication.php` |
| 2 | Approved | admin action via `action_approve.php` |
| 3 | Rejected | admin action |
| 4 | Admitted | `action_admit.php` |
| 5 | Confirmed | applicant confirms admission |
| 6 | Withdrawn | applicant withdraws |
| 7 | Registered | applicant starts |

PDF outputs available after admission:
- `/app/printadmissionletterlsz.php?action=getPDF&applicantID=<id>` — Vakil
- `/app/printadmissionletterlszv.php?action=getPDF&applicantID=<id>` — Vakil (alt)
- `/app/printadmissionletterlszcertified.php?action=getPDF&applicantID=<id>` — PGD/Advocate

---

## Quick reference — DB tables touched in order

| Step | Tables written |
|---|---|
| 0 Register | `users`, `userroles`, `applicants`, `applicantresults` |
| 1 Study level | `applicantstudylevel` |
| 2 Education background | `applicantresults` (UPDATE), `applicantsubjects` |
| 3 Programmes | `applicantapplication` (×2 rows) |
| 4 Personal info | `applicants` (UPDATE), `applicant_identification` |
| 5 Working | `working_experience` |
| 6 Referees | `referees` |
| 7 Attachments | `applicant_documents` + filesystem `upload_doc/` |
| 8 Payments | `applicant_payment` |
| 9 Submit | `applicantremarks`, `applicants` (UPDATE — sets `refNumber`) |

---

## Common error-message codes

| `msg` query param | Meaning | Where surfaced |
|---|---|---|
| `exists` | Username (index number) already taken | Login + `confirm_profile` |
| `indexexists` | Same as above on the final commit step | `action_confirm_register` |
| `emailexists` | Email already in use | `action_confirm_register`, `confirm_profile` |
| `apierror` | NECTA returned `status.code != 1` (bad index, server down, etc.) | `confirm_profile` |
| `neta_unreachable` | NECTA endpoint did not respond at all (transport failure) | `action_register` |
| `error` | Generic PDO exception caught in the action handler | every action_*.php with a try/catch |
| `succ` / `unsucc` | Generic success/failure on persist | mostly education_background flow |
| `dropSchool`, `dropSubject` | Confirmation after deleting a school/subject row | education_background |
