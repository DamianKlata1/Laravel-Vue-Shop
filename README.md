# About


# Installation
1. Clone the repository:

```bash
git clone https://github.com/DamianKlata1/Laravel-Vue-Shop.git
```
2. Copy the .env.example file to .env and fill in the necessary data


3. Go to the docker directory:

```bash
cd Laravel-Vue-Shop/.docker
```
4. Build and start docker containers:

```bash
docker-compose up -d --build
```
5. Go into the app container:

```bash
docker-compose exec -it docker-php-1 bash
```
6. Install dependencies:

```bash
composer install
npm install 
```
7. Generate application key:

```bash
php artisan key:generate
```
8. Run migrations:

```bash
php artisan migrate
```
9. Seed the database:

```bash
php artisan db:seed
```
10. Compile assets:

```bash
npm run dev
```
11. Open the browser and go to the address:

```bash
http://localhost:80
```
12. You can log in as an administrator using the following data:

```bash
email:admin@wp.pl
password:password
```


