# Laravel Task Management API

Этот проект представляет собой REST API, созданный на основе фреймворка Laravel, который позволяет пользователям создавать, редактировать, удалять и просматривать задачи.

## **Требования**

Перед запуском проекта убедитесь, что у вас установлены следующие зависимости:

-   **Docker** (Docker Engine и Docker Compose должны быть установлены)
-   **Git** (для клонирования репозитория)

## **Запуск проекта**

1. **Клонирование репозитория:**

    ```sh
    git clone https://github.com/AlijonTashtanov/task_manager.git
    cd task_manager
    ```

2. **Настройка файла .env:**

    ```sh
    cp .env.example .env
    ```

    В файле `.env` измените необходимые параметры (подключение к базе данных, конфигурация приложения и т. д.).

3. **Запуск контейнеров Docker:**

    ```sh
    docker-compose up -d --build
    ```

4. **Установка пакетов Composer:**

    ```sh
    docker-compose exec app composer install
    ```

5. **Запуск миграций и заполнение базы данных:**

    ```sh
    docker-compose exec app php artisan migrate --seed
    ```

6. **Генерация API-ключа:**

    ```sh
    docker-compose exec app php artisan key:generate
    ```

7. **Генерация документации Swagger:**
    ```sh
    docker-compose exec app php artisan l5-swagger:generate
    ```

## **API Эндпоинты**

API поддерживает следующие маршруты:

-   **POST /api/tasks** – Создать новую задачу
-   **GET /api/tasks** – Получить список задач
-   **GET /api/tasks/{id}** – Получить конкретную задачу
-   **PUT /api/tasks/{id}** – Обновить задачу
-   **DELETE /api/tasks/{id}** – Удалить задачу

## **Документация Swagger**

Чтобы открыть документацию API в браузере, перейдите по следующему адресу:

```
http://localhost/api/documentation
```

## **Остановка контейнеров Docker**

Чтобы остановить сервисы, выполните команду:

```sh
docker-compose down
```

## **Запуск тестов**

Для запуска тестов Laravel используйте следующую команду:

```sh
docker-compose exec app php artisan test
```
