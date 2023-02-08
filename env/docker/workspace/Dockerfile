ARG PHP_VERSION=${PHP_VERSION}

FROM php:${PHP_VERSION}-cli-alpine


RUN apk update && apk add \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

ARG COMPOSER_PROCESS_TIMEOUT=300
ENV COMPOSER_PROCESS_TIMEOUT ${COMPOSER_PROCESS_TIMEOUT}

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /app

CMD [ "php" ]
