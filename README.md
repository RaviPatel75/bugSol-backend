## Laravel Canban Board

Canban Board using laravel.

<b>Clone and follow the below steps</b>

 1) Change file name .env.example to .env and configure it as needed.
 2) Update Composer (for vendor folder)<br> <code>composer update</code>
 3) Generate Key for Laravel Application<br><code>php artsian key:generate</code>
 4) Install node dependencies<br><code>npm install</code>
 5) Compile assets<br><code>npm run dev</code>
 6) Below command will create migration needed, but make sure the database configured in .env file is empty<br><code>php artsian migrate</code>
 7) Add the seeders using the below code <br><code>php artsian db:seed</code>
 8) Start the server<br><code>php artisan serve</code>

Now you are ready to go.
