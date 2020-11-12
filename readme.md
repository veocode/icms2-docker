

# icms2-docker
Тулкит для запуска InstantCMS 2 в Docker-контейнере.

 - [Комплектация](#equipment)
 - [Требования](#reqs)
 - [Установка и запуск](#init)
   - [Размещение чистой InstantCMS 2](#init.scratch)
   - [Размещение готового сайта по SSH/SFTP](#init.ssh)
   - [Размещение готового сайта из GIT-репозитория](#init.git)
 - [Сброс и отмена установки](#clear)   
 - [Установка InstantCMS](#install)
 - [Остановка контейнеров](#stop)
 - [Настройка окружения](#config)
 - [Доступ к файлам](#files)


## Комплектация<a name="equipment">

 - PHP 7.2.34
	 - Memcached
	 - Zend OPcache
	 - IonCube Loader
 - Apache 2.4.38
	 - mod_rewrite
 - Mysql 8
 - PhpMyAdmin 5.0.4 *(опционально)*

Всё окружение настроено специально под InstantCMS.

## Требования<a name="reqs">

- bash
- docker
- docker-compose
- git

## Установка и запуск<a name="init">

Склонируйте репозиторий и перейдите в полученную папку:
```bash
$ git clone https://github.com/veocode/icms2-docker.git
$ cd icms2-docker
```
После этого необходимо запустить мастер установки `init.sh`, способ запуска которого будет зависеть от того, какую установку вы хотите получить:

### 1. Размещение чистой InstantCMS 2<a name="init.scratch">
Если вы хотите создать новую чистую установку InstantCMS 2, то запустите мастер без параметров:
```bash
$ ./init.sh
```
Описание вопросов, которые задаст мастер, см. ниже.

### 2. Размещение готового сайта по SSH/SFTP<a name="init.ssh">
Если вы хотите опубликовать готовый сайт на InstantCMS 2, то сделайте следующее:

 1. Загрузите файлы сайта в папку `icms2`;
 2. Дамп базы данных сайта в виде SQL-файла с любым названием и расширением `.sql` положите в папку `mysql/dump`. Внутри дампа должны создаваться только таблицы, сама база будет создана автоматически. Подойдет дамп из phpMyAdmin с настройками по-умолчанию.
 3. В конфиге сайта в файле `icms2/system/config/config.php` пропишите реквизиты базы данных (название, пользователь, пароль), которые вы планируете использовать. Эти же реквизиты нужно будет сообщить мастеру установки на следующем шаге. Реквизиты можно выдумать, они никак не связаны с хост-машиной. **Важно:** в качестве хоста базы данных укажите `mysql` вместо `localhost`.

После этого запустите мастер установки с параметром `deploy`:
```bash
$ ./init.sh deploy
```
### 3. Размещение готового сайта из GIT-репозитория<a name="init.git">
Если вы хотите опубликовать готовый сайт на InstantCMS 2, исходники которого размещены в репозитории, то используйте такой запуск мастера установки:
```bash
$ ./init.sh deploy <REPO_URL>
```
Например:
```bash
$ ./init.sh deploy https://github.com/user/repo.git
```
Мастер выполнит следующие действия:

1. Загрузит ваш сайт из указанного репозитория в папку `icms2`;
2. Проверит существование файла `icms2/config/config.prod.php` и если он существует, заменит им оригинальный конфиг сайта. Таким образом, вы можете хранить в репозитории отдельный конфиг с настройками для продакшена;
3. Перенесет все файлы `icms2/*.sql` в папку `mysql/dump`, чтобы загрузить их в базу при её создании. Таким образом, вы можете хранить дамп базы вашего сайта прямо в корне репозитория.

### Вопросы мастера установки
Мастер установки спросит у вас значения следующих параметров:
| Параметр | По-умолчанию | Описание | 
|--|--|--|
| InstantCMS version to install | 2.13.1 | Версия InstantCMS для установки. Полный список всех версий можно посмотреть в [официальном репозитории](https://github.com/instantsoft/icms2/tags). Этот вопрос не будет задан, если мастер запущен с параметром `deploy` |
| Web-server Port | 80 | Порт, на котором будет доступен веб-сервер |
| MySQL Database | icmsdb | Название базы данных (будет создана автоматически). Этот и остальные параметры MySQL должны быть придуманы вами - они нигде заранее не прописаны и никак не связаны с хост-системой |
| MySQL User | icmsdb | Пользователь базы данных | 
| MySQL User Password | secret | Пароль пользователя базы данных |
| MySQL Root Password | rootsecret | Пароль root-пользователя базы данных |
| Install phpMyAdmin? (y/n) | y | Нужно ли устанавливать phpMyAdmin? Ответ `y` или `n` |
| phpMyAdmin Port | 8001 | Если в предыдущем вопросе вы ответили `y`, то нужно будет указать, на каком порту будет работать phpMyAdmin |

После ответа на вопросы установщик загрузит требуемую версию InstantCMS из официального репозитория, настроит, создаст и запустит необходимые контейнеры. 

После запуска контейнеров ваш сайт будет доступен по адресу: `http://<SERVER-IP>:<PORT>`, где **SERVER-IP** - адрес текущего сервера, **PORT** - порт веб-сервера, указанный в мастере установки.

Если вы размещали готовый сайт, то на этом установка закончена и всё должно работать. 

Если вы проводите новую чистую установку, то далее вам необходимо установить саму InstantCMS. Подробности см. ниже.

## Сброс и отмена установки<a name="clear">
Вы можете сбросить установку в случае, если вы передумали или что-то пошло не так. Работу мастера установки в любой момент можно прервать нажатием `Ctrl`+`C`. Чтобы удалить все загруженные файлы и вернуть `icms2-docker` в исходное чистое состояние используйте команду:
```bash
$ ./init.sh clear
```
После этого установку можно начать заново.

## Установка InstantCMS<a name="install">

Этот шаг требуется только в случае, когда вы размещаете новую чистую InstantCMS 2.

Перейдите по адресу `http://<SERVER-IP>:<PORT>/install` чтобы запустить установку InstantCMS. Установка проводится по стандартной инструкции, за исключением трех моментов:

### 1. Параметры базы данных
В качестве адреса MySQL-сервера укажите `mysql` вместо стандартного `localhost`. Пользователя, пароль и название базы указывайте в том виде, в котором вы указали их при запуске контейнеров. Для InstantCMS 2.14 и выше данные поля в инсталляторе будут заполнены автоматически.

### 2. Планировщик
Задание планировщика необходимо создать в хост-системе, то есть прямо на том сервере, где вы развернули докер. Команда для задания будет выглядеть так:
```bash
docker exec -t icms2-docker_icms_1 php /var/www/html/cron.php
```

### 3. Выключение sql_mode
Если вы используете InstantCMS версии ниже, чем 2.14, то после установки необходимо зайти в Панель управления, раздел "Настройки", вкладка "База данных" и активировать опцию `Включить режим пустого sql_mode для MySQL`.


## Остановка контейнеров<a name="stop">
Для остановки перейдите в папку `icms2-docker` и выполните команду:
```bash
$ docker-compose down
```

## Настройка окружения<a name="config">
### PHP
Конфигурацию PHP можно изменить в файле `php/php.ini`. После внесения изменений необходимо перезапустить контейнеры:
```bash
$ docker-compose down && docker-compose up -d
```
### MySQL
Дополнительные конфиги MySQL можно добавить в папку `mysql/conf`

Файлы баз данных хранятся в папке `mysql/db`

Папка `mysql/dump` предназначена для импорта готовых SQL-дампов. Положите в эту папку файл с расширением `.sql` и его содержимое будет автоматически залито в базу данных в момент её первого создания.

## Доступ к файлам<a name="files">
Все файлы InstantCMS размещаются в папке `icms2` и доступны для редактирования. Перезапуск контейнеров после редактирования этих файлов не требуется.
