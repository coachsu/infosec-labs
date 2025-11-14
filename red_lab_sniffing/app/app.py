from flask import Flask, render_template, request
import os, psycopg2, bcrypt

app = Flask(__name__)

DB_HOST = os.environ.get("POSTGRES_HOST", "db")
DB_PORT = int(os.environ.get("POSTGRES_PORT", 5432))
DB_NAME = os.environ.get("POSTGRES_DB", "appdb")
DB_USER = os.environ.get("POSTGRES_USER", "appuser")
DB_PASS = os.environ.get("POSTGRES_PASSWORD", "apppass")

def get_conn():
    return psycopg2.connect(
        host=DB_HOST, port=DB_PORT, dbname=DB_NAME, user=DB_USER, password=DB_PASS
    )

@app.route("/healthz")
def healthz():
    try:
        conn = get_conn()
        conn.close()
        return "ok", 200
    except Exception as e:
        return f"db error: {e}", 500

@app.route("/", methods=["GET"])
def index():
    return render_template("login.html")

@app.route("/login", methods=["POST"])
def login():
    username = request.form.get("username","").strip()
    password = request.form.get("password","").encode("utf-8")

    if not username or not password:
        return render_template("result.html", success=False, msg="缺少帳號或密碼")

    try:
        conn = get_conn()
        cur = conn.cursor()
        cur.execute("SELECT password_hash FROM users WHERE username = %s", (username,))
        row = cur.fetchone()
        cur.close()
        conn.close()
    except Exception as e:
        return render_template("result.html", success=False, msg=f"DB 連線錯誤: {e}")

    if not row:
        return render_template("result.html", success=False, msg="使用者不存在或密碼錯誤")

    stored = row[0]
    if isinstance(stored, str):
        stored = stored.encode("utf-8")
    if bcrypt.checkpw(password, stored):
        return render_template("result.html", success=True, msg="登入成功")
    else:
        return render_template("result.html", success=False, msg="使用者不存在或密碼錯誤")

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8000)
