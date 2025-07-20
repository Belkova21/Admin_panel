# Laravel Admin Panel with Filament

Tento projekt používa **Laravel**, **Filament**, **Spatie Media Library** a je nakonfigurovaný na beh v **Docker kontajneri** s **SQLite databázou**.

## Lokálne Spustenie projektu

1. Inštalácia závislostí
```bash
composer install
npm install
```

2. Vytvor súbor ```.env```
```bash
cp .env.example .env
php artisan key:generate
```

3. Vytvor SQLite databázu a spusti migrácie
```bash
touch database/database.sqlite
php artisan migrate
```

4. Vytvor Filament administrátora
```bash
php artisan make:filament-user
```

5. Spusti server
```bash
php artisan serve
```

## Spustenie s Dockerom

1. Spusti Docker kontajner
```bash
docker-compose up -d --build
```

2. Vytvor Filament administrátora
```bash
docker-compose exec <nazov_kontajnera> php artisan make:filament-user
```
