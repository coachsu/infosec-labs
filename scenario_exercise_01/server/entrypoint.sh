#!/bin/bash
set -e

echo "=== Starting SSH on port 2857 ==="
service ssh start

echo "=== Starting PostgreSQL on port 6666 ==="
service postgresql start

echo "=== Waiting for PostgreSQL to be ready ==="
until pg_isready -h localhost -p 6666 -U postgres; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 1
done

# Initialize DB once
if [ ! -f /var/lib/postgresql/.db_initialized ]; then
  echo "=== Initializing database and seeding sample data ==="
  su - postgres -c "/usr/bin/python3 /app/init_db.py"
  touch /var/lib/postgresql/.db_initialized
else
  echo "=== Database already initialized, skipping ==="
fi

# Random Flask port between 15000 and 15100
FLASK_PORT=$(shuf -i 15000-15100 -n 1)
export FLASK_PORT

echo "=== Starting Flask app on port ${FLASK_PORT} ==="
python3 /app/app.py
