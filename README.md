# 🔗 URL Shortener API (Laravel 12)

RESTful API для сокращения ссылок.

Сохраняет оригинальную ссылку, генерирует уникальный 6-символьный код, возвращает сокращённый URL вида http://localhost/abcd1e.

---

## Запуск проекта

```bash
git clone https://github.com/your/repo.git
cd repo
composer install

cp .env.example .env
php artisan key:generate

# Настрой .env с доступом к MySQL
php artisan migrate

php artisan serve
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