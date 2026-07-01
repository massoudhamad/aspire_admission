<?php
/*
 * app/_lsz_guide.php — Shared LSZ-mode helper card for applicant pages.
 *
 * Include from any applicant-facing sub-page after the <h1> title:
 *
 *     <?php $LSZ_STEP = 'programmes'; include __DIR__ . '/_lsz_guide.php'; ?>
 *
 * Renders a small alert box that:
 *   - Explains what this step does in LSZ terms (professional courses)
 *   - Tells the applicant what to do next based on saved state
 *
 * Silent no-op when the tenant isn't LSZ or when required session context
 * is missing. Never breaks the page — every DB probe is in try/catch.
 *
 * Required at include time:
 *   $db        DBHelper instance (created by app/index.php)
 *   $LSZ_STEP  string key: 'programmes', 'personal', 'working',
 *              'referees', 'documents', 'payments', 'submit'
 */

if (!isset($db) || empty($_SESSION['applicantID']) || empty($LSZ_STEP)) {
    return;
}

$_lsz_applicantID = (int)$_SESSION['applicantID'];

/* Tenant check */
$_LSZ_MODE = false;
try {
    $_org = $db->getRows("organization");
    if (!empty($_org[0]['organizationCode']) && strtoupper($_org[0]['organizationCode']) === 'LSZ') {
        $_LSZ_MODE = true;
    }
} catch (Throwable $e) {}
if (!$_LSZ_MODE) return;

/* Study level (so we can adapt the copy to CLP vs Vakil) */
$_LSZ_STUDY_LEVEL_ID = null;
try {
    $_lvl = $db->getRows('applicantstudylevel', array('where' => array('applicantID' => $_lsz_applicantID)));
    if (!empty($_lvl[0])) $_LSZ_STUDY_LEVEL_ID = (int)$_lvl[0]['studyLevelID'];
} catch (Throwable $e) {}
$_LSZ_PROGRAMME_NAME = 'your programme';
if ($_LSZ_STUDY_LEVEL_ID === 4)     $_LSZ_PROGRAMME_NAME = 'Certified Legal Professional (CLP)';
elseif ($_LSZ_STUDY_LEVEL_ID === 5) $_LSZ_PROGRAMME_NAME = 'Vakil Course';

/* Row-count helper — safe on missing tables */
$_lszCount = static function ($table, $extraWhere = array()) use ($db, $_lsz_applicantID) {
    try {
        $where = array_merge(array('applicantID' => $_lsz_applicantID), $extraWhere);
        $r = $db->getRows($table, array('where' => $where));
        return is_array($r) ? count($r) : 0;
    } catch (Throwable $e) { return 0; }
};

$_n_programmes = $_lszCount('applicantapplication');
$_n_working    = $_lszCount('working_experience');
$_n_referees   = $_lszCount('referees');
$_n_payments   = $_lszCount('applicant_payment');
$_n_submitted  = $_lszCount('applicantremarks', array('remarkID' => 1));

/* documents — table name varies across installs */
$_n_docs = 0;
foreach (array('applicant_documents', 'upload') as $_t) {
    try {
        $r = $db->getRows($_t, array('where' => array('applicantID' => $_lsz_applicantID)));
        if (is_array($r) && count($r) > 0) { $_n_docs = count($r); break; }
    } catch (Throwable $e) {}
}

/* personal-info check: use dateOfBirth as the sentinel (mandatory for
   every downstream step, so if it's set they've filled the form). */
$_personal_done = false;
try {
    $rows = $db->getRows('applicants', array('where' => array('applicantID' => $_lsz_applicantID)));
    if (!empty($rows[0]['dateOfBirth']) && $rows[0]['dateOfBirth'] !== '0000-00-00') {
        $_personal_done = true;
    }
} catch (Throwable $e) {}

/* ---------------- render ---------------- */

function _lsz_alert($severity, $title, $body) {
    echo '<div class="alert alert-' . htmlspecialchars($severity) . '" style="margin-bottom:14px;border-left:4px solid ';
    switch ($severity) {
        case 'success': echo '#2C5F2D'; break;
        case 'warning': echo '#C9A227'; break;
        case 'danger':  echo '#C0392B'; break;
        default:        echo '#1D3557';
    }
    echo ';">';
    echo   '<strong style="display:block;font-size:15px;margin-bottom:4px;">' . $title . '</strong>';
    echo   '<span style="font-size:14px;">' . $body . '</span>';
    echo '</div>';
}

switch ($LSZ_STEP) {
    case 'programmes':
        if ($_n_programmes >= 1) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> Programme choice saved',
                'You are applying for the <strong>' . htmlspecialchars($_LSZ_PROGRAMME_NAME) . '</strong>. You can revisit this page anytime to change it before you submit your application.'
            );
        } else {
            _lsz_alert('info',
                '<i class="fa fa-list"></i> Choose your LSZ programme',
                'LSZ offers two professional programmes: <strong>Certified Legal Professional (CLP)</strong> for Law Degree holders and <strong>Vakil Course</strong> for Law Diploma holders. Pick the one that matches your qualification.'
            );
        }
        break;

    case 'personal':
        if ($_personal_done) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> Personal information on file',
                'Your identification details are saved. You can update any field below and click <em>Save</em>.'
            );
        } else {
            _lsz_alert('info',
                '<i class="fa fa-id-card"></i> Tell us about yourself',
                'Fill in your personal, contact, and next-of-kin details. Fields marked with * are required. Your NIDA or passport number is required at the bottom.'
            );
        }
        break;

    case 'working':
        if ($_n_working >= 1) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> ' . (int)$_n_working . ' work entry saved',
                'You can add more entries or continue to the next step. Working experience is optional but helps demonstrate your suitability.'
            );
        } else {
            _lsz_alert('info',
                '<i class="fa fa-briefcase"></i> Working experience (optional)',
                'If you have relevant work experience, add each employer here. You can skip this step if not applicable — just click <em>Save &amp; Continue</em> without adding an entry.'
            );
        }
        break;

    case 'referees':
        if ($_n_referees >= 2) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> ' . (int)$_n_referees . ' referees on file',
                'You can update your referees anytime before submitting.'
            );
        } elseif ($_n_referees == 1) {
            _lsz_alert('warning',
                '<i class="fa fa-exclamation-circle"></i> Add one more referee',
                'You have added <strong>1</strong> referee. LSZ requires <strong>at least 2 referees</strong>. Please add another.'
            );
        } else {
            _lsz_alert('info',
                '<i class="fa fa-users"></i> Add two referees',
                'Enter the details of <strong>at least two referees</strong> who can vouch for your character and academic background — typically a former lecturer and a workplace supervisor.'
            );
        }
        break;

    case 'documents':
        $expected = ($_LSZ_STUDY_LEVEL_ID === 4)
            ? 'the original Certificate of Ordinary Secondary Education (Form IV), Degree of Law, and three recent passport-size photographs'
            : (($_LSZ_STUDY_LEVEL_ID === 5)
                ? 'the original Certificate of Ordinary Secondary Education (Form IV), Diploma in Law, and three recent passport-size photographs'
                : 'all your original certificates and three recent passport-size photographs');
        if ($_n_docs >= 1) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> ' . (int)$_n_docs . ' document' . ($_n_docs > 1 ? 's' : '') . ' uploaded',
                'You will bring the original copies of ' . $expected . ' when you register at the school.'
            );
        } else {
            _lsz_alert('info',
                '<i class="fa fa-file-text"></i> Upload your documents',
                'Upload a clear scan or photo of each certificate you hold. At registration you will present ' . $expected . '.'
            );
        }
        break;

    case 'payments':
        if ($_n_payments >= 1) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> Payment recorded',
                'Your application-fee payment is on file. It will be verified by the finance office.'
            );
        } else {
            $amount = ($_LSZ_STUDY_LEVEL_ID === 4 || $_LSZ_STUDY_LEVEL_ID === 5) ? 'TShs 20,000' : 'the application fee';
            _lsz_alert('info',
                '<i class="fa fa-money"></i> Application fee: ' . htmlspecialchars($amount),
                'Pay the application fee, then enter the payment control number below. Full fee structure (tuition, exam, library, etc.) will be sent with your admission letter.'
            );
        }
        break;

    case 'submit':
        /* Pre-flight checklist for submission */
        $checks = array(
            'Study level chosen'          => (bool)$_LSZ_STUDY_LEVEL_ID,
            'Form IV results saved'       => ($_lszCount('applicantresults', array('examinationLevel' => 'Ordinary', 'applicantResultStatus' => 1)) > 0),
            'Professional qualification'  => ($_lszCount('applicantresults', array('examinationLevel' => 'Equivalent')) > 0),
            'Programme choice made'       => ($_n_programmes >= 1),
            'Personal information filled' => $_personal_done,
            'At least 2 referees added'   => ($_n_referees >= 2),
            'At least 1 document uploaded' => ($_n_docs >= 1),
        );
        $missing = array();
        foreach ($checks as $label => $ok) if (!$ok) $missing[] = $label;

        if (!empty($_n_submitted)) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> Application submitted',
                'Your application is with the LSZ admissions office. You will be notified by email once it has been reviewed.'
            );
        } elseif (empty($missing)) {
            _lsz_alert('success',
                '<i class="fa fa-check-circle"></i> Ready to submit',
                'All required information is on file. Review your details below and click <strong>Submit Application</strong> when ready. Once submitted, you cannot edit your application.'
            );
        } else {
            $items = '';
            foreach ($missing as $m) $items .= '<li>' . htmlspecialchars($m) . '</li>';
            _lsz_alert('warning',
                '<i class="fa fa-exclamation-triangle"></i> Complete these steps before submitting',
                'The following are still missing:<ul style="margin:6px 0 0 20px;">' . $items . '</ul>'
            );
        }
        break;
}
