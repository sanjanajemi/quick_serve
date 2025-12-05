<?php
use PHPUnit\Framework\TestCase;
use App\Models\OrderModel;

class AddOrderCommentTest extends TestCase
{
    private $validCustomerData;
    private $validOrderData;

    protected function setUp(): void
    {
        $this->validCustomerData = [
            'name'  => 'ValidName',
            'email' => uniqid().'@example.com',
            'phone' => null
        ];

        $this->validOrderData = [
            'final_amount' => 100.00,
            'items'        => [1 => 2]
        ];
    }

    protected function tearDown(): void
    {
        $db = \App\Core\Database::connect();
        if ($db->inTransaction()) {
            $db->rollBack();
        }
    }

    private function createOrderWithComment(string $comment)
    {
        $orderData = $this->validOrderData;
        $orderData['comments'] = $comment;
        return OrderModel::createWithCustomer($this->validCustomerData, $orderData);
    }

    public function testExtremeMinComment()
    {
        $orderId = $this->createOrderWithComment('');
        $this->assertIsInt($orderId);
    }

    public function testMinMinusOneComment()
    {
        $orderId = $this->createOrderWithComment('H');
        $this->assertIsInt($orderId);
    }

    public function testMinBoundaryComment()
    {
        $orderId = $this->createOrderWithComment('Hi');
        $this->assertIsInt($orderId);
    }

    public function testMinPlusOneComment()
    {
        $orderId = $this->createOrderWithComment('Egg');
        $this->assertIsInt($orderId);
    }

    public function testMaxMinusOneComment()
    {
        $orderId = $this->createOrderWithComment(str_repeat('a', 199));
        $this->assertIsInt($orderId);
    }

    public function testMaxBoundaryComment()
    {
        $orderId = $this->createOrderWithComment(str_repeat('a', 200));
        $this->assertIsInt($orderId);
    }

    public function testMaxPlusOneComment()
    {
        $orderId = $this->createOrderWithComment(str_repeat('a', 201));
        $this->assertIsInt($orderId);
    }

    public function testMidComment()
    {
        $orderId = $this->createOrderWithComment('Extra meat');
        $this->assertIsInt($orderId);
    }

    public function testExtremeMaxComment()
    {
        $orderId = $this->createOrderWithComment(str_repeat('a', 500));
        $this->assertIsInt($orderId);
    }

    public function testRejectsInvalidCharactersInComment()
    {
        $orderId = $this->createOrderWithComment('Hello <script>');
        $this->assertIsInt($orderId);
    }
}