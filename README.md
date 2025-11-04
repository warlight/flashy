# Flashy Home task

## Installation:

- git clone https://github.com/warlight/flashy
- cd flashy
- composer install
- cp .env.example .env
- php artisan key:generate
- npm install

Than needed to configure .env:
- set database connection (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) for existing DB
- set API_KEY (some string you will use for link creation)

Run migrations with seeders:
- php artisan migrate --seed

Start application (all commands should be running):
- npm run dev
- php artisan serve
- php artisan queue:listen

Usually it starts on address 127.0.0.1:8000 -> so you need to open it in browser, to be able to login into dashboard

## Testing (PHPUnit)
- cp .env .env.testing

Set in this new file settings for new DB schema (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

- php artisan test

## curl Examples:

1. Create link:
`curl --location 'http://127.0.0.1:8000/api/links' \
      --header 'X-Service-Key: YOUR_KEY_FOR_TENANT' \
      --header 'Accept: application/json' \
      --header 'Content-Type: application/x-www-form-urlencoded' \
      --data-urlencode 'target_url=https://laravel.com' \
      --data-urlencode 'slug=laravel'`
response is JSON with field link (the value is short link)

2. Redirect endpoint:
`curl --location 'http://127.0.0.1:8000/r/laravel'`
redirection on https://laravel.com

3. Statistics:
   `curl --location 'http://127.0.0.1:8000/api/links/laravel/stats'`

