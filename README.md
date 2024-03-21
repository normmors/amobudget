# Тестовое задание

Интеграция для подсчёта бюджета в AmoCRM

## Установка проекта

Установить локально:

1. Запускаем докер

```
docker-compose up -d
```

2. Заходим в докер из под bash, как запустится

```
docker exec -it apicrm_php bash
```

3. Устанавливаем composer

```
composer install
```

4. Копируем .env.example в .env

5. Запускаем миграции

```
php artisan migrate
```

## Запуск проекта

1. Получение access токена и refrsh токена

```
php artisan app:amo-crm (20-минутный код интеграции)
```
Интеграция получит токены и подсчитвает бюджет

2. Запуск интеграции, если access и refresh токены уже были получены 
```
php artisan app:amo-crm
```