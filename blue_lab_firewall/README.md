# 實驗說明
- 類別: 藍隊
- 名稱: 防火牆(Firewall)

## 目的
學員可以透過此實驗學習
1. 了解防火牆的基本功能
2. 了解防火牆規則設定原則
3. 學如如何使用 iptables


## 專案目錄
- blue_lab_01_firewall
  - client (客戶端 / nmap, ping, curl, ssh, ftp)
  - firewall (防火牆 / iptables)
  - server (伺服器 / http, sshd, ssh)
    - 帳號: labuser
    - 密碼: labpass
  - compose.yaml

## 實驗步驟
Step 1. 在**背景**建立並啟動 docker compose 服務

```shell
docker compose up -d --build
```

Step 2. 進入容器的終端機

進入防火牆容器的終端機
```shell
docker exec -i -t firewall bash
```

進入客戶端容器的終端機
```shell
docker exec -i -t client[1/2/3] bash
```

Step 3. 完成實驗並製做報告

Step 4. 停止並刪除 docker compose 服務
```shell
docker compose down --rmi all
```

## 實驗內容
### 實驗 A. 防火牆現況檢查

Step 1. 完成以下問題
  1. 確認目前 iptables filter 目前規則
  2. 分別確認 client[1/2/3]是否可以使用server上的 http, ssh, ftp 服務

內容要求
  - 針對每個問題寫出對應的操作與結果

### 實驗 B. 預設拒絕原則

Step 1. 完成以下問題
  1. 依據預設拒絕原則將 iptables filter 設定成拒絕所有流量。
  2. 分別確認 client[1/2/3]是否可以使用server上的 http, ssh, ftp 服務

內容要求
  - 針對每個問題寫出對應的操作與結果

### 實驗 C. 最小開放原則

Step 1. 完成以下問題
  1. 依據預設拒絕原則將 iptables filter 設成以下設定
      - 允許所有 http 服務
      - 只允許 client[1/2] 的 ssh 服務 
  2. 分別確認 client[1/2/3]是否可以使用server上的 http, ssh, ftp 服務

內容要求
  - 針對每個問題寫出對應的操作與結果

### 挑戰題. 連線限制

Step 1. 完成以下問題
  1. 透過 connlimit-above 模組設定同 IP 超過 3 個 ssh 連線就 DROP
  2. 透過 recent 模組設定同 IP 在 60 秒內是否嘗試連現 5 次就 DROP 
  3. 確認 client1 在嘗試同時登入第4次時會遭到拒絕
  4. 確認 client1 在連續 60 秒內嘗試連線超過 5 次會遭到拒絕

內容要求
  - 針對每個問題寫出對應的操作與結果

# 實驗報告格式
實驗報告應包含(但不限於以下內容)

1. 課程/主題
2. 實驗日期
3. 實驗目的
4. 背景與理論
5. 實驗過程與結果(針對每個實驗的每個問題)
6. 結論與心得
8. 參考文獻




# security-firewall-lab

Docker Compose lab that demonstrates a simple network-level firewall (iptables) between clients and an internal server.

## 結構
- firewall/: firewall container (iptables)
- server/: internal server (HTTP, SSH, FTP)
- client/: client image (with nmap, curl, ping)

## 快速上手
1. 在含有 docker 與 docker compose 的環境中，解壓或 clone 此專案至一個資料夾。
2. 在該資料夾執行：
   ```
   docker compose up -d --build
   ```
3. 進入 client：
   ```
   docker exec -it client1 bash
   ```
4. 在 client 內可以執行：
   - `curl http://172.21.0.10`
   - `ssh labuser@172.21.0.10` (password: labpass)
   - `nmap 172.21.0.10`

## 注意事項
- 本實驗將 iptables 規則設定在 firewall container，容器需具備 NET_ADMIN 權限。
- 若在某些環境中需要模擬更接近實際路由，可能須在 host 端或使用 macvlan networks。
