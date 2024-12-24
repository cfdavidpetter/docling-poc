FROM nginx:1.25.2-alpine
RUN apk update && apk add --no-cache openssl bash nodejs npm bash

RUN rm /etc/nginx/conf.d/default.conf
COPY ./nginx.conf /etc/nginx/conf.d