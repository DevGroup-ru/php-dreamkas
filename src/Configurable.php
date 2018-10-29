<?php

namespace DevGroup\Dreamkas;


/**
 * Class Configurable
 */
class Configurable
{
    /**
     * Configurable constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $attributes = get_object_vars($this);
        $result = array();
        foreach ($attributes as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (\is_array($value)) {
                $newValue = array();
                foreach ($value as $valueKey => $valueItem) {
                    if ($valueItem instanceof self) {
                        $newValue[$valueKey] = $valueItem->toArray();
                    } else {
                        $newValue[$valueKey] = $valueItem;
                    }
                }
                $value = $newValue;
            }
            if ($value instanceof self) {
                $value = $value->toArray();
            }
            $result[$key] = $value;

        }
        return $result;
    }
}