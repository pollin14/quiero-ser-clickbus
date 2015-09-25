<?php

namespace Pollin14\QuieroSerClickbus\Test\CashMachine;

use Pollin14\QuieroSerClickbus\CashMachine\CashMachine;

/**
 * Class CashMachineTest
 */
class CashMachineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CashMachine
     */
    protected $cashMachine;

    public function setUp()
    {
        $this->cashMachine = new CashMachine();
    }

    public function testWithdraw()
    {
        $this->assertEquals([20, 10], $this->cashMachine->withdraw(30));
        $this->assertEquals([40], $this->cashMachine->withdraw(40));
        $this->assertEquals([50, 20, 10], $this->cashMachine->withdraw(80));
        $this->assertEquals([], $this->cashMachine->withdraw(0));
        $this->assertEquals([500, 50, 20, 10], $this->cashMachine->withdraw(580));
        $this->assertEquals([500, 50, 40], $this->cashMachine->withdraw(590));
        $this->assertEquals([500, 10], $this->cashMachine->withdraw(510));
        $this->assertEquals([500, 50, 10], $this->cashMachine->withdraw(560));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithdrawArgumentException()
    {
        $invalidValues = [125, -130];

        foreach ($invalidValues as $value) {
            $this->cashMachine->withdraw($value);
        }
    }

    public function testBillBiggerAndSmallerThat()
    {
        $this->invokeMethod(new CashMachine(), 'billBiggerAndSmallerThat', [500]);
    }

    public function testCleanResult()
    {
        $this->assertEquals([500], $this->invokeMethod(new CashMachine(), 'cleanResult', [[0, 0, 500]]));
        $this->assertEquals([500], $this->invokeMethod(new CashMachine(), 'cleanResult', [[0, 500, 0]]));
        $this->assertEquals([500], $this->invokeMethod(new CashMachine(), 'cleanResult', [[500]]));
        $this->assertEquals([500], $this->invokeMethod(new CashMachine(), 'cleanResult', [[500, 0, 0]]));
        $this->assertEquals([500], $this->invokeMethod(new CashMachine(), 'cleanResult', [[500, 0, 0]]));
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
