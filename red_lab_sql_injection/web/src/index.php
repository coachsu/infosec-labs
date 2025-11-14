<?php
// index.php — SQL 注入攻擊教學用

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'userdb';
$user = getenv('DB_USER') ?: 'app';
$pass = getenv('DB_PASS') ?: 'app123';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

$message = '';
$ran_sql = null;
$elapsed = null;
$logged_in_row = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = "SELECT id, name, username, email, role FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
    $ran_sql = $sql;

    $start = microtime(true);
    try {
        $res = $conn->query($sql);
    } catch (Exception $e) {
        $message = "<div class='error'>Query error: " . htmlspecialchars($e->getMessage()) . "</div>";
        $res = false;
    }
    $elapsed = round((microtime(true) - $start) * 1000, 2);

    if ($res && $res->num_rows > 0) {
        $logged_in_row = $res->fetch_assoc();
        $message = "<div class='notice'>Logged in as <strong>" . htmlspecialchars($logged_in_row['name']) . "</strong> (username: " . htmlspecialchars($logged_in_row['username']) . ")<br>"
                 . "Email: " . htmlspecialchars($logged_in_row['email']) . " &nbsp; | &nbsp; Role: " . htmlspecialchars($logged_in_row['role']) . "</div>";
    } else {
        $message = "<div class='error'>Login failed</div>";
    }
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>登入</title>
<style>
body{font-family:system-ui,Segoe UI,Roboto,"Noto Sans TC",sans-serif;background:#f5f7fa;margin:0;color:#111}
.wrap{max-width:900px;margin:28px auto;padding:18px}
.card{background:#fff;padding:16px;border-radius:10px;box-shadow:0 1px 4px rgba(16,24,40,0.06)}
label.small{font-size:13px;color:#666}
input[type=text], input[type=password]{width:100%;padding:10px;border:2px solid #e6edf3;border-radius:8px;font-size:14px}
.controls{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap}
.btn{padding:8px 12px;border-radius:8px;border:1px solid #e6edf3;background:#fff;cursor:pointer}
.btn-primary{background:#0066ff;color:#fff;border-color:#0066ff}
.small{font-size:13px;color:#666}
.notice{padding:10px;background:#f1f8ff;border:1px solid #cfe4ff;border-radius:8px;margin-top:12px}
.error{padding:10px;background:#fff1f0;border:1px solid #ffd7d2;border-radius:8px;margin-top:12px}
.meta{margin-top:10px;font-size:13px;color:#444}
.footer{margin-top:12px;font-size:13px;color:#666}
hr{border:none;height:1px;background:#eee;margin:16px 0}
.kbd{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, "Noto Sans Mono", monospace;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:6px;padding:0 6px}
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>登入(SQL 注入攻擊教學用)</h2>
      <p class="small">請輸入帳號/密碼</p>

      <form method="post" autocomplete="off">
        <div style="display:grid;grid-template-columns:1fr;gap:12px">
          <div>
            <label class="small">帳號</label>
            <input name="username" type="text" placeholder="例如：admin" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
          </div>
          <div>
            <label class="small">密碼</label>
            <!-- 為了注入練習方便，仍使用 text（非 password）便於看見輸入 -->
            <input name="password" type="text" placeholder="例如：secret123 或 anything" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>">
          </div>
        </div>

        <div class="controls">
          <button class="btn btn-primary" type="submit">登入</button>
          <button class="btn" type="button" onclick="document.querySelector('[name=username]').value='';document.querySelector('[name=password]').value='';">清除</button>
        </div>
      </form>

      <?php
        if ($message) {
          echo $message;
        }
      ?>

      <?php if ($ran_sql): ?>
        <div class="meta">
          <strong>執行 SQL：</strong>
          <pre style="background:#fafafa;padding:8px;border-radius:6px;border:1px solid #eee;white-space:pre-wrap;"><?php echo htmlspecialchars($ran_sql); ?></pre>
          <?php if ($elapsed !== null): ?>
            耗時：<?php echo htmlspecialchars($elapsed); ?> ms
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="footer">
      <a href="sql.php">回到 SQL 練習頁面</a>
      <a href="search.php">回到搜尋頁面</a>|
      </div>
    </div>
  </div>
</body>
</html>
