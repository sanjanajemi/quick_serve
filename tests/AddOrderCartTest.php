<?php
use PHPUnit\Framework\TestCase;
use App\Models\OrderModel;

class AddOrderCartTest extends TestCase
{
    private $validCustomerData;

    protected function setUp(): void
    {
        $this->validCustomerData = [
            'name'  => 'ValidName',
            'email' => uniqid().'@example.com',
            'phone' => null
        ];
    }

    protected function tearDown(): void
    {
        $db = \App\Core\Database::connect();
        if ($db->inTransaction()) {
            $db->rollBack();
        }
    }

    private function createOrderWithCart(array $cartItems, float $amount = 100.00)
    {
        $orderData = [
            'comments'     => 'Cart test',
            'final_amount' => $amount,
            'items'        => $cartItems
        ];
        return OrderModel::createWithCustomer($this->validCustomerData, $orderData);
    }

    public function testExtremeMinCart()
    {
        $orderId = $this->createOrderWithCart([]);
        $this->assertIsInt($orderId);
    }

    public function testMinMinusOneCart()
    {
        $orderId = $this->createOrderWithCart([]);
        $this->assertIsInt($orderId);
    }

    public function testMinBoundaryCart()
    {
        $orderId = $this->createOrderWithCart([1 => 1]);
        $this->assertIsInt($orderId);
    }

    public function testMinPlusOneCart()
    {
        $orderId = $this->createOrderWithCart([1 => 1, 2 => 1]);
        $this->assertIsInt($orderId);
    }

    public function testMaxMinusOneCart()
    {
        $items = [];
        for ($i = 1; $i <= 29; $i++) {
            $items[$i] = 1;
        }
        $orderId = $this->createOrderWithCart($items);
        $this->assertIsInt($orderId);
    }

    public function testMaxBoundaryCart()
    {
        $items = [];
        for ($i = 1; $i <= 30; $i++) {
            $items[$i] = 1;
        }
        $orderId = $this->createOrderWithCart($items);
        $this->assertIsInt($orderId);
    }

    public function testMaxPlusOneCart()
    {
        $items = [];
        for ($i = 1; $i <= 31; $i++) {
            $items[$i] = 1;
        }
        $orderId = $this->createOrderWithCart($items);
        $this->assertIsInt($orderId);
    }

    public function testMidCart()
    {
        $items = [];
        for ($i = 1; $i <= 15; $i++) {
            $items[$i] = 1;
        }
        $orderId = $this->createOrderWithCart($items);
        $this->assertIsInt($orderId);
    }

    public function testExtremeMaxCart()
    {
        $items = [];
        for ($i = 1; $i <= 100; $i++) {
            $items[$i] = 1;
        }
        $orderId = $this->createOrderWithCart($items);
        $this->assertIsInt($orderId);
    }

    public function testInvalidDataTypeCart()
    {
        $items = ['corrupted' => 'invalid'];
        $orderId = $this->createOrderWithCart($items);
        $this->assertIsInt($orderId);
    }
}