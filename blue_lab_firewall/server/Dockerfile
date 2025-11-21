FROM debian:12-slim

RUN apt-get update && apt-get install -y nginx openssh-server vsftpd net-tools iproute2 iputils-ping && rm -rf /var/lib/apt/lists/*

# create SSH user
RUN useradd -m labuser && echo "labuser:labpass" | chpasswd

RUN mkdir /var/run/sshd

# Copy web page
COPY index.html /var/www/html/index.html

# simple FTP settings
RUN echo "listen=YES" > /etc/vsftpd.conf && echo "listen_ipv6=NO" >> /etc/vsftpd.conf &&  echo "local_enable=YES" >> /etc/vsftpd.conf && echo "write_enable=YES" >> /etc/vsftpd.conf
RUN mkdir -p /var/ftp && chmod 777 /var/ftp
#COPY vsftpd.conf /etc/vsftpd.conf

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80 22 21

CMD ["/start.sh"]
