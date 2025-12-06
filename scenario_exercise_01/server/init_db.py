import psycopg2
from psycopg2 import sql
import random

PG_PORT = 6666

def ensure_database(conn, db_name):
    conn.autocommit = True
    cur = conn.cursor()
    cur.execute("SELECT 1 FROM pg_database WHERE datname = %s;", (db_name,))
    exists = cur.fetchone() is not None
    if not exists:
        print(f"Creating database {db_name} ...")
        cur.execute(sql.SQL("CREATE DATABASE {}").format(sql.Identifier(db_name)))
    else:
        print(f"Database {db_name} already exists.")
    cur.close()

def init_users_table(conn):
    cur = conn.cursor()
    # users table with comment column for XSS payload
    cur.execute("""
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY,
            name    VARCHAR(100),
            phone   VARCHAR(30),
            address VARCHAR(255),
            comment TEXT,
            password VARCHAR(100)
        );
    """)

    # Ensure comment column exists (for older versions)
    try:
        cur.execute("ALTER TABLE users ADD COLUMN IF NOT EXISTS comment TEXT;")
    except Exception as e:
        print("ALTER TABLE users ADD COLUMN comment failed (might already exist):", e)

    # Re-seed 10 users each init
    cur.execute("DELETE FROM users;")

    names = ["Alice", "Bob", "Charlie", "David", "Eva",
             "Frank", "Grace", "Heidi", "Ivan", "Judy"]
    streets = [
        "Taipei City Zhongzheng Dist.",
        "Taipei City Da'an Dist.",
        "New Taipei City Banqiao Dist.",
        "Taoyuan City Zhongli Dist.",
        "Taichung City Xitun Dist.",
        "Tainan City East Dist.",
        "Kaohsiung City Zuoying Dist.",
        "Yilan County Yilan City",
        "Hsinchu City East Dist.",
        "Keelung City Ren'ai Dist.",
    ]

    password = [
        "1qaz",
        "xsw2",
        "3edc",
        "vfr4",
        "5tgb",
        "nhy6",
        "7ujm",
        ",ki8",
        "9ol.",
        "/;p0",
    ]

    for i in range(10):
        id = i
        name = names[i]
        phone = f"09{random.randint(10000000, 99999999)}"
        address = streets[i] + f" No.{random.randint(1, 300)}"
        if i == 4:
            comment = "root"
        else:
            comment = "normal user"
        cur.execute(
            "INSERT INTO users (id, name, phone, address, comment, password) VALUES (%s, %s, %s, %s, %s, %s);",
            (id, name, phone, address, comment, password[i])
        )

    conn.commit()
    cur.close()

def main():
    # Connect to postgres system DB to create userdb
    conn = psycopg2.connect(
        dbname="postgres",
        user="postgres",
        host="localhost",
        port=PG_PORT,
    )
    ensure_database(conn, "userdb")
    conn.close()

    # Connect to userdb to create users table and seed data
    user_conn = psycopg2.connect(
        dbname="userdb",
        user="postgres",
        host="localhost",
        port=PG_PORT,
    )
    init_users_table(user_conn)
    user_conn.close()

if __name__ == "__main__":
    main()
