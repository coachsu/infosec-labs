import os
import psycopg2
from flask import Flask, request, render_template_string

PG_PORT = 6666

app = Flask(__name__)

HTML_PAGE = """
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>使用者查尋</title>
</head>
<body>
    <h1>114學年度資訊安全實驗期中考</h1>

    <h2>使用者查詢</h2>
    <form method="post" action="/lookup">
        <label>帳號(id)：</label>
        <input type="text" name="id" size="40" value="{{ last_id or '' }}">
        <button type="submit">查詢</button>
    </form>

    {% if sql_string %}
    <h3>實際執行的 SQL (實際上應該看不到)：</h3>
    <pre>{{ sql_string }}</pre>
    {% endif %}

    {% if lookup_results is not none %}
        <h3>查詢結果：</h3>
        {% if lookup_results %}
            <table border="1" cellpadding="4" cellspacing="0">
                <tr>
                    <th>帳號</th><th>電話</th><th>地址</th><th>Comment</th>
                </tr>
                {% for row in lookup_results %}
                <tr>
                    <td>{{ row.id }}</td>
                    <td>{{ row.phone }}</td>
                    <td>{{ row.address }}</td>
                    <td>{{ row.comment|safe }}</td>
                </tr>
                {% endfor %}
            </table>
        {% else %}
            <p>查無資料。</p>
        {% endif %}
    {% endif %}
</body>
</html>
"""

def get_connection():
    return psycopg2.connect(
        dbname="userdb",
        user="postgres",
        host="localhost",
        port=PG_PORT,
    )

@app.route("/", methods=["GET"])
def index():
    return render_template_string(
        HTML_PAGE,
        lookup_results=None,
        sql_string=None,
        last_id=None,
        add_message=None,
    )

@app.route("/lookup", methods=["POST"])
def lookup():
    id = request.form.get("id", "")
    conn = get_connection()
    cur = conn.cursor()

    # Intentionally vulnerable SQL injection via string concatenation
    sql_string = f"SELECT id, phone, address, comment FROM users WHERE id = '{id}';"
    print("[DEBUG] Executing SQL:", sql_string)
    cur.execute(sql_string)

    results =[]

    try:
        rows = cur.fetchall()
        results = [
            {
                "id": r[0],
                "phone": r[1],
                "address": r[2],
                "comment": r[3] or "",
            }
            for r in rows
        ]   
    except Exception as e:
        conn.commit()
        print("Error fetching results:", e)

    cur.close()
    conn.close()

    return render_template_string(
        HTML_PAGE,
        lookup_results=results,
        sql_string=sql_string,
        last_id=id,
        add_message=None,
    )

if __name__ == "__main__":
    port = int(os.environ.get("FLASK_PORT", "5000"))
    print(f"Flask running on port {port}")
    app.run(host="0.0.0.0", port=port)
