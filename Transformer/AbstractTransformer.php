<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;

abstract class AbstractTransformer implements TransformerInterface
{

    /**
     * @param $data
     * @return array
     */
    protected function returnMappedData($data)
    {
        $key = $this->getOptions()['field'];

        if ($key === null) {
            $key = $this->getProperty();
        }

        if (!$key) {
            return $data;
        }

        return [$key => $data];
    }


    /**
     * Получить данные из ресурса
     *
     * @param $resource
     * @param TransformerInterface $transformer
     * @return mixed|null
     *
     * @throws UndefinedItemPropertyException
     */
    protected function fetchDataProperty($resource, TransformerInterface $transformer)
    {
        if (!$transformer->getProperty()) {
            return $resource;
        }

        if (is_array($resource) && array_key_exists($transformer->getProperty(), $resource)) {
            return $resource[$transformer->getProperty()];
        }

        if (method_exists($resource, $transformer->getProperty())) {
            return $resource->{$transformer->getProperty()}();
        }

        if ($transformer->getOptions()['required']) {
            throw new UndefinedItemPropertyException(sprintf(
                'Undefined property "%s"', $transformer->getProperty()
            ));
        } else {
            return null;
        }
    }

}