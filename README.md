# php-dreamkas
Фискализация чека для Дримкас-Ф для PHP 5.3

## Установка

```
composer require devgroup/php-dreamkas:0.0.1
```

В коде, где будет использоваться этот модуль надо обязательно подключить автозагрузчик composer:

```php
<?php
include_once 'vendor/autoload.php';
```

## Пример кода

```php
<?php

// обязательно импортируем пространства имён

use DevGroup\Dreamkas\Api;
use DevGroup\Dreamkas\CustomerAttributes;
use DevGroup\Dreamkas\exceptions\ValidationException;
use DevGroup\Dreamkas\Payment;
use DevGroup\Dreamkas\PaymentType;
use DevGroup\Dreamkas\Position;
use DevGroup\Dreamkas\Receipt;
use DevGroup\Dreamkas\TaxMode;
use Guzzle\Http\Exception\RequestException;

/***
 * 123 - ID кассы
 * MODE_MOCK - режим, может быть MODE_MOCK, MODE_PRODUCTION, MODE_MODE_DEBUG
 */
$api = new Api('ACCESS_TOKEN из профиля', 123, Api::MODE_MOCK);

$receipt = new Receipt();
// режим налогообложения, см. файл src/TaxMode.php
$receipt->taxMode = TaxMode::MODE_SIMPLE;
// для каждой позиции в чеке пишем название, количество и цену за штуку
$receipt->positions[] = new Position(array(
    'name' => 'Билет - тест',
    'quantity' => 2,
    'price' => 210000, // цена в копейках за 1 шт. или 1 грамм
));
// обязательно добавляем оплату
$receipt->payments[] = new Payment(array(
    'sum' => 420000, // стоимость оплаты по чеку
    'type' => PaymentType::TYPE_CASHLESS, // оплата безналом, для оплаты налом TYPE_CASH
));
$receipt->attributes = new CustomerAttributes(array(
    'email' => 'info@devgroup.ru', // почта покупателя
    'phone' => '74996776566', // телефон покупателя
));

// Можно посчитать сумму автоматом
// $receipt->calculateSum();
// А можно завалидировать чек
// $receipt->validate();


$response = array();
try {
    $response = $api->postReceipt($receipt);
} catch (ValidationException $e) {
    // Это исключение кидается, когда информация в чеке не правильная
} catch (RequestException $e) {
    // Это исключение кидается, когда при передачи чека в Дрикас произошла ошибка. Лучше отправить чек ещё раз
    // Если будут дубли - потом отменяйте через $receipt->type = Receipt::TYPE_REFUND;
}

```

Made by DevGroup.ru - [Создание интернет-магазинов](https://devgroup.ru/services/internet-magazin).
