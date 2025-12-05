<?php
use PHPUnit\Framework\TestCase;
use App\Models\OrderModel;

class AddOrderTest extends TestCase
{
    private $validOrderData;

    protected function setUp(): void
    {
        $this->validOrderData = [
            'comments' => 'No onions please',
            'final_amount' => 100.00,
            'items' => [1 => 2]
        ];
    }

    protected function tearDown(): void
    {
        $db = \App\Core\Database::connect();
        if ($db->inTransaction()) {
            $db->rollBack();
        }
    }

    private function uniqueEmail($prefix = 'test'): string
    {
        return $prefix . uniqid() . '@example.com';
    }

    public function testRejectsEmptyName()
    {
        $customerData = ['name' => '', 'email' => $this->uniqueEmail('emptyname'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testRejectsOneCharName()
    {
        $customerData = ['name' => 'S', 'email' => $this->uniqueEmail('onechar'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsTwoCharName()
    {
        $customerData = ['name' => 'Sa', 'email' => $this->uniqueEmail('twochar'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsThreeCharName()
    {
        $customerData = ['name' => 'San', 'email' => $this->uniqueEmail('threechar'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsMaxMinusOneName()
    {
        $customerData = ['name' => str_repeat('a', 24), 'email' => $this->uniqueEmail('maxminusone'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsMaxBoundaryName()
    {
        $customerData = ['name' => str_repeat('a', 25), 'email' => $this->uniqueEmail('maxboundary'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testRejectsMaxPlusOneName()
    {
        $customerData = ['name' => str_repeat('a', 26), 'email' => $this->uniqueEmail('maxplusone'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsMidName()
    {
        $customerData = ['name' => 'Sanjana Akter', 'email' => $this->uniqueEmail('midname'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testRejectsExtremeMaxName()
    {
        $customerData = ['name' => str_repeat('a', 50), 'email' => $this->uniqueEmail('extrememaxname'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testRejectsInvalidDataTypeName()
    {
        $customerData = ['name' => 'Sanjana123', 'email' => $this->uniqueEmail('invalidname'), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }


    //EMAIL TESTS
    
    public function testRejectsEmptyEmail()
    {
        $customerData = ['name' => 'ValidName', 'email' => '', 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testRejectsTooShortEmail()
    {
        $customerData = ['name' => 'ValidName', 'email' => 's@j', 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsShortestValidEmail()
    {
        $customerData = ['name' => 'ValidName', 'email' => 's' . uniqid() . '@j.co', 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsValidEmail()
    {
        $customerData = ['name' => 'ValidName', 'email' => 'sa' . uniqid() . '@je.com', 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }

    public function testAcceptsMidEmail()
    {
        $customerData = ['name' => 'ValidName', 'email' => uniqid() . 'Sanjana@gmail.com', 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }


    public function testRejectsInvalidDataTypeEmail()
    {
        $customerData = ['name' => 'ValidName', 'email' => 'Abc123' . uniqid(), 'phone' => null];
        $orderId = OrderModel::createWithCustomer($customerData, $this->validOrderData);
        $this->assertIsInt($orderId);
    }
}