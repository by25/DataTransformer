<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;

abstract class AbstractTransformer implements TransformerInterface
{

    /**
     * @var string|null
     */
    private $property;


    /**
     * {@inheritdoc}
     */
    public function __construct($property = null)
    {
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function createData($resource, $isCollection)
    {
        $data = $this->fetchDataProperty($this->property, $resource);

        if (!$isCollection) {
            return $this->transform($data);
        }

        $result = [];
        foreach ($data as $value) {
            $result[] = $this->transform($value);
        }

        return $result;
    }

    /**
     * @return null|string
     */
    public function getProperty()
    {
        return $this->property;
    }


    /**
     * Извлекает значение из $item
     *
     * @param $property
     * @param $item
     * @return mixed
     *
     * @throws UndefinedItemPropertyException
     */
    private function fetchDataProperty($property, $item)
    {
        if (!$property) {
            return $item;
        }

        if (is_array($item) && array_key_exists($property, $item)) {
            return $item[$property];
        }

        if (method_exists($item, $property)) {
            return $item->{$property}();
        }

        throw new UndefinedItemPropertyException(sprintf(
            'Undefined property "%s"', $property
        ));
    }

}