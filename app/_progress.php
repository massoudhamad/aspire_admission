<?php
// app/_progress.php — Horizontal progress indicator for the applicant flow.
//
// Renders the .lsz-progress breadcrumb at the top of every applicant page.
// Reads the applicant's saved state from the DB to mark steps as done /
// current / upcoming. Each completed step is a back-link; future steps are
// non-clickable (linear-with-back navigation).
//
// Style block lives in assets/css/lsz-brand.css under .lsz-progress.
//
// Required globals at include time:
//   $db                  DBHelper instance (already created by app/index.php)
//   $_SESSION['applicantID']  the applicant we're rendering for
//   $_GET['sz']          the route key from app/main_index.php (see step
//                        keys below)
//
// Including this file is harmless: if any prerequisite is missing it returns
// silently and nothing is rendered.

if (!isset($db) || empty($_SESSION['applicantID'])) {
    return;
}

$applicantID = (int) $_SESSION['applicantID'];
$currentSz   = $_GET['sz'] ?? 'home';

/* The 10 steps. `keys` is the list of routes that map to this step (some
   steps have multiple internal sub-routes — e.g. education_background also
   covers olevel / equivalent / confirm_ordinary_results / alevel). */
$STEPS = [
    ['key' => 'home',      'label' => 'Welcome',     'keys' => ['home','']],
    ['key' => 'level',     'label' => 'Study Level', 'keys' => ['level']],
    ['key' => 'education', 'label' => 'Education',   'keys' => ['education_background','olevel','equivalent','newsubject','alevel','confirm_ordinary_results','other_ordinary','other_ordinary_results','ordinary_results','pg_education']],
    ['key' => 'programme', 'label' => 'Programmes',  'keys' => ['programmechoice','programme_choice_verification']],
    ['key' => 'personal',  'label' => 'Personal',    'keys' => ['personalinfo','changepwd']],
    ['key' => 'working',   'label' => 'Working',     'keys' => ['working']],
    ['key' => 'referees',  'label' => 'Referees',    'keys' => ['referees']],
    ['key' => 'documents', 'label' => 'Documents',   'keys' => ['attachment','document_upload','upload_document']],
    ['key' => 'payments',  'label' => 'Payments',    'keys' => ['payments','payment']],
    ['key' => 'submit',    'label' => 'Submit',      'keys' => ['submit','confirm_registration','summary','applicationindex','appdetails','check_status','success']],
];

/* Determine which steps have data saved against the applicant. Each probe is
   wrapped in try/catch so a missing table never breaks the page render. */
$done = ['home' => true];

try {
    if (method_exists($db, 'getRows')) {
        $r = $db->getRows('applicantstudylevel', ['where' => ['applicantID' => $applicantID]]);
        if (!empty($r)) { $done['level'] = true; }
    }
} catch (Throwable $e) {}

try {
    /* "Education" counts as done if the applicant has any applicantresults
       row — older accounts pre-date the applicantResultStatus column so
       gating on status=1 only would hide their progress. The status field
       is still respected via a more nuanced check elsewhere (e.g. the
       admit-applicants UI). */
    $r = $db->getRows('applicantresults', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['education'] = true; }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('applicantapplication', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['programme'] = true; }
} catch (Throwable $e) {}

try {
    /* Personal info is "done" when the applicants row has identity fields filled.
       We treat dateOfBirth as the simplest sentinel — it's required by every step
       further on. */
    $rows = $db->getRows('applicants', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($rows[0]['dateOfBirth']) && $rows[0]['dateOfBirth'] !== '0000-00-00') {
        $done['personal'] = true;
    }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('working_experience', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['working'] = true; }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('referees', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['referees'] = true; }
} catch (Throwable $e) {}

try {
    /* Documents — the table name varies across installs. Try the most common
       one first, then fall back. */
    $r = $db->getRows('applicant_documents', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['documents'] = true; }
} catch (Throwable $e) {
    try {
        $r = $db->getRows('upload', ['where' => ['applicantID' => $applicantID]]);
        if (!empty($r)) { $done['documents'] = true; }
    } catch (Throwable $e2) {}
}

try {
    $r = $db->getRows('applicant_payment', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['payments'] = true; }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('applicantremarks', ['where' => ['applicantID' => $applicantID, 'remarkID' => 1]]);
    if (!empty($r)) { $done['submit'] = true; }
} catch (Throwable $e) {}

/* Which step is the user currently on? */
$currentKey = 'home';
foreach ($STEPS as $s) {
    if (in_array($currentSz, $s['keys'], true)) { $currentKey = $s['key']; break; }
}

/* Render. Linear-with-back: any step in $done is a clickable back-link; the
   current step is highlighted; future steps are non-clickable. */
?>
<nav class="lsz-progress" aria-label="Application progress">
<?php foreach ($STEPS as $i => $s):
    $k        = $s['key'];
    $isDone   = !empty($done[$k]);
    $isCur    = ($k === $currentKey);
    $class    = $isCur ? 'current' : ($isDone ? 'done' : '');
    $stepNum  = $i + 1;
    /* Pick the canonical route for the back-link from the first entry in keys */
    $route    = $s['keys'][0] !== '' ? "index.php?sz={$s['keys'][0]}" : 'index.php';
?>
    <div class="lsz-progress-step <?php echo $class; ?>" title="Step <?php echo $stepNum; ?>: <?php echo htmlspecialchars($s['label']); ?>">
<?php if ($isDone && !$isCur): ?>
        <a href="<?php echo $route; ?>"><?php echo $stepNum . '. ' . htmlspecialchars($s['label']); ?></a>
<?php else: ?>
        <?php echo $stepNum . '. ' . htmlspecialchars($s['label']); ?>
<?php endif; ?>
    </div>
<?php endforeach; ?>
</nav>
