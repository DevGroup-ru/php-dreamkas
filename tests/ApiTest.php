<?php

namespace DevGroup\Dreamkas\tests;

use DevGroup\Dreamkas\Api;
use DevGroup\Dreamkas\CustomerAttributes;
use DevGroup\Dreamkas\exceptions\ValidationException;
use DevGroup\Dreamkas\Payment;
use DevGroup\Dreamkas\Position;
use DevGroup\Dreamkas\Receipt;
use DevGroup\Dreamkas\TaxMode;
use Guzzle\Http\Exception\RequestException;


/**
 * Class ApiTest
 */
class ApiTest extends \PHPUnit_Framework_TestCase
{

    public function testJson()
    {
        $api = new Api('FAKE', 123, Api::MODE_MOCK);
        $result = $api->json('GET', 'products');

        $this->assertSame('b0381fe4-4428-4dcb-8169-c8bbcab59626', $result[0]['id']);
    }

    public function testPostReceipt()
    {

        $api = new Api('FAKE', 123, Api::MODE_MOCK);

        $receipt = new Receipt();
        $receipt->taxMode = TaxMode::MODE_SIMPLE;
        $receipt->positions[] = new Position(array(
            'name' => 'Билет - тест',
            'quantity' => 2,
            'price' => 210000,
        ));
        $receipt->payments[] = new Payment(array(
            'sum' => 420000,
        ));
        $receipt->attributes = new CustomerAttributes(array(
            'email' => 'info@devgroup.ru',
        ));

        $receipt->calculateSum();

        $this->assertSame(420000, $receipt->total['priceSum']);


        $response = array();
        try {
            $response = $api->postReceipt($receipt);
        } catch (ValidationException $e) {
            $this->assertFalse(true, 'Validation exception: ' . $e->getMessage());
        } catch (RequestException $e) {

            $this->assertFalse(true, 'Client exception');
        }
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('createdAt', $response);

//
        $receipt->type = Receipt::TYPE_REFUND;
        $response = array();
        try {
            $response = $api->postReceipt($receipt);
        } catch (ValidationException $e) {
            $this->assertFalse(true, 'Validation exception: ' . $e->getMessage());
        } catch (RequestException $e) {
            $this->assertFalse(true, 'Client exception');
        }
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('createdAt', $response);

    }


}
