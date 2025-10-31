echo "starting redis"
redis-server &
exho "starting web service"
node xss.js