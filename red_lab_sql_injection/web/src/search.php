<?php
// search.php — SQL 注入攻擊教學用

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'userdb';
$user = getenv('DB_USER') ?: 'app';
$pass = getenv('DB_PASS') ?: 'app123';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

$q = isset($_GET['q']) ? ($_GET['q']) : '';
$ran_sql = null;
$elapsed = null;
$result_rows = [];
$fields = [];

if ($q !== '') {
    $sql = "SELECT id, name, username, email, role FROM users WHERE username = '$q' OR name = '$q' OR email = '$q' OR role = '$q'";
    $ran_sql = $sql;
    $start = microtime(true);
    try {
      $conn->multi_query($sql);
      do {
        if($res = $conn->store_result()) {
          while ($row = $res->fetch_assoc()) {
            $result_rows[] = $row;
          }
          foreach ($res->fetch_fields() as $f) {
            $fields[] = $f->name;
          }
          if (empty($fields) && !empty($result_rows)) {
              $fields = array_keys($result_rows[0]);
          }
          $res->free();
        } else {
          if($conn->errno) {
              $error_msg = $conn->error;
              break;
          } else {
              $affected = $conn->affected_rows;
          }
        }
      } while($conn->more_results() && $conn->next_result());
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }
    
    $elapsed = round((microtime(true) - $start) * 1000, 2);
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>搜尋</title>
<style>
body{font-family:system-ui,Segoe UI,Roboto,"Noto Sans TC",sans-serif;background:#f5f7fa;margin:0;color:#111}
.wrap{max-width:980px;margin:28px auto;padding:18px}
.card{background:#fff;padding:16px;border-radius:10px;box-shadow:0 1px 4px rgba(16,24,40,0.06)}
label.small{font-size:13px;color:#666}
input[type=text]{width:100%;padding:10px;border:1px solid #e6edf3;border-radius:8px;font-size:14px}
.controls{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap}
.btn{padding:8px 12px;border-radius:8px;border:1px solid #e6edf3;background:#fff;cursor:pointer}
.btn-primary{background:#0066ff;color:#fff;border-color:#0066ff}
.small{font-size:13px;color:#666}
.notice{padding:10px;background:#f1f8ff;border:1px solid #cfe4ff;border-radius:8px;margin-top:12px}
.error{padding:10px;background:#fff1f0;border:1px solid #ffd7d2;border-radius:8px;margin-top:12px}
.meta{margin-top:10px;font-size:13px;color:#444}
.result-table{width:100%;border-collapse:collapse;margin-top:12px}
.result-table th, .result-table td{padding:8px;border-bottom:1px solid #f1f5f9;text-align:left}
.kbd{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, "Noto Sans Mono", monospace;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:6px;padding:0 6px}
.footer{margin-top:12px;font-size:13px;color:#666}
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>搜尋(SQL 注入攻擊教學用)</h2>
      <p class="small">可關鍵字搜尋 username / name / email / role。</p>

      <form method="get" autocomplete="off">
        <label class="small">搜尋字串(精確比對)：</label>
        <input type="text" name="q" placeholder="例如：admin" value="<?php echo htmlspecialchars($q); ?>">
        <div class="controls">
          <button class="btn btn-primary" type="submit">搜尋</button>
          <button class="btn" type="button" onclick="location.href='index.php';">清除</button>
        </div>
      </form>

      <?php if (isset($error_msg) && $error_msg): ?>
        <div class="error">執行錯誤：<?php echo htmlspecialchars($error_msg); ?></div>
      <?php endif; ?>

      <?php if ($ran_sql): ?>
        <div class="meta"><strong>執行 SQL：</strong>
          <pre style="background:#fafafa;padding:8px;border-radius:6px;border:1px solid #eee;white-space:pre-wrap;"><?php echo htmlspecialchars($ran_sql); ?></pre>
          <?php if ($elapsed !== null): ?>
            耗時：<?php echo htmlspecialchars($elapsed); ?> ms
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($result_rows)): ?>
        <div style="margin-top:12px">
          <table class="result-table">
            <thead>
              <tr>
                <?php foreach ($fields as $f): ?>
                  <th><?php echo htmlspecialchars($f); ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($result_rows as $r): ?>
                <tr>
                  <?php foreach ($fields as $f): ?>
                    <td><?php echo htmlspecialchars(isset($r[$f]) ? (string)$r[$f] : ''); ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php elseif ($ran_sql): ?>
        <div class="notice">查無結果。</div>
      <?php endif; ?>

      <div class="footer">
        <a href="sql.php">回到 SQL 練習頁面</a>
        <a href="index.php">回到登入頁面</a>|
      </div>
    </div>
  </div>
</body>
</html>
