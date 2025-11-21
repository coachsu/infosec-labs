FROM debian:12-slim

RUN apt-get update &&             apt-get install -y iproute2 iptables tcpdump iputils-ping &&             rm -rf /var/lib/apt/lists/*

COPY firewall.sh /usr/local/bin/firewall.sh
RUN chmod +x /usr/local/bin/firewall.sh

CMD ["/usr/local/bin/firewall.sh"]
