
Application description 


Technical Requirement:

- PHP >= 8.0.10
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension


## How to run

```bash

# Clone the repository and go to the cloned directory

# install Composer dependencies/packages
$ composer install


# Migrate and run seeding (do this every any update on the migration and the seeder)
$ php artisan migrate
$ php artisan db:seed 

#if you want to run in development use this:
$ php artisan serve

#if you want to run in production just set up on the web server or container(docker)

```
