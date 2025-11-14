#!/bin/sh
set -e
SSL_DIR=/etc/nginx/ssl
mkdir -p "$SSL_DIR"
CRT=$SSL_DIR/server.crt
KEY=$SSL_DIR/server.key

if [ ! -f "$CRT" ] || [ ! -f "$KEY" ]; then
  echo "Generating self-signed certificate inside nginx container..."
  openssl req -x509 -nodes -days 365 -newkey rsa:2048     -keyout "$KEY" -out "$CRT"     -subj "/C=TW/ST=Taiwan/L=Taipei/O=Example/OU=IT/CN=example.local"
fi

exec "$@"
