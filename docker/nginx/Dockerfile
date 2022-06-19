FROM nginx:latest

#ADD ./nginx/vhost.conf /etc/nginx/conf.d/default.conf

RUN apt-get update && apt-get install -y \
        git \
        curl \
        wget \
        libpq-dev \
        libfreetype6-dev \
        nano \
        zip \
        unzip \
        locales \
        libmcrypt-dev \
        libpng-dev zlib1g-dev libicu-dev g++ libmagickwand-dev --no-install-recommends libxml2-dev

WORKDIR /var/www
