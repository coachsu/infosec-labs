# 實驗說明
- 類別: 紅隊
- 名稱: SQL 注入攻擊(SQL Injection)

## 目的
學員可以透過此實驗學習
1. SQL 基本語法
2. 透過進行 SQL 注入攻擊理解其原理
3. 如何防範 SQL 注入攻擊

## 專案目錄
- red_01_sql_injection
  - db
    - init.sql (資料庫初始化腳本)
  - web
    - src
      - index.php (登入頁面)
      - search.php (搜尋頁面)
      - sql.php (SQL 練習頁面)
    - Dockerfile
  - .env (環境變數)
  - compose.yaml

## 實驗步驟
Step 1. 在**前景**建立並啟動 docker compose 服務(SQL 注入範例)

```shell
docker compose up -d
```

Step 2. 開啟瀏覽器 [http://127.0.0.1:8080](http://127.0.0.1:8080)

Step 3. 完成實驗並製做報告

Step 4. 停止並刪除 docker compose 服務
```shell
docker compose down --rmi all -v
```

## 實驗內容
### 實驗 A. SQL 語法練習
進入「SQL 練習」頁面並請寫出符合以下各個請求

  1. 搜尋角色為 admin 的使用者並顯示所有欄位
  2. 搜尋角色為 user 的使用者並只顯示帳號與密碼
  3. 新增使用者 name='John Wicker', username='john', password='johnpass', email='jwicker@kill.com', role='admin'
  4. 刪除使用者 bob 後顯示所有使用者的資料

內容要求
  - 針對每個問題寫出對應的(1) SQL 語法與(2)輸出結果

### 實驗 B. 進行 SQL 注入攻擊
進入「登入」頁面，嘗試進行 SQL 注入攻擊以符合下列請求

  1. 繞過權限檢查登入(可以列出多種可能的攻擊與法)

進入「搜尋」頁面，嘗試進行 SQL 注入攻擊以符合下列請求

  2. 刪除 john
  3. 新增自己為使用者(姓名, 學號, 密碼, 電子郵件, 角色)

內容要求
  - 針對每個問題，寫出(1)輸入的內容、(2)結果、(3)思考模式

### 實驗 C. 防範 SQL 注入攻擊
修改「登入」頁面程式以符合下列請求

  1. 避免 SQL 注入攻擊

內容要求

  - 寫出(1)修改部分的前後對照與(2)思考模式

# 實驗報告格式
實驗報告應包含(但不限於以下內容)

1. 課程/主題
2. 實驗日期
3. 實驗目的
4. 背景與理論
5. 實驗過程與結果(針對每個實驗的每個問題)
6. 結論與心得
8. 參考文獻
