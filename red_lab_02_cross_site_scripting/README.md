# 實驗說明
- 類別: 紅隊
- 名稱: 跨站指令碼攻擊(Cross Site Scripting, XSS)

## 目的
學員可以透過此實驗學習
1. JavaScript (JS) 基本語法
2. 透過進行 XSS 攻擊理解其原理
3. 如何防範 XSS 攻擊

## 專案目錄
- red_02_cross_site_scripting
  - js_example (JavaScript 範例)
  - xss_dom (DOM 型 XSS)
  - xss_relfected (反射型 XSS)
  - xss_stored (儲存型 XSS)

## 實驗步驟
Step 1. 在**前景**建立並啟動 docker compose 服務

```shell
docker compose up -d
```

Step 2. 開啟瀏覽器並前往不同 XSS 攻擊的端點

- DOM 型 XSS [http://127.0.0.1:8080](http://127.0.0.1:8080)
- 反射型 XSS [http://127.0.0.1:8081](http://127.0.0.1:8081)
- 儲存型 XSS [http://127.0.0.1:8082](http://127.0.0.1:8082)

Step 3. 根據不同 XSS 攻擊的說明進行實驗並製做報告

Step 4. 停止並刪除 docker compose 服務
```shell
docker compose down --rmi all
```

## 實驗內容
### A. JS 語法練習
進入 js_example 資料夾，練習撰寫 JS 程式並實作以下程式

  - 設計一個網頁程式讓使用者可以輸入帳號(user)與密碼(pass)。其中，密碼在輸入時需要遮蔽。
  - 使用者按下登入後
    - 以 JS 程式在網頁上顯示 user 已登入
    - 以 JS 程式將 pass 顯示在彈出視窗中。

內容要求
  - 附上 JS 程式與執行結果

### B. DOM 型 XSS 攻擊
進入 DOM 型 XSS 端點，嘗試進行 DOM 型 XSS 攻擊以符合下列請求

  1. 讓網頁產生一個 prompt 彈跳視窗並將使用者在 prompt 中輸入的字串顯示在一個 alert 彈跳視窗

內容要求
  - 針對每個問題寫出對應的(1) URL 與(2)執行結果

### C. 反射型 XSS 攻擊
進入反射型 XSS 端點，嘗試進行反射型 XSS 攻擊以符合下列請求

  1. 讓網頁跳轉到指定的網頁
  [http://127.0.0.1:8082/fish.html](http://127.0.0.1:8082/fish.html)

內容要求
  - 針對每個問題寫出對應的(1) URL 與(2)執行結果

### D. 儲存型 XSS 攻擊
進入儲存型 XSS 端點，嘗試進行儲存型 XSS 攻擊以符合下列請求

  1. 在網頁呈現與實驗 A 的結果一樣

內容要求
  - 針對每個問題寫出對應的(1) URL 與(2)執行結果

# 實驗報告格式
實驗報告應包含(但不限於以下內容)

1. 課程/主題
2. 實驗日期
3. 實驗目的
4. 背景與理論
5. 實驗過程結果(每個問題)
6. 結論與心得
8. 參考文獻
