#!/bin/sh

# start sshd
/usr/sbin/sshd

# start ftp
/usr/sbin/vsftpd /etc/vsftpd.conf &

# start nginx in foreground
nginx -g "daemon off;"
