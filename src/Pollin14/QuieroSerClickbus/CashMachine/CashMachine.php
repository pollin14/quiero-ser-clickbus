<?php

namespace Pollin14\QuieroSerClickbus\CashMachine;

use InvalidArgumentException;

/**
 * Class CashMachine
 * @package Pollin14\QuieroSerClickbus\CashMachine
 */
class CashMachine
{
    const TEN       = 10;
    const TWENTY    = 20;
    const FIFTY     = 50;
    const HUNDRED   = 100;
    const BIGGER    = self::HUNDRED;

    /**
     * @var array
     */
    protected $bills = [
        'ten'       => self::TEN,
        'twenty'    => self::TWENTY,
        'fifty'     => self::FIFTY,
        'hundred'   => self::HUNDRED
    ];

    public function withdraw($amount)
    {
        $result = $this->recWithdraw($amount, self::BIGGER);

//        $cleanResult = $this->cleanResult($result);
//
//        return $cleanResult;

        return $result;
    }

    private function recWithdraw($amount, $billSize, array $result= [])
    {
        if ($amount === 0) {
            return $result;
        }

        if ($amount < self::TEN) {
            throw new InvalidArgumentException();
        }

        $nextBillSize = $this->billBiggerAndSmallerThat($billSize);

        if ($amount < $billSize) {
            return $this->recWithdraw($amount, $nextBillSize, $result);
        }

        $rest   = $amount % $billSize;
        $bills  = intval(floor($amount / $billSize));

        array_push($result, $bills * $billSize);

        return $this->recWithdraw($rest, $nextBillSize, $result);
    }

    private function billBiggerAndSmallerThat($upperBound)
    {
        $bigger = 0;

        foreach ($this->bills as $bill) {
            if ($upperBound > $bill && $bigger < $bill) {
                $bigger = $bill;
            }
        }

        return $bigger;
    }

    private function cleanResult(array $result)
    {
        return array_reduce($result, function ($cleanResult, $value) {

            if ($value === 0) {
                return $cleanResult;
            }

            array_push($cleanResult, $value);

            return $cleanResult;
        }, []);
    }
}
