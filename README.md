# exchange_rates
Информационная система курсов валют для просмотра котировок и построения графиков <!-- описание репозитория -->
<!--Блок информации о репозитории в бейджах-->
![Static Badge](https://img.shields.io/badge/popovalnik-exchange_rates-exchange_rates)
![GitHub top language](https://img.shields.io/github/languages/top/popovalnik/exchange_rates)
![GitHub](https://img.shields.io/github/license/popovalnik/exchange_rates)
![GitHub Repo stars](https://img.shields.io/github/stars/popovalnik/exchange_rates)
![GitHub issues](https://img.shields.io/github/issues/popovalnik/exchange_rates)

![Logotype](./docs/logo.png)

<!--Установка-->
## Установка (Linux)
У вас должны быть установлены [зависимости проекта](https://github.com/popovalnik/exchange_rates#Зависимости)

1. Клонирование репозитория 

```git clone https://github.com/popovalnik/exchange_rates.git```

2. Копирование файлов информационной системы в каталог веб-сервера

```cp -r /public /var/www/```

3. Импорт базы данных и пользователя для подключения к базе данных, назначение прав

```mysql -uroot -ppassword``` где password пароль от суперпользователя базы данных, заданных при установке
```source /var/www/public/dump.sql```
```create user 'user'@'localhost' identified by 'password'``` где user - ваш новый логин от пользователя, password - пароль
```grant all privileges on popovalnik_ex . * to 'user'@'localhost'``` где user - логин от созданного выше пользователя
```flush privileges```
```exit```

4. Отредактируйте конфигурационные файлы Virtual Host веб-сервера в каталоге /etc/apache2

5. Отредактируйте конфигурационный файл информационной системы, содержащий подключение к базе данных

```vi /var/www/public/heart/db.php``` где необходимо ввести логин и пароль от пользователя базы данных, созданного в шаге 3

6. Добавление заданий в cron для автоматической загрузки данных в информационную систему

```crontab -e```

Теперь добавьте расписание задания и путь к файлу скрипта:
30 03 * * * php /var/www/public/heart/cron-scripts/download.php >/dev/null 2&>1
00 03 * * 7 php /var/www/public/heart/cron-scripts/download-sp-rates.php >/dev/null 2&>1

<!--Пользовательская документация-->
## Документация
Пользовательскую документацию можно получить по [этой ссылке](./docs/index.md).

## Демонстрация работы
Демо: [перейти на сайт](https://popovalnik.myjino.ru/)

<!--Поддержка-->
## Поддержка
Если у вас возникли сложности или вопросы по использованию пакета, создайте 
[обсуждение](https://github.com/popovalnik/exchange_rates/issues/new/choose) в данном репозитории или напишите на электронную почту <popovalnik@yandex.ru>.

<!--Зависимости-->
## Зависимости
Информационная система построена на базе PHP версии 8.1 или выше. 
Для хранения данных о курсах валют используется движок баз данных MYSQL.
Для работы ифномационной системы на сервере должен быть установлен веб-сервер Apache2 и mod_php.
