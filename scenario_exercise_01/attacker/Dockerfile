FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y nmap curl openssh-client iputils-ping \
    && rm -rf /var/lib/apt/lists/*

CMD ["tail", "-f", "/dev/null"]
