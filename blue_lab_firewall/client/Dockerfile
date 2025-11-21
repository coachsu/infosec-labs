FROM debian:12-slim

RUN apt-get update && apt-get install -y iproute2 iputils-ping curl dnsutils nmap net-tools openssh-client ftp && rm -rf /var/lib/apt/lists/*

CMD ["bash"]
