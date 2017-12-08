<?php

namespace DevGroup\Dreamkas\tests;

use DevGroup\Dreamkas\Api;
use DevGroup\Dreamkas\CustomerAttributes;
use DevGroup\Dreamkas\exceptions\ValidationException;
use DevGroup\Dreamkas\Payment;
use DevGroup\Dreamkas\Position;
use DevGroup\Dreamkas\Receipt;
use DevGroup\Dreamkas\TaxMode;
use GuzzleHttp\Exception\ClientException;


/**
 * Class ApiTest
 */
class ApiTest extends \PHPUnit_Framework_TestCase
{

    public function testJson()
    {
        $api = new Api('FAKE', 123, Api::MODE_MOCK);
        $result = $api->json('GET', 'products');

        $this->assertSame([[]], $result);
    }

    public function testPostReceipt()
    {

        $api = new Api('FAKE', 123, Api::MODE_MOCK);

        $receipt = new Receipt();
        $receipt->taxMode = TaxMode::MODE_SIMPLE;
        $receipt->positions[] = new Position([
            'name' => 'Билет - тест',
            'quantity' => 2,
            'price' => 210000,
        ]);
        $receipt->payments[] = new Payment([
            'sum' => 420000,
        ]);
        $receipt->attributes = new CustomerAttributes([
            'email' => 'info@devgroup.ru',
        ]);

        $receipt->calculateSum();


        $response = [];
        try {
            $response = $api->postReceipt($receipt);
        } catch (ValidationException $e) {
            $this->assertFalse(true, 'Validation exception: ' . $e->getMessage());
        } catch (ClientException $e) {
            echo $e->getResponse()->getBody();
            $this->assertFalse(true, 'Client exception');
        }
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('createdAt', $response);

//
        $receipt->type = Receipt::TYPE_REFUND;
        $response = [];
        try {
            $response = $api->postReceipt($receipt);
        } catch (ValidationException $e) {
            $this->assertFalse(true, 'Validation exception: ' . $e->getMessage());
        } catch (ClientException $e) {
            echo $e->getResponse()->getBody();
            $this->assertFalse(true, 'Client exception');
        }
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('createdAt', $response);

    }


}
