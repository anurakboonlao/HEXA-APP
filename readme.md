## Deploy

go to project directory

Run

composer install

cp .env.example .env (and config database)

php artisan key:generate

php artisan migrate

php artisan db:seed

## make dummy data

Run

php artisan db:seed --class="DummyDataSeeder"
