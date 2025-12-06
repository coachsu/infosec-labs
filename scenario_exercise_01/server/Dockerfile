FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Install Python, PostgreSQL, SSH
RUN apt-get update \
    && apt-get install -y python3 python3-pip postgresql postgresql-contrib postgresql-client openssh-server \
    && rm -rf /var/lib/apt/lists/*

# Configure SSH on port 2857, enable password auth (for lab only)
RUN mkdir -p /var/run/sshd \
    && sed -i 's/^#Port 22/Port 2857/' /etc/ssh/sshd_config \
    && sed -i 's/^#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config \
    && sed -i 's/^PasswordAuthentication no/PasswordAuthentication yes/' /etc/ssh/sshd_config \
    && sed -i 's/^#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config \
    && sed -i 's/^PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config 

# Change root password
RUN echo 'root:5tgb' | chpasswd

# Configure PostgreSQL: port 6666, listen on all interfaces, trust auth (lab only)
RUN set -eux; \
    # 修改 postgresql.conf：port、listen_addresses
    for conf in $(find /etc/postgresql -name postgresql.conf -print); do \
        sed -ri "s/^#?port = .*/port = 6666/" "$conf"; \
        sed -ri "s/^#?listen_addresses = .*/listen_addresses = '*'/" "$conf"; \
    done; \
    # 修改 pg_hba.conf：只允許本機存取
    for hba in $(find /etc/postgresql -name pg_hba.conf -print); do \
        # 本機 Unix socket
        sed -ri "s/^local\s+all\s+all\s+.*/local   all             all                                     trust/" "$hba"; \
        # IPv4 本機
        sed -ri "s/^host\s+all\s+all\s+127\.0\.0\.1\/32\s+.*/host    all             all             127.0.0.1\/32            trust/" "$hba"; \
        # IPv6 本機
        sed -ri "s/^host\s+all\s+all\s+::1\/128\s+.*/host    all             all             ::1\/128                 trust/" "$hba"; \
        # 加入拒絕所有外部 IPv4
        # 注意：要避免重複添加，先刪除舊版 0.0.0.0/0 規則
        sed -ri "/host\s+all\s+all\s+0\.0\.0\.0\/0/d" "$hba"; \
        echo "host    all             all             0.0.0.0/0               reject" >> "$hba"; \
        # 拒絕所有外部 IPv6
        sed -ri "/host\s+all\s+all\s+::\/0/d" "$hba"; \
        echo "host    all             all             ::/0                    reject" >> "$hba"; \
    done

WORKDIR /app

COPY requirements.txt .
RUN pip3 install --no-cache-dir -r requirements.txt

COPY app.py init_db.py entrypoint.sh ./
RUN chmod +x /app/entrypoint.sh

# Expose DB port, SSH port, and the random Flask port range
EXPOSE 6666 2857 10000-20000

ENTRYPOINT ["/app/entrypoint.sh"]
