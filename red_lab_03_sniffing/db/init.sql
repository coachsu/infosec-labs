CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(128) UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed users (bcrypt hashes for: alice/S3cretPass, bob/P@ssw0rd, carol/Changeme123)
INSERT INTO users (username, password_hash) VALUES
  ('alice', '$2b$12$SFZw6qdoaLd9icKFubQzA.fSOi37NxLTb6Wgg48v7qP6Z9FI2vA.q')
ON CONFLICT (username) DO NOTHING;

INSERT INTO users (username, password_hash) VALUES
  ('bob', '$2b$12$7NUF.tdZQ464Roc5wLtyU.NLzoj4TguKfVGafUaX4GcUJ1Wg2tjye')
ON CONFLICT (username) DO NOTHING;

INSERT INTO users (username, password_hash) VALUES
  ('carol', '$2b$12$X0VXv8kPWfa8XU1iCd7jlOvvKqukq1m7f5p6jdywM0eB4xZ7oQ8o6')
ON CONFLICT (username) DO NOTHING;
