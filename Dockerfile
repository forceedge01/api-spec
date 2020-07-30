FROM forceedge01/php7.2cli-composer:latest

WORKDIR '/app'
COPY composer.json .
COPY composer.lock .
RUN composer install
COPY . .

CMD ["composer", "run-script", "tests"]
