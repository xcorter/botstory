# botstory

## Запуск
1. Зайди в папку docker
2. Выполни  docker-compose up. Должны подняться 3 сервиса: php-fpm, nginx, mysql.

С mysql создастся пользователь botstory с паролем password и база данных botstory.

## Как работать
1. В папке docker необходимо выполнить команду `docker-compose exec botstory-php bin/console`. 
Она запустит стандартную консоль симфони. В списке команд можно будет увидеть команду `bot:run`, которая
будет служить основной командой для запуска бота
Запускается она так `docker-compose exec botstory-php bin/console bot:run`

2. Для запуска миграций инеобходимо выполнить `docker-compose exec botstory-php bin/console doctrine:migrations:migrate`

