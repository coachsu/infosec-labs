FROM node:slim
RUN mkdir /usr/src/xss
WORKDIR /usr/src/xss
COPY site/ .
RUN npm install
CMD [ "node", "xss.js"]