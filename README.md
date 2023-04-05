## Simple Laravel 10 REST API

Simple Laravel 10 REST API with Sanctum Registration/Authentication, Products + Categories.

Install:
- Run Docker
- ./vendor/bin/sail up

From Docker container:
- composer install
- php artisan key:generate
- php artisan migrate
- php artisan queue:work (For queues)

API Structure:
CRUD[GET, POST, PUT/PATCH, DELETE] for Categories and Products
[POST] Login / Register 

Best regards.