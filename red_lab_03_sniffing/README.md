# 實驗說明
- 類別: 紅隊
- 名稱: 監聽(Sniffing)

## 目的
學員可以透過此實驗學習
1. 使用使用 nmap 
2. 如何使用 Wireshark
3. 了解 HTTP 與 HTTPS 的差異

## 專案目錄
- red_lab_03_sniffing
  - app (Web 應用程式 - 簡單的登入頁面)
  - db (資料庫 - 用來儲存帳號密碼)
  - nginx (代理伺服器)
  - .env (環境變數)
  - compose.yaml

## 實驗步驟
Step 1. 在**背景**建立並啟動 docker compose 服務

```shell
docker compose up -d --build
```

Step 2. 完成實驗並製做報告

Step 3. 停止並刪除 docker compose 服務
```shell
docker compose down --rmi all -v
```

## 實驗內容
### 實驗 A. Nmap 實驗

Step 1. 找出名稱為 nmap 的 container 並進入容器的終端機

```shell
docker exec -i -t nmap sh
```

Step 2. 在 container 終端機中完成以下問題

  1. 列出在網域中線上的主機與對應的 IP 位址
  2. 列出每台主機開放的服務、軟體、與版本(如果存在)
  3. 列出每台主機的作業系統與版本(如果存在)

內容要求
  - 針對每個問題寫出對應的(1) nmap 指令與(2)輸出畫面與結果

### 實驗 B. 進行 SQL 注入攻擊
Step 1. 開啟瀏覽器並前往 [http://127.0.0.1](http://127.0.0.1) 或 [https://127.0.0.1](https://127.0.0.1)。(如果無法指定 HTTP 協定，請嘗試清除瀏覽器上的瀏覽紀錄)

Step 2. 開啟主機上的 Wireshark 並完成以下問題

  1.嘗試擷取 HTTP 協定下在網頁中輸入的帳號與密碼。(因為伺服器在主機上，網路介面卡請選擇 loopback)
  2.嘗試擷取 HTTPS 協定下在網頁中輸入的帳號與密碼。(因為伺服器在主機上，網路介面卡請選擇 loopback)

內容要求
  - 針對每個問題，寫出
    - 擷取過濾器設定(只能擷取單一主機上的 HTTP 或 HTTPS 封包)
    - 擷取的結果(如果可以)

# 實驗報告格式
實驗報告應包含(但不限於以下內容)

1. 課程/主題
2. 實驗日期
3. 實驗目的
4. 背景與理論
5. 實驗過程與結果(針對每個實驗的每個問題)
6. 結論與心得
8. 參考文獻
