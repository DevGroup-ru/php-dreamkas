<?php

namespace StudServise\Dreamkas;


/**
 * Class Configurable
 */
class Configurable
{
    /**
     * Configurable constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $attributes = get_object_vars($this);
        $result = [];
        foreach ($attributes as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (\is_array($value)) {
                $newValue = [];
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