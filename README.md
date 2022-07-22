## Платежный сервис (Payment-relay)
<p>Функции сервиса</p>
<ul>
 <li>Создавать счета на оплату товаров в базе данных и выдавать ссылку на форму оплаты по банковской карте или QIWI кошельком.</li>
 <li>Отменять выставленные счета через QIWI и фиксировать данный факт в базе данных.</li>
 <li>Отслеживать статус выставленных счетов на покупки товаров и изменять статус счетов покупок в базе данных.</li>
</ul>

### Запуск проекта
<p style="color: white"><span style="color: #f5f271">Внимание!</span> Для работы проекта нужен предустановленный docker и docker-compose на вашем ПК. Ссылка на документацию по установке <a href="https://docs.docker.com/engine/install/">здесь</a>. Все ниже перечисленные команды выполняются при первом клонировании проекта! Если вы все это уже выполняли, то достаточно вызвать в корне проекта две команды:</p>

 > docker-compose up -d --build

 > ./init-app.sh

<ol>
  <li>Раскоментируйте сервисы "db", "redis" и хранилище "volumes" в файле docker-compose.yml.</li>
  <li>В корне проекта вызовите команду для запуска контейнеров:</li>
  <code style="color: #f5f271"> docker-compose up -d</code>
  <li>Далее, после сборки образов и создания контейнеров убедитесь, что все контейнеры имеют состояние (state) равное "Up" или "Running":</li>
  <code style="color: #f5f271">docker-compose ps</code>
  <li>В катологе проекта создайте файл .env с переменными окружения. Содержимое файла возьмите из .env.example. Заполните переменные базы данных и redis своими значениями. Пример: </li>
  <pref>
    ... <br/>
    DB_CONNECTION=pgsql <br/>
    DB_HOST=db <br/>
    DB_PORT=5432 <br/>
    DB_DATABASE=testdb <br/>
    DB_USERNAME=sa <br/>
    DB_PASSWORD=postgres <br/>
    ... <br/>
    REDIS_HOST=redis <br/>
    REDIS_PASSWORD="12qwsa@@" (пароль берется из docker-compose.yml: command: redis-server --requirepass 12qwsa@@) - укажите свой<br/>
    REDIS_PORT=6379 <br/>
  </pref>
  <p>Переменная "QIWI_URL" должна равняться "https://api.qiwi.com/partner/bill/v1/", но с изменением QIWI API может меняться.</p>
  <pref>
  ... <br/>
  QIWI_URL=https://api.qiwi.com/partner/bill/v1/  <br/>
  ... <br/>
  </pref>
  <li>Значения переменных "QIWI_SECRET_KEY" и "QIWI_PUBLIC_KEY" нужно будет получить по <a href="https://qiwi.com/p2p-admin/transfers/api">ссылке</a>. Предварительно, у вас должен быть заведен свой QIWI кошелек.</li>
  <li>В корне проекта вызовите команду генерация ключа для приложения:</li>
  <code style="color: #f5f271">docker exec -it pay-php-fpm bash -c "php artisan key:generate"</code>
  <li>Далее, в корне проекта вызовите по очереди следующие команды:</li>
  <code style="color: #f5f271"> ./init-env.sh (инициализация переменных среды для docker)</code><br/>
  <code style="color: #f5f271"> ./init-app.sh (инициализация приложения)</code><br/>
  <code style="color: #f5f271"> ./fill-db.sh (заполнение базы данных начальными данными)</code><br/>
  <code style="color: #f5f271"> ./start-test.sh (запуск тестов и проверка, что проект работает)</code><br/>
</ol>

### Работа с сервисом
<p>В корне проекта есть каталог "postman". В нем содержится файл с конечными точками для работы с сервисом. Для этого вам предварительно нужно установить на свой ПК <a href="https://www.postman.com/downloads/">Postman</a>. Данный файл вам нужно импортировать в ваш установленный Postman.</p>


