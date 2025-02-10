## Выполнение тестового задания для Микрокредитная Компания Небус

Ссылка на описание функциональных требований тестового задания: [docs.google.com](https://docs.google.com/document/d/1OhQ6cdah4E-fbvkgI6FE5FnWfOUHYluyN9uxpjJ_fX8/edit?usp=sharing)

# Установка

### Копирование файла `.env.example` в `.env`
```sh
cp .env.example .env
```

### Установка зависимостей через Composer
```sh
composer install
```

### Генерация ключа приложения
```sh
php artisan key:generate
```

### Запуск контейнеров Docker (используется `docker-compose.dev.yml`)
```sh
docker-compose up -d
```

### Создание схемы базы данных и наполнение тестовыми данными
```sh
docker exec -i laravel_dev php artisan migrate --seed
```

### Генерация API документации
```sh
docker exec laravel_dev php artisan l5-swagger:generate
```
