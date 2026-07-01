<?php
// app/_progress.php — Horizontal progress indicator for the applicant flow.
//
// Renders the .ichas-progress breadcrumb at the top of every applicant page.
// Reads the applicant's saved state from the DB to mark steps as done /
// current / upcoming. Each completed step is a back-link; future steps are
// non-clickable (linear-with-back navigation).
//
// Style block lives in assets/css/ichas-brand.css under .ichas-progress.
//
// Required at include time:
//   $db                        DBHelper instance (created by app/index.php)
//   $_SESSION['applicantID']   the applicant we're rendering for
//   $_GET['sz']                the route key from app/main_index.php

if (!isset($db) || empty($_SESSION['applicantID'])) {
    return;
}

$applicantID = (int) $_SESSION['applicantID'];
$currentSz   = $_GET['sz'] ?? 'home';

/* ICHAS flow — 7 steps only. No Working Experience, no Referees, no Documents.
   `keys` groups multiple sub-routes into one visible step. */
$STEPS = [
    ['key' => 'home',      'label' => 'Welcome',     'keys' => ['home','']],
    ['key' => 'level',     'label' => 'Study Level', 'keys' => ['level']],
    ['key' => 'education', 'label' => 'Education',   'keys' => ['education_background','olevel','equivalent','newsubject','alevel','confirm_ordinary_results','other_ordinary','other_ordinary_results','ordinary_results','pg_education']],
    ['key' => 'programme', 'label' => 'Programmes',  'keys' => ['programmechoice','programme_choice_verification']],
    ['key' => 'personal',  'label' => 'Personal',    'keys' => ['personalinfo','changepwd']],
    ['key' => 'payments',  'label' => 'Payments',    'keys' => ['payments','payment']],
    ['key' => 'submit',    'label' => 'Submit',      'keys' => ['submit','confirm_registration','summary','applicationindex','appdetails','check_status','success']],
];

/* Determine what has been saved. Each probe is wrapped in try/catch so a
   missing table never breaks the page. */
$done = ['home' => true];

try {
    $r = $db->getRows('applicantstudylevel', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['level'] = true; }
} catch (Throwable $e) {}

try {
    /* Education is done as soon as any applicantresults row exists — older
       accounts pre-date the applicantResultStatus column being maintained. */
    $r = $db->getRows('applicantresults', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['education'] = true; }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('applicantapplication', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['programme'] = true; }
} catch (Throwable $e) {}

try {
    $rows = $db->getRows('applicants', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($rows[0]['dateOfBirth']) && $rows[0]['dateOfBirth'] !== '0000-00-00') {
        $done['personal'] = true;
    }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('applicant_payment', ['where' => ['applicantID' => $applicantID]]);
    if (!empty($r)) { $done['payments'] = true; }
} catch (Throwable $e) {}

try {
    $r = $db->getRows('applicantremarks', ['where' => ['applicantID' => $applicantID, 'remarkID' => 1]]);
    if (!empty($r)) { $done['submit'] = true; }
} catch (Throwable $e) {}

/* Current step */
$currentKey = 'home';
foreach ($STEPS as $s) {
    if (in_array($currentSz, $s['keys'], true)) { $currentKey = $s['key']; break; }
}

?>
<nav class="ichas-progress" aria-label="Application progress">
<?php foreach ($STEPS as $i => $s):
    $k        = $s['key'];
    $isDone   = !empty($done[$k]);
    $isCur    = ($k === $currentKey);
    $class    = $isCur ? 'current' : ($isDone ? 'done' : '');
    $stepNum  = $i + 1;
    $route    = $s['keys'][0] !== '' ? "index.php?sz={$s['keys'][0]}" : 'index.php';
?>
    <div class="ichas-progress-step <?php echo $class; ?>" title="Step <?php echo $stepNum; ?>: <?php echo htmlspecialchars($s['label']); ?>">
<?php if ($isDone && !$isCur): ?>
        <a href="<?php echo $route; ?>"><?php echo $stepNum . '. ' . htmlspecialchars($s['label']); ?></a>
<?php else: ?>
        <?php echo $stepNum . '. ' . htmlspecialchars($s['label']); ?>
<?php endif; ?>
    </div>
<?php endforeach; ?>
</nav>
