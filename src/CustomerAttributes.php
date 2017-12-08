<?php

namespace DevGroup\Dreamkas;


use DevGroup\Dreamkas\exceptions\ValidationException;

class CustomerAttributes extends Configurable
{
    /** @var string|null */
    public $email;
    /** @var string|null */
    public $phone;

    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        if (empty($this->email) && empty($this->phone)) {
            throw new ValidationException('No customer details provided');
        }
        return true;
    }
}