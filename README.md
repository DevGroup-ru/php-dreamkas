# php-dreamkas
Фискализация чека для Дримкас-Ф для PHP 7.0

Для более старых версий PHP придётся править код на предмет типов у функций.

## Установка

```
composer require studservis/php-dreamkas
```

## Пример кода

```php
<?php
use StudServise\Dreamkas\Api;
use StudServise\Dreamkas\CustomerAttributes;
use StudServise\Dreamkas\exceptions\ValidationException;
use StudServise\Dreamkas\Payment;
use StudServise\Dreamkas\Position;
use StudServise\Dreamkas\Receipt;
use StudServise\Dreamkas\TaxMode;
use GuzzleHttp\Exception\ClientException;

/***
 * 123 - ID кассы
 * MODE_MOCK - режим, может быть MODE_MOCK, MODE_PRODUCTION, MODE_MODE_DEBUG
 */
$api = new Api('ACCESS_TOKEN из профиля', 123, Api::MODE_MOCK);

$receipt = new Receipt();
$receipt->taxMode = TaxMode::MODE_SIMPLE;
$receipt->positions[] = new Position([
    'name' => 'Билет - тест',
    'quantity' => 2,
    'price' => 210000, // цена в копейках за 1 шт. или 1 грамм
]);
$receipt->payments[] = new Payment([
    'sum' => 420000, // стоимость оплаты по чеку
]);
$receipt->attributes = new CustomerAttributes([
    'email' => 'info@devgroup.ru', // почта покупателя
    'phone' => '74996776566', // телефон покупателя
]);

// Можно посчитать сумму автоматом
// $receipt->calculateSum();
// А можно завалидировать чек
// $receipt->validate();


$response = [];
try {
    $response = $api->postReceipt($receipt);
} catch (ValidationException $e) {
    // Это исключение кидается, когда информация в чеке не правильная
} catch (ClientException $e) {
    echo $e->getResponse()->getBody();
    // Это исключение кидается, когда при передачи чека в Дрикас произошла ошибка. Лучше отправить чек ещё раз
    // Если будут дубли - потом отменяйте через $receipt->type = Receipt::TYPE_REFUND;
}

```

Made by DevGroup.ru - [Создание интернет-магазинов](https://devgroup.ru/services/internet-magazin).
