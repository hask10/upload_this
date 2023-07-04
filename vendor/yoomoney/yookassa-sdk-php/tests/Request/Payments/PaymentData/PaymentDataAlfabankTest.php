<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;


use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataAlfabank;

/**
 * @internal
 */
class PaymentDataAlfabankTest extends AbstractTestPaymentData
{
    /**
     * @dataProvider validLoginDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetLogin($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setLogin($value);
        self::assertEquals($value, $instance->getLogin());
        self::assertEquals($value, $instance->login);

        $instance = $this->getTestInstance();
        $instance->login = $value;
        self::assertEquals($value, $instance->getLogin());
        self::assertEquals($value, $instance->login);
    }

    /**
     * @dataProvider invalidLoginDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLogin($value): void
    {
        $this->expectException(TypeError::class);
        $instance = $this->getTestInstance();
        $instance->setLogin($value);
    }

    /**
     * @dataProvider invalidLoginDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidLogin($value): void
    {
        $this->expectException(TypeError::class);
        $instance = $this->getTestInstance();
        $instance->login = $value;
    }

    public static function validLoginDataProvider()
    {
        return [
            ['123'],
            [Random::str(256)],
            [Random::str(1024)],
        ];
    }

    public static function invalidLoginDataProvider()
    {
        return [
            [new \DateTime()],
            [new \stdClass()],
            [[false]],
        ];
    }

    protected function getTestInstance(): PaymentDataAlfabank
    {
        return new PaymentDataAlfabank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::ALFABANK;
    }
}
