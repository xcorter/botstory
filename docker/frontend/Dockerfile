FROM node:10-alpine

RUN mkdir -p /usr/src/frontend/public/build

WORKDIR /usr/src/frontend
COPY package.json yarn.lock ./

RUN yarn install

CMD yarn watch
