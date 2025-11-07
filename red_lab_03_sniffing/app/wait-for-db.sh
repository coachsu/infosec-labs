#!/bin/sh
set -e

python - <<'PYCODE'
import os, time, psycopg2
host=os.environ.get("POSTGRES_HOST","db")
port=int(os.environ.get("POSTGRES_PORT","5432"))
db=os.environ.get("POSTGRES_DB","appdb")
user=os.environ.get("POSTGRES_USER","appuser")
pw=os.environ.get("POSTGRES_PASSWORD","apppass")

for i in range(60):
    try:
        conn = psycopg2.connect(host=host, port=port, dbname=db, user=user, password=pw)
        conn.close()
        print("DB is ready")
        break
    except Exception as e:
        print("DB not ready yet:", e)
        time.sleep(2)
else:
    raise SystemExit("DB not ready after retries")
PYCODE

exec gunicorn --bind 0.0.0.0:8000 app:app --workers 2
