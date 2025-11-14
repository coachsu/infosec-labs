<?php
// sql.php — SQL 練習

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'userdb';
$user = getenv('DB_USER') ?: 'app';
$pass = getenv('DB_PASS') ?: 'app123';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

function starts_with_any($str, $prefixes) {
    foreach ($prefixes as $p) {
        if (stripos(ltrim($str), $p) === 0) return true;
    }
    return false;
}

function render_table($res) {
    if (!($res instanceof mysqli_result)) {
        echo "<p class='muted'>No result set to display.</p>";
        return;
    }
    echo "<table class='result-table'><thead><tr>";
    foreach ($res->fetch_fields() as $f) {
        echo "<th>" . htmlspecialchars($f->name) . "</th>";
    }
    echo "</tr></thead><tbody>";
    $res->data_seek(0);
    while ($row = $res->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $v) {
            echo "<td>" . htmlspecialchars((string)$v) . "</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
}

$execute_sql = '';
$messages = [];
$runResult = null;
$elapsed = null;
$affected = null;
$ran_sql_shown = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $execute_sql = isset($_POST['sql']) ? trim($_POST['sql']) : '';
  if ($execute_sql === '') {
    $messages[] = "請輸入 SQL。";
  } else {
    $sql_to_run = $execute_sql;
    $ran_sql_shown = $sql_to_run;

    $start = microtime(true);
    try {
      $mysqli->multi_query($sql_to_run);
      do {
        if($res = $mysqli->store_result()) {
          $runResult = $res;
        } else {
          if($mysqli->errno) {
            $res = false;
            $messages[] = "執行錯誤： " . htmlspecialchars($mysqli->error);
            break;
          } else {
            $affected = $mysqli->affected_rows;
          }
        }
      } while($mysqli->more_results() && $mysqli->next_result());
    } catch (Exception $e) {
      $res = false;
      $messages[] = "執行錯誤： " . htmlspecialchars($mysqli->error);
    }
    $elapsed = round((microtime(true)-$start)*1000, 2);
  }
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>SQL 練習</title>
<style>
body{font-family:system-ui,Segoe UI,Roboto,"Noto Sans TC",sans-serif;background:#f5f7fa;margin:0;color:#111}
.wrap{max-width:900px;margin:28px auto;padding:18px}
.card{background:#fff;padding:16px;border-radius:10px;box-shadow:0 1px 4px rgba(16,24,40,0.06)}
label.small{font-size:13px;color:#666}
textarea{width:100%;min-height:180px;font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, "Noto Sans Mono", monospace;font-size:14px;padding:10px;border:2px solid #e6edf3;border-radius:8px;resize:vertical}
.controls{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap}
.btn{padding:8px 12px;border-radius:8px;border:1px solid #e6edf3;background:#fff;cursor:pointer}
.btn-primary{background:#0066ff;color:#fff;border-color:#0066ff}
.small{font-size:13px;color:#666}
.muted{color:#666}
.result-table{width:100%;border-collapse:collapse;margin-top:12px}
.result-table th, .result-table td{padding:8px;border-bottom:1px solid #f1f5f9;text-align:left}
.notice{padding:10px;background:#f1f8ff;border:1px solid #cfe4ff;border-radius:8px;margin-top:12px}
.error{padding:10px;background:#fff1f0;border:1px solid #ffd7d2;border-radius:8px;margin-top:12px}
.meta{margin-top:8px;font-size:13px;color:#444}
.footer{margin-top:12px;font-size:13px;color:#666}
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>SQL 練習</h2>
      <p class="small">直接貼入一個 SQL 字串並執行。</p>

      <form method="post">
        <label class="small">SQL：</label>
        <textarea name="sql" placeholder="例如：SELECT id, username, email, role FROM users WHERE username = 'admin';"><?php echo htmlspecialchars($execute_sql); ?></textarea>

        <div class="controls">
          <button class="btn btn-primary" type="submit">執行</button>
          <button class="btn" type="button" onclick="document.querySelector('textarea[name=sql]').value='';">清除</button>
        </div>
      </form>

      <?php foreach ($messages as $m): ?>
        <div class="<?php echo stripos($m,'錯誤')!==false ? 'error' : 'notice'; ?>"><?php echo $m; ?></div>
      <?php endforeach; ?>

      <?php if ($ran_sql_shown): ?>
        <div class="meta"><strong>執行 SQL：</strong><pre style="background:#fafafa;padding:8px;border-radius:6px;border:1px solid #eee;"><?php echo htmlspecialchars($ran_sql_shown); ?></pre></div>
      <?php endif; ?>

      <?php if ($elapsed !== null): ?>
        <div class="meta">耗時：<?php echo htmlspecialchars($elapsed); ?> ms</div>
      <?php endif; ?>

      <?php if ($runResult !== null): ?>
        <div style="margin-top:12px">
          <?php render_table($runResult); ?>
        </div>
      <?php elseif ($affected !== null): ?>
        <div class="meta">受影響列數：<?php echo htmlspecialchars($affected); ?></div>
      <?php endif; ?>

      <div class="footer">
        <a href="index.php">回到登入頁面</a>|
        <a href="search.php">回到搜尋頁面</a>
      </div>
    </div>
  </div>
</body>
</html>
