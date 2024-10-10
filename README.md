<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Getting Started

## Prerequisites

Make sure you have the following installed on your machine:

    PHP >= 8.x
    Composer
    PostgreSQL
    Redis
    Postman (for API testing)
    Installation Steps

Clone the repository:

    git clone https://github.com/your-username/your-repo-name.git

Navigate to the project directory:

    cd your-repo-name

Install PHP dependencies:

    composer install

Set up your .env file:
Duplicate the .env.example file and rename it to .env

Update the PostgreSQL database configuration and Redis settings as needed. For example:

    dotenv

    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    CACHE_DRIVER=redis
    QUEUE_CONNECTION=redis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

Migrate the database:

    php artisan migrate

Seed the database:

    php artisan db:seed

Start the development server:

    php artisan serve

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
