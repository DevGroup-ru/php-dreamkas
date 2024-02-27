<?php

namespace StudServise\Dreamkas;
use StudServise\Dreamkas\exceptions\ValidationException;


/**
 * Class Position
 */
class Position extends Configurable
{
    const TYPE_COUNTABLE = 'COUNTABLE';
    const TYPE_SCALABLE = 'SCALABLE';

    public $name = '';

    public $type = self::TYPE_COUNTABLE;

    public $quantity = 0;
    public $price = 0;
    public $priceSum = 0;

    /** @var null|string */
    public $tax;

    /** @var null|int */
    public $taxSum;

    public function calculate()
    {
        $this->priceSum = $this->price * $this->quantity;
    }

    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        if (empty($this->name)) {
            throw new ValidationException('Name is not specified');
        }
        if ($this->quantity <= 0) {
            throw new ValidationException('Quantity is not specified');
        }
        if ($this->price <= 0) {
            throw new ValidationException('Price is not specified');
        }
        return true;
    }
}