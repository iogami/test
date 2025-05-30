# 🔗 URL Shortener API (Laravel 12)

RESTful API для сокращения ссылок.

Сохраняет оригинальную ссылку, генерирует уникальный 6-символьный код, возвращает сокращённый URL вида http://localhost/abcd1e.

---

## Запуск проекта

1. Склонировать проект

```bash
  git clone https://github.com/iogami/test.git
```

2. В папке `laravel-api`. Переименвать файл .env.example в .env

```bash
  cp laravel-api/.env.example laravel-api/.env
```

3. Запустите контейнеры (в папке, где находиться `docker-compose.yml`)

```bash
  docker compose up -d --build
```

4. Изменить права в папках для cache

```bash
  docker compose exec app bash -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
```


5. Установите зависимости Laravel

```bash
  docker compose exec app composer install
```

6. Создайте ключ приложения

```bash
  docker compose exec app php artisan key:generate
```

7. Выполните миграции

```bash
  docker compose exec app php artisan migrate
```

---

Приложение будет доступно по адресу:

http://localhost

Чтобы Изменить localhost в `nginx/default.conf`

Чтобы изменить доменное имя (например, на `myproject.local`), измените строку:

```bash
  server_name localhost;
```

на:

```bash
  server_name myproject.local;
```

Затем пропишите этот домен в `/etc/hosts`:

```bash
  127.0.0.1 myproject.local
```

---

## API Endpoint: POST `/api/short-links`

Создаёт короткую ссылку на основе переданного URL.  
Возвращает сгенерированный уникальный код, сокращённую ссылку и дату создания.

### Заголовки

```http
Accept: application/json
Content-Type: application/json
```

### Тело запроса

```json
{
    "url": "https://example.com/some-long-page"
}
```

### Пример успешного ответа

```json
{
    "message": "Short link created successfully.",
    "data": {
        "original_url": "https://example.com/some-long-page",
        "short_url": "http://localhost/Ab12Cd",
        "code": "Ab12Cd"
    }
}
```

|Поле   |Описание   |
| ------------ | --------------------------- |
| `short_url`  | Сокращённая ссылка с кодом  |
| `code`       | Уникальный 6-символьный код |
| `created_at` | Дата и время создания       |

### Пример ошибки валидации

Запрос:

```json
{
    "url": "not-a-valid-url"
}
```

Ответ:

```json
{
    "message": "Validation error.",
        "errors": {
        "url": [
            "The url field must be a valid URL."
        ]
    }
}
```

### Ограничение по количеству запросов

Лимит: 120 запросов в минуту на IP

При превышении: ответ `429 Too Many Requests`

## API Endpoint: GET `/api/short-links/{code}/stats`

Получить статистику по сокращённой ссылке.
Этот эндпоинт требует авторизацию с использованием Bearer токена.

Добавьте заголовок:
```http
Authorization: Bearer <ваш_токен>
```

### Параметры пути
code (string) - Уникальный 6-символьный код ссылки

### Пример успешного ответа

```json
{
    "data": {
        "code": "Ab12Cd",
        "original_url": "https://example.com/some-page",
        "created_at": "2025-05-30T10:12:34.000000Z",
        "visit_count": 42
    }
}
```

### Примечания

Эндпоинт предназначен для внутренних пользователей/администраторов.

Чтобы получить токен, используйте POST /api/get-token.

## API Endpoint: POST `/api/get-token`

Генерирует Bearer токен для доступа к защищённым эндпоинтам API.
Эндпоинт не требует авторизации и возвращает токен, связанный с системным пользователем.

### Заголовки

```http
Accept: application/json
```

(тело запроса не требуется)

### Пример успешного ответа

```json
{
    "token": "QWErtyUIOP...",
    "token_type": "Bearer"
}
```