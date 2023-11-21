FROM php:8.2

WORKDIR /app

COPY composer.json .
COPY composer.lock .

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-scripts --no-autoloader

COPY . /app

RUN composer dump-autoload --optimize

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

EXPOSE 8000

CMD ["symfony", "server:start"]
