FROM node:slim
RUN mkdir /usr/src/xss
WORKDIR /usr/src/xss
COPY site/ .
RUN npm install
RUN apt update
RUN apt install redis -y
# RUN redis-server &
#CMD ["node","xss.js"]
CMD ["bash", "start.sh"]