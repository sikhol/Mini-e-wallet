# Mini-e-wallet
# FrameWork Laravel
DBMS: Mysql: 

step :
- git clone 
- composer install
- cp .env.example .env
- kemudian masukan nama db username dan password di envnya
- kemudian php artisan key:generate
- kemudian php artisan migrate
- kemudian composer dump-autoload
- kemudian php artisan db:seed --class=UsersTableSeeder
- kemudian php artisan db:seed --class=BlanceBankTableSeeder
- untuk menjalankan proyek dengan perintah php artisan serve
- link collection postman https://www.getpostman.com/collections/1da8b7bce5e8220c3a4b




