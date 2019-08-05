#Ukrposhta
**Ukrposhta** - это библиотека для работы с API УкрПошты, далее УП. В данном проекте реализована работа с:
- **Справочники** -  список адресов в которых работают отделения УП, а именно области, регионы, города, отделения и улицы.  
- **Клиенты** - работа с контрагентами(создание, редактирование). Клиенты используются при создании отправок.
- **Адреса** - работа с адресами клинта(создание, редактирование). Адреса привязываются к клиентам и используются при создании отправок.
- **Отправки** - работа с почтовыми отправлениями(создание, редактирование, отслеживание статуса).
- **Формы** - получение формы отправки(накладной) в формате *pdf*.
- **Документацией** - получение последней актуальной документации по API.
#Установка
###git
```bash
git clone https://github.com/kex15i10/ukrposhta
```
###composer
```bash
composer require kex15i10/ukrposhta
```
#Использование
###Конфигурация
Каждый класс принимает в качестве аргумента конструктора объект-конфигурацию: Ukrposhta\Data\Configuration. В данном классе устанавливаем token и bearer при помощи соответствующих методов-сеттеров
```php
$config = new Ukrposhta\Data\Configuration();
$config->setBearer('string bearer'); 
$config->setToken('string token');
``` 
Так же в данном классе можно добавить заголовки для запросов при помощи соответствующего метода
```php
$config->addHeaders([
    'Content-Type' => 'application/json'
]);
```
###Общая Документация
####API
Актуальную документацию по параметрам API и возвращаемым полям можно получить на официальном [сайте](https://ukrposhta.ua/api-ukrposhta-ekspres/) или воспольоваться классом данной библитеки
```php
$config = new Ukrposhta\Data\Configuration();
$config->setBearer('string bearer'); 
$doc = new Ukrposhta\Doc($config);
$doc->save('./);
```
Метод **save** принимает два аргумента. Первый является обязательным - это путь для сохранения файла, второй, не обязательный, это имя сохраняемого файла, по-умолчанию "documentation.pdf".
####Возвращаемые данные, Исключения(Exceptions)
В случае когда API должен вернуть данные(пример при работе со справочниками, с.м. документацию к API) - будет возвращен массив данных. Когда происходит ошибка, будь то серверная либо ошибка API - библиотека бросит исключение (Exception) в соответствии с поведением библиотеки для HTTP запросов GuzzleHttp\Client.
####Параметры запросов
В случае, когда необходимо передавать параметры в запросе, метод соответствующего класса в качестве параметра принимает объект класса Ukrposhta\Data\Storage. Конструктор данного класса(Storage) принимает необзательный аргумент в ввиде массива с параметрами в формате ключ-значение. Указать данные можно несколькоми способами:
```php
$params = [
    'firstName' => 'Test_First_Name',
    'lastName' => 'Test_Last_Name'
    'middleName' => 'Test_Middle_Name'
];
$storage = new Ukrposhta\Data\Storage($params);
$storage->addData($storage);
$storage->setData('lastName', Test2_Last_Name);
$storage->firstName = 'Test2_First_Name';
```
###Работа со справочниками
Работа со справочниками представлена следующими классами
* Ukrposhta\Directory\City
* Ukrposhta\Directory\District
* Ukrposhta\Directory\Postoffice
* Ukrposhta\Directory\Region
* Ukrposhta\Directory\Street

Для получения списка городов, областей, регионов и улиц есть метод **getList** для соответствующих классов
```php
$config = new Ukrposhta\Data\Configuration();
$config->setBearer('string bearer'); 
$config->setToken('string token');
```
```php
$cities = new Ukrposhta\Directory\City($config)->getList();
$districts = Ukrposhta\Directory\District($config)->getList();
$regions = Ukrposhta\Directory\Region($config)->getList();
$streets = Ukrposhta\Directory\Street($config)->getList();
```
Для получения списка городов с фильтром по региону
```php
$storage = new Ukrposhta\Data\Storage();
$storage->region_id = 1;
$cities = new Ukrposhta\Directory\City($config)->getList($storage);
```
Получение списка отеделений по id города
```php
$storage = new Ukrposhta\Data\Storage();
$postoffices = new Ukrposhta\Directory\Postoffice($config)->getByCityId(1);
```
Получение отделения по почтовому индексу
```php
$storage = new Ukrposhta\Data\Storage();
$postoffices = new Ukrposhta\Directory\Postoffice($config)->getByPostIndex(72370);
```
###Адреса, Контрагенты, Отправления
Работа с данными моделями реализована в следующих классах
* Ukrposhta\Address
* Ukrposhta\Client
* Ukrposhta\Shipment

Для создания адреса контрагента, самого контрагента и отправления необходимо воспользоваться методом **save** соответсвующего класса. Данный метод первым аргументом принимает объект класса Ukrposhta\Data\Storage с параметрами запроса.\
Пример создания контрагента
```php
$config = new Ukrposhta\Data\Configuration();
$config->setBearer('string bearer'); 
$config->setToken('string token');
$params = [
    'firstName' => 'Марк'
    'lastName' => 'Зотов'
    'middleName' => 'Олегович'
    'addressId' => 1250990
    'type' => 'INDIVIDUAL'
];
$storage = new Ukrposhta\Data\Storage($params);
$client = new Ukrposhta\Client($config);
$client->save($storage);
```
Для обновления информации о контрагенте необходимо в методе **save** вторым аргументом указать UUID контрагента
```php
$client->save($storage, 'string client UUID');
```
Для получения информации о клиенте необходимо воспользоваться методом **get**
```php
$client->get('customer id');
```
Данный метод принимает первым аргументом идентифкатор клиента, по-умолчанию это UUID контрагента. Если указать второй параметр true, то в этом случае первый аргумент будет выступать как external-id
###Печать накладных
Для работы с накладным есть класс Ukrposhta\Form с методом **saveSticker**
```php
$config = new Ukrposhta\Data\Configuration();
$config->setBearer('string bearer'); 
$config->setToken('string token');
$form = new Ukrposhta\Form($config);
$form->saveSticker('shipment uuid or barcode', './path/to/save');
```
Данный метод принимает два обязательных аргумента, первый - идентификатор отправления, второй - путь сохранения файла. Третий аргумент - это название сохраняемого файла, по-умолчанию "sticker.pdf". Четверый - список параметров передваемых в запросе в виде объекта класса Ukrposhta\Data\Storage.
