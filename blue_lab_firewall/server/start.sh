#!/bin/sh

# start sshd
/usr/sbin/sshd

# start ftp
/usr/sbin/vsftpd /etc/vsftpd.conf &

# 刪除預設路由（若沒有也不會失敗）
ip route del default 2>/dev/null || true;
# 新增經由 firewall 的預設路由（若已存在也忽略錯誤）
ip route add default via 172.21.0.254;

# start nginx in foreground
nginx -g "daemon off;"
