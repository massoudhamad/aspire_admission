<?php
/*
 * rule_builder.php — Admin UI for building the programrequirements.ruleGroups
 * JSON tree with AND / OR groupings.
 *
 * Included by mainindex.php via ?sp=rule_builder.
 *
 * URL:
 *   index3.php?sp=rule_builder                           -> pick a programme
 *   index3.php?sp=rule_builder&programmeMajorID=<id>     -> edit its rules
 *
 * Save endpoint: action_rule_builder.php
 */
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($db)) {
    require_once __DIR__ . '/DB.php';
    $db = new DBHelper();
}

$programmeMajorID = isset($_GET['programmeMajorID']) ? (int)$_GET['programmeMajorID'] : 0;

/* ---------- pick a programme ---------- */
if ($programmeMajorID === 0) {
    ?>
    <div class="content-wrapper">
      <section class="content-header"><h1>Program Rules (AND / OR) <small>Pick a programme to edit</small></h1></section>
      <section class="content">
        <div class="box box-primary">
          <div class="box-body">
            <table class="table table-striped table-hover">
              <thead><tr><th>#</th><th>Code</th><th>Programme</th><th>Requires Prior Level</th><th style="width:120px;">Rule set?</th><th style="width:130px;"></th></tr></thead>
              <tbody>
              <?php
              /* List all published programme majors with their current rule state. */
              $rows = $db->getRows('programmemajor', array('where' => array('publishStatus' => 1)));
              $i = 0;
              foreach ((array)$rows as $r) {
                  $i++;
                  $pmid    = (int)$r['programmeMajorID'];
                  $prog    = htmlspecialchars($r['programmeMajor'] ?? '');
                  $code    = htmlspecialchars($r['majorCode'] ?? '');
                  $prReq   = $db->getRows('programrequirements', array('where' => array('programmeMajorID' => $pmid)));
                  $prior   = !empty($prReq[0]['requiresPriorLevel']) ? $prReq[0]['requiresPriorLevel'] : '—';
                  $hasRule = !empty($prReq[0]['ruleGroups']);
                  echo "<tr>";
                  echo "  <td>$i</td>";
                  echo "  <td>$code</td>";
                  echo "  <td>$prog</td>";
                  echo "  <td>" . htmlspecialchars($prior) . "</td>";
                  echo "  <td>" . ($hasRule
                      ? "<span class='label label-success'>Defined</span>"
                      : "<span class='label label-default'>None</span>") . "</td>";
                  echo "  <td><a href='index3.php?sp=rule_builder&programmeMajorID=$pmid' class='btn btn-primary btn-sm'>";
                  echo         ($hasRule ? '<i class=\"fa fa-edit\"></i> Edit' : '<i class=\"fa fa-plus\"></i> Define') . "</a></td>";
                  echo "</tr>";
              }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>
    <?php
    return;
}

/* ---------- edit a specific programme's rules ---------- */

$pmRow = $db->getRows('programmemajor', array('where' => array('programmeMajorID' => $programmeMajorID)));
if (empty($pmRow)) {
    echo '<div class="alert alert-danger">Programme not found (programmeMajorID=' . (int)$programmeMajorID . ').</div>';
    return;
}
$programmeName = $pmRow[0]['programmeMajor'] ?? '';
$majorCode     = $pmRow[0]['majorCode']       ?? '';

$reqRow = $db->getRows('programrequirements', array('where' => array('programmeMajorID' => $programmeMajorID)));
$currentPrior      = !empty($reqRow) ? ($reqRow[0]['requiresPriorLevel'] ?? 'None') : 'None';
$currentRuleGroups = !empty($reqRow) ? ($reqRow[0]['ruleGroups'] ?? '') : '';
if (empty($currentRuleGroups)) $currentRuleGroups = '[]';

/* Subject list for the subject_grade dropdown */
$subjectRows = $db->getRows('subjects');
$subjectNames = array();
foreach ((array)$subjectRows as $sr) {
    if (!empty($sr['subjectName'])) $subjectNames[] = $sr['subjectName'];
}
$subjectNames = array_values(array_unique($subjectNames));
sort($subjectNames);

/* Grade list — the canonical set (A, B+, B, C, D) */
$grades = array('A', 'B+', 'B', 'C', 'D');
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>Program Rules — <?php echo htmlspecialchars($programmeName); ?>
      <small><?php echo htmlspecialchars($majorCode); ?></small></h1>
    <ol class="breadcrumb">
      <li><a href="index3.php?sp=rule_builder"><i class="fa fa-list"></i> All programmes</a></li>
      <li class="active"><?php echo htmlspecialchars($programmeName); ?></li>
    </ol>
  </section>

  <section class="content">
    <form id="rule-builder-form" method="post" action="action_rule_builder.php">
      <input type="hidden" name="programmeMajorID" value="<?php echo (int)$programmeMajorID; ?>">
      <input type="hidden" name="ruleGroups"       id="ruleGroups"       value="">
      <input type="hidden" name="requiresPriorLevel" id="requiresPriorLevelHidden" value="<?php echo htmlspecialchars($currentPrior); ?>">

      <div class="box box-info">
        <div class="box-header with-border"><h3 class="box-title">Ladder gate</h3></div>
        <div class="box-body">
          <label for="requiresPriorLevel">Requires a prior qualification:</label>
          <select id="requiresPriorLevel" class="form-control" style="max-width:340px;">
            <option value="None" <?php if ($currentPrior === 'None' || $currentPrior === '') echo 'selected'; ?>>None &mdash; no ladder</option>
            <option value="BC"   <?php if ($currentPrior === 'BC')   echo 'selected'; ?>>Basic Certificate (BC)</option>
            <option value="TC"   <?php if ($currentPrior === 'TC')   echo 'selected'; ?>>Technician Certificate (TC)</option>
          </select>
          <p class="help-block" style="margin-top:6px;">Applicants without a saved BC/TC row will be rejected outright, before any rule matching runs.</p>
        </div>
      </div>

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Rule tree (groups joined by <strong>OR</strong>, rules inside a group joined by <strong>AND</strong>)</h3>
        </div>
        <div class="box-body">
          <div id="rule-groups"></div>
          <button type="button" class="btn btn-default" id="add-group"><i class="fa fa-plus-circle"></i> OR &mdash; Add another group</button>
          <hr>
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save rules</button>
          <a href="index3.php?sp=rule_builder" class="btn btn-link">Cancel</a>
        </div>
      </div>
    </form>
  </section>
</div>

<style>
  .rb-group { border:1px solid #CBD5E1; border-radius:6px; padding:14px; margin-bottom:14px; background:#F8FAFC; }
  .rb-group-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
  .rb-group-title { font-weight:700; color:#1B3A5C; }
  .rb-rule { display:grid; grid-template-columns:170px repeat(4, 1fr) 44px; gap:8px; align-items:center; margin:6px 0; }
  .rb-and-label { text-align:center; color:#6B7280; font-size:12px; font-weight:700; margin:4px 0; }
  .rb-or-label  { text-align:center; color:#C9A227; font-size:13px; font-weight:700; margin:8px 0; }
  .rb-rm { color:#C0392B; background:transparent; border:0; font-size:18px; }
  .rb-actions { display:flex; gap:6px; }
  /* subject_list checkbox list: click each subject to toggle. */
  .rb-subject-checklist {
    height: 140px;
    overflow-y: auto;
    padding: 8px 10px !important;
    background: #fff;
  }
  .rb-subj-item {
    display: block;
    margin: 0;
    padding: 3px 0;
    font-weight: 400 !important;
    font-size: 13px;
    color: var(--ichas-ink);
    cursor: pointer;
    user-select: none;
  }
  .rb-subj-item:hover { color: var(--ichas-navy); }
  .rb-subj-chk { margin-right: 6px; vertical-align: middle; }
</style>

<script type="text/javascript">
(function () {
  var SUBJECTS = <?php echo json_encode($subjectNames); ?>;
  var GRADES   = <?php echo json_encode($grades); ?>;
  var initial  = <?php echo $currentRuleGroups; ?>;

  var $wrap = document.getElementById('rule-groups');
  var $addGroup = document.getElementById('add-group');
  var $priorSelect = document.getElementById('requiresPriorLevel');
  var $priorHidden = document.getElementById('requiresPriorLevelHidden');
  var $form = document.getElementById('rule-builder-form');
  var $ruleGroupsHidden = document.getElementById('ruleGroups');

  function opt(v, label, sel) {
    var o = document.createElement('option');
    o.value = v; o.textContent = label;
    if (sel === v) o.selected = true;
    return o;
  }

  function renderRule(rule) {
    var row = document.createElement('div'); row.className = 'rb-rule';

    // Type dropdown
    var type = document.createElement('select'); type.className = 'form-control rb-type';
    type.appendChild(opt('subject_grade', 'Subject grade'));
    type.appendChild(opt('gpa',           'GPA'));
    type.appendChild(opt('subject_list',  'Subject list (any N of)'));
    type.value = (rule && rule.type) ? rule.type : 'subject_grade';
    row.appendChild(type);

    // Placeholder for type-specific inputs
    var wrap = document.createElement('div'); wrap.style.gridColumn = 'span 4'; wrap.style.display = 'contents';
    row.appendChild(wrap);

    function buildTypeInputs() {
      // remove old
      while (row.children.length > 2) row.removeChild(row.children[1]);

      if (type.value === 'subject_grade') {
        var sSel = document.createElement('select'); sSel.className = 'form-control rb-subject';
        sSel.appendChild(opt('', 'Subject…'));
        SUBJECTS.forEach(function (s) { sSel.appendChild(opt(s, s, rule && rule.subject)); });
        if (rule && rule.subject) sSel.value = rule.subject;
        row.insertBefore(sSel, row.children[1]);

        var gSel = document.createElement('select'); gSel.className = 'form-control rb-min-grade';
        gSel.appendChild(opt('', '≥ Grade…'));
        GRADES.forEach(function (g) { gSel.appendChild(opt(g, '≥ ' + g, rule && rule.min_grade)); });
        if (rule && rule.min_grade) gSel.value = rule.min_grade;
        row.insertBefore(gSel, row.children[2]);

        // spacers to align grid
        for (var i = 0; i < 2; i++) { var sp = document.createElement('span'); row.insertBefore(sp, row.children[3 + i]); }
      }
      else if (type.value === 'gpa') {
        var g1 = document.createElement('input'); g1.type = 'number'; g1.step = '0.01'; g1.min = '0'; g1.max = '5';
        g1.className = 'form-control rb-min-gpa'; g1.placeholder = 'GPA ≥ …';
        if (rule && rule.min_gpa !== undefined) g1.value = rule.min_gpa;
        row.insertBefore(g1, row.children[1]);
        for (var j = 0; j < 3; j++) { var sp2 = document.createElement('span'); row.insertBefore(sp2, row.children[2 + j]); }
      }
      else { // subject_list
        // Click-to-toggle checkbox list (no Cmd/Ctrl needed).
        // Presented in a scrollable card so the row height stays tidy.
        var sList = document.createElement('div');
        sList.className = 'form-control rb-subjects rb-subject-checklist';
        sList.setAttribute('role', 'group');
        var preSelected = (rule && Array.isArray(rule.subjects)) ? rule.subjects : [];
        SUBJECTS.forEach(function (s) {
          var lab = document.createElement('label');
          lab.className = 'rb-subj-item';
          var chk = document.createElement('input');
          chk.type  = 'checkbox';
          chk.value = s;
          chk.className = 'rb-subj-chk';
          if (preSelected.indexOf(s) !== -1) chk.checked = true;
          lab.appendChild(chk);
          lab.appendChild(document.createTextNode(' ' + s));
          sList.appendChild(lab);
        });
        row.insertBefore(sList, row.children[1]);

        var n = document.createElement('input'); n.type = 'number'; n.min = '1'; n.max = '10';
        n.className = 'form-control rb-min-count'; n.placeholder = 'Any N ≥';
        if (rule && rule.min_count) n.value = rule.min_count;
        row.insertBefore(n, row.children[2]);

        var gl = document.createElement('select'); gl.className = 'form-control rb-list-grade';
        gl.appendChild(opt('', 'grade…'));
        GRADES.forEach(function (g) { gl.appendChild(opt(g, g)); });
        if (rule && rule.min_grade) gl.value = rule.min_grade;
        row.insertBefore(gl, row.children[3]);

        var sp3 = document.createElement('span'); row.insertBefore(sp3, row.children[4]);
      }

      // Remove button always last
      if (!row.querySelector('.rb-rm')) {
        var rm = document.createElement('button');
        rm.type = 'button'; rm.className = 'rb-rm'; rm.title = 'Remove this rule';
        rm.innerHTML = '&times;';
        rm.addEventListener('click', function () { row.parentNode.removeChild(row); });
        row.appendChild(rm);
      }
    }

    type.addEventListener('change', buildTypeInputs);
    buildTypeInputs();
    return row;
  }

  function renderGroup(group, index, total) {
    var g = document.createElement('div'); g.className = 'rb-group';
    var h = document.createElement('div'); h.className = 'rb-group-header';
    var t = document.createElement('span'); t.className = 'rb-group-title';
    t.textContent = 'Group ' + (index + 1) + ' — all rules must match (AND)';
    h.appendChild(t);

    var rm = document.createElement('button'); rm.type = 'button'; rm.className = 'btn btn-link';
    rm.style.color = '#C0392B'; rm.innerHTML = '<i class="fa fa-trash"></i> Remove group';
    rm.addEventListener('click', function () { g.parentNode.removeChild(g); });
    h.appendChild(rm);
    g.appendChild(h);

    var rules = (group && group.rules) ? group.rules : [];
    if (rules.length === 0) rules = [{}];
    rules.forEach(function (r, i) {
      if (i > 0) {
        var lbl = document.createElement('div'); lbl.className = 'rb-and-label'; lbl.textContent = 'AND';
        g.appendChild(lbl);
      }
      g.appendChild(renderRule(r));
    });

    var addBtn = document.createElement('button');
    addBtn.type = 'button'; addBtn.className = 'btn btn-default btn-sm'; addBtn.style.marginTop = '8px';
    addBtn.innerHTML = '<i class="fa fa-plus"></i> AND — Add rule';
    addBtn.addEventListener('click', function () {
      var lbl = document.createElement('div'); lbl.className = 'rb-and-label'; lbl.textContent = 'AND';
      g.insertBefore(lbl, addBtn);
      g.insertBefore(renderRule({}), addBtn);
    });
    g.appendChild(addBtn);
    return g;
  }

  function addGroup(group) {
    var idx = $wrap.querySelectorAll('.rb-group').length;
    if (idx > 0) {
      var lbl = document.createElement('div'); lbl.className = 'rb-or-label'; lbl.textContent = '— OR —';
      $wrap.appendChild(lbl);
    }
    $wrap.appendChild(renderGroup(group, idx, 0));
  }

  // Init from server data
  if (Array.isArray(initial) && initial.length > 0) {
    initial.forEach(function (g) { addGroup(g); });
  } else {
    addGroup({});
  }
  $addGroup.addEventListener('click', function () { addGroup({}); });

  $priorSelect.addEventListener('change', function () { $priorHidden.value = $priorSelect.value; });

  // Serialise on submit
  $form.addEventListener('submit', function (e) {
    var groups = [];
    $wrap.querySelectorAll('.rb-group').forEach(function (g) {
      var rules = [];
      g.querySelectorAll('.rb-rule').forEach(function (row) {
        var t = row.querySelector('.rb-type').value;
        if (t === 'subject_grade') {
          var sub = row.querySelector('.rb-subject').value;
          var grd = row.querySelector('.rb-min-grade').value;
          if (sub && grd) rules.push({ type: 'subject_grade', subject: sub, min_grade: grd });
        } else if (t === 'gpa') {
          var g1 = parseFloat(row.querySelector('.rb-min-gpa').value);
          if (!isNaN(g1)) rules.push({ type: 'gpa', min_gpa: g1 });
        } else if (t === 'subject_list') {
          // Checkbox list — collect checked inputs.
          var subjects = [];
          row.querySelectorAll('.rb-subj-chk:checked').forEach(function (c) { subjects.push(c.value); });
          var n = parseInt(row.querySelector('.rb-min-count').value, 10);
          var grd2 = row.querySelector('.rb-list-grade').value;
          if (subjects.length && n && grd2) rules.push({ type: 'subject_list', subjects: subjects, min_count: n, min_grade: grd2 });
        }
      });
      if (rules.length) groups.push({ match: 'all', rules: rules });
    });
    $ruleGroupsHidden.value = JSON.stringify(groups);
  });
})();
</script>
