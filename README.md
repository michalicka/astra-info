# Astra Info

Sample project to download and parse zipper XML using Symfony console

## Installation

```sh
git clone git@github.com:michalicka/astra-info.git
cd astra-info
composer install
cp .env.example .env
```

Edit `.env` and configure `URL` of zipped XML file.

## Usage

Run `./console` script and select action

## Tests

Run `./vendor/bin/phpunit tests`

## Docker

Run:
```sh
docker-compose up -d --build
docker exec -it astra php ./console
docker-compose down
```
