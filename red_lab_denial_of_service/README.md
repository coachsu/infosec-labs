# 實驗說明
- 類別: 紅隊
- 名稱: 阻斷服務攻擊(Denial of Service, DoS)

## 目的
學員可以透過此實驗學習
1. 透過進行 DoS 攻擊理解其原理
3. 如何在本機透過設定防範 DoS 攻擊

## 專案目錄
- red_denial_of_service
  - attacker (攻擊者)
  - web_http_protected
  - web_plain
  - web_syn_protected
  - compose.yaml

## 實驗步驟
Step 1. 建立並啟動 docker compose 服務

```shell
docker compose up -d --build
```

服務包含：

| 服務 | 說明 |
|-----|------|
| web | 網頁伺服器 |
| attacker | ab / hping3 工具 |

Step 2. 完成實驗並製做報告

Step 3. 停止並刪除 docker compose 服務
```shell
docker compose down --rmi all -v
```

## 實驗內容
### 實驗 A. HTTP Flood 攻擊
- 進入攻擊端
```
docker exec -i -t attacker bash
```

- 模擬以 HTTP Flood 攻擊網站

先模擬同時發出 10 個 HTTP 要求
```
ab -n 2000 -c 10 http://web/
```
觀察結果。

接著，模擬同時發出 50 個 HTTP 要求
```
ab -n 2000 -c 50 http://web/
```
觀察結果。

如有需要修改本機上的web/nginx.conf後執行下命令重啟 nginx 
```
docker exec web nginx -s reload
```

完成以下實驗

  1. 在 attacker 以不同的 HTTP 要求數量直到(10, 100, 200, 400, 800, 1000)，觀察並記錄不同 n 的實驗結果，包含失敗的要求數量、每個 HTTP 要求的處理時間、與最長的 HTTP 要求處理時間。
  2. 修改 web 的 nginx.conf 嘗試慢慢縮小每秒能接收受要求數量(x)、每個IP同時能接收的要求數量(y)，觀察與記錄不同(x, y)組合對實驗 1 中不同 n 值的實驗結果造成的影響。

內容要求
  - 不同的實驗組合至少要做 10 次並取得平均結果
  - 針對每個實驗組合提供
      1. 實驗結果資料表
      2. 以趨勢圖視覺化實驗結果
      3. 解釋實驗結果

# 實驗報告格式
實驗報告應包含(但不限於以下內容)

1. 課程/主題
2. 實驗日期
3. 實驗目的
4. 背景與理論
5. 實驗過程與結果(針對每個實驗的每個問題)
6. 結論與心得
8. 參考文獻