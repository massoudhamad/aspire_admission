<?php
/*
 * action_rule_builder.php — Persist programrequirements.ruleGroups
 * from the rule-builder UI.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/DB.php';

$db = new DBHelper();

/* Accept only POST from a logged-in admin */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_session'])) {
    header('Location: index3.php?sp=rule_builder&msg=denied');
    exit;
}

$programmeMajorID  = isset($_POST['programmeMajorID']) ? (int)$_POST['programmeMajorID'] : 0;
$ruleGroupsJson    = isset($_POST['ruleGroups']) ? (string)$_POST['ruleGroups'] : '[]';
$requiresPrior     = isset($_POST['requiresPriorLevel']) ? trim((string)$_POST['requiresPriorLevel']) : 'None';

if ($programmeMajorID <= 0) {
    header('Location: index3.php?sp=rule_builder&msg=missingprogramme');
    exit;
}

/* Validate the JSON — must be an array of {match, rules[]} objects. */
$decoded = json_decode($ruleGroupsJson, true);
if (!is_array($decoded)) {
    header('Location: index3.php?sp=rule_builder&programmeMajorID=' . $programmeMajorID . '&msg=badjson');
    exit;
}

/* Normalise + reject unknown rule types so we never write junk. */
$allowedTypes = array('subject_grade', 'gpa', 'subject_list');
$clean = array();
foreach ($decoded as $g) {
    if (!is_array($g) || empty($g['rules']) || !is_array($g['rules'])) continue;
    $cleanRules = array();
    foreach ($g['rules'] as $r) {
        if (!is_array($r) || empty($r['type'])) continue;
        if (!in_array($r['type'], $allowedTypes, true)) continue;
        switch ($r['type']) {
            case 'subject_grade':
                if (!empty($r['subject']) && !empty($r['min_grade'])) {
                    $cleanRules[] = array(
                        'type'      => 'subject_grade',
                        'subject'   => (string)$r['subject'],
                        'min_grade' => (string)$r['min_grade'],
                    );
                }
                break;
            case 'gpa':
                if (isset($r['min_gpa'])) {
                    $cleanRules[] = array(
                        'type'    => 'gpa',
                        'min_gpa' => (float)$r['min_gpa'],
                    );
                }
                break;
            case 'subject_list':
                if (!empty($r['subjects']) && is_array($r['subjects']) && !empty($r['min_count']) && !empty($r['min_grade'])) {
                    $cleanRules[] = array(
                        'type'      => 'subject_list',
                        'subjects'  => array_values(array_filter(array_map('strval', $r['subjects']))),
                        'min_count' => (int)$r['min_count'],
                        'min_grade' => (string)$r['min_grade'],
                    );
                }
                break;
        }
    }
    if (!empty($cleanRules)) {
        $clean[] = array('match' => 'all', 'rules' => $cleanRules);
    }
}

if (!in_array($requiresPrior, array('None', 'BC', 'TC'), true)) {
    $requiresPrior = 'None';
}

$serialised = json_encode($clean);

/* Upsert into programrequirements. Row is keyed by programmeMajorID —
   updates on existing rows, inserts otherwise. */
try {
    /* DBHelper::$conn is private, so we open a fresh PDO for the raw UPDATE/INSERT. */
    require_once __DIR__ . '/dbconfig.php';
    $pdo = (new Database())->dbConnection();

    $existing = $db->getRows('programrequirements', array(
        'where' => array('programmeMajorID' => $programmeMajorID)
    ));

    if (!empty($existing)) {
        $stmt = $pdo->prepare(
            "UPDATE programrequirements
             SET requiresPriorLevel = :prior,
                 ruleGroups         = :groups,
                 modifiedDate       = NOW()
             WHERE programmeMajorID = :pmid"
        );
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO programrequirements
                 (programmeMajorID, requiresPriorLevel, ruleGroups, createdDate, modifiedDate)
             VALUES (:pmid, :prior, :groups, NOW(), NOW())"
        );
    }

    $stmt->execute(array(
        ':pmid'   => $programmeMajorID,
        ':prior'  => $requiresPrior,
        ':groups' => $serialised,
    ));

    header('Location: index3.php?sp=rule_builder&programmeMajorID=' . $programmeMajorID . '&msg=saved');
    exit;

} catch (Throwable $e) {
    error_log('[action_rule_builder] ' . $e->getMessage());
    header('Location: index3.php?sp=rule_builder&programmeMajorID=' . $programmeMajorID . '&msg=error');
    exit;
}
