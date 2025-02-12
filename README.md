# Wine
## Стек 
- Inertia
- Vue 3
- Laravel

## Larastan дял проврки ошибок

`./vendor/bin/phpstan analyse --memory-limit=2G`

## Импорт из csv

- Сгененрован файл на 500 записей и на 3000 записей в формате csv с помощью gpt (дежат в корне проекта)
- Было добавлено разбиение на чанки для больших файлов
- Находится в дашбоард

## Crud

простой Crud, не сделана валидация для фронта в формах создания и редактирвоания (todo)

## Docker (in work)

-  build `docker-compose up -d --build`
-  down `docker-compose down`
-  run migration `docker-compose exec app php artisan migrate --force`
-  host http://localhost:8000