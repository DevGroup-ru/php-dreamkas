<?php

namespace DevGroup\Dreamkas;


use DevGroup\Dreamkas\exceptions\ValidationException;

/**
 * Class CustomerAttributes описывает информацию о покупателе, куда ему высылать чек
 */
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
    public function validate()
    {
        if (empty($this->email) && empty($this->phone)) {
            throw new ValidationException('No customer details provided');
        }
        return true;
    }
}