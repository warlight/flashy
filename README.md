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

1. Create link:  (laravel is the slug;  (https://laravel.com is the target_url))
`curl --location 'http://127.0.0.1:8000/api/links' \
      --header 'X-Service-Key: YOUR_KEY_FOR_TENANT' \
      --header 'Accept: application/json' \
      --header 'Content-Type: application/x-www-form-urlencoded' \
      --data-urlencode 'target_url=https://laravel.com' \
      --data-urlencode 'slug=laravel'`
response is JSON with field link (the value is short link)

2. Redirect endpoint:   (laravel is the slug)
`curl --location 'http://127.0.0.1:8000/r/laravel'`
redirection on https://laravel.com

3. Disable link: (laravel is the slug)
`curl --location 'http://127.0.0.1:8000/api/links/laravel/disable' \
      --header 'X-Service-Key: YOUR_KEY_FOR_TENANT'` 

4. Statistics: (laravel is the slug)
`curl --location 'http://127.0.0.1:8000/api/links/laravel/stats?expires=1762256686&signature=e58f5ebe6481c9a609901ba428e003c0af20b6ad9f8329ce273a9b4885c7503f'`
the real link you need to take from dashboard (column "stats link")

## Explanation of architecture:

- creation link is POST-request (method POST because we create new item). At response there is a JSON with element link. It has also middleware that checks API_KEY in request
- redirect link works by slug:
  - it takes from DB item and returns it's target_url.
  - To make it fast - there is an asynchronously storing of hit (through the job and queue).
  - Before returning result (redirect) - it checks if link is active and checks if user agent contains any known part of bot's user agents. If it is bot - it gets redirect without storing hit.
  - After storing hit in job - it uses event (to signalize that needs to run listener on it) and clears cache for statistics for current link (by slug)
- disable link is PATCH-request (method PATCH because it is partly changing existing object) works by slug.
- using cache for stats at starting request (only after checking sign) used to make less requests to DB. 
  - also implemented method with (eager loading) for the related model (LinkHit) preventing issue N+1
  - after getting all data for route it goes to cache, where the cache key is link's slug
  - in debug mode this API-request has in response information from debugbar lib (that works through the middleware) regarding SQL-queries and other

## if it should be installed in production - needs to set in .env APP_DEBUG=false and run composer install --no-dev to prevent installing laravel debugbar package


