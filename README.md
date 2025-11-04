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

## Testing
- cp .env .env.testing

Set in this new file settings for new DB schema (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

- php artisan test
