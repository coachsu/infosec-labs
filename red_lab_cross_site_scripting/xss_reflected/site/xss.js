const express = require('express')
const app = express()

// 模擬惡意網站
app.use(express.static('hack-site'))

// 模擬具有反射式XSS安全性威脅的網站
app.get('/hello', (req, res) => {
    // TODO: 應該檢查輸入排除反射式XSS攻擊
    res.send(`Hello, ${req.query.name}!`)    
})

app.listen(80, () => console.log('Listening on port 80!'))