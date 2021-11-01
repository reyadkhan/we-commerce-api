### Requirements
- PHP >= 8.0
- Laravel >= 8.6

### Installation
```
    git clone https://github.com/reyadkhan/we-commerce-api.git
    cd we-commerce-api
    composer intall
    cp .env.example .env
    php artisan key:generate
```
_Create a database and put it to **.env** file_
```
php artisan migrate --seed
```
_This will create a default admin user in the database with E-mail: `admin@wecommerce.com` and Pass: `admin123`_
#### Order notification for admin
_To get order notification in admin mail, Please configure email in the **.env** file_
_For task scheduling `(Delivered orders move to deliveries)` in local_
```
php artisan schedule:work
```

***NB:** The api doc has been placed under **_test/post-main_** directory
