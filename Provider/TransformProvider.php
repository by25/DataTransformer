<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Provider;


use Itmedia\ArrayTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\ArrayTransformer\Transformer\TransformerInterface;

class TransformProvider implements TransformProviderInterface
{


    /**
     * Трансформация коллекции
     *
     * @param array $collection
     * @param TransformerInterface $transformer
     * @return array
     */
    public function transformCollection(array $collection, TransformerInterface $transformer)
    {
        if (!count($collection)) {
            return [];
        }

        $data = [];
        foreach ($collection as $item) {
            $data[] = $this->transform($item, $transformer);
        }

        return $data;
    }


    /**
     * Трансформация элемента
     *
     * @param $item
     * @param TransformerInterface $transformer
     * @return array
     *
     * @throws UndefinedItemPropertyException
     */
    public function transform($item, TransformerInterface $transformer)
    {
        $data = $transformer->transform($item);

        $parts = [];

        foreach ($transformer->getTransformers() as $property => $includedTransformer) {
            $partItem = $this->fetchProperty($property, $item);
            $parts[$property] = $this->transform($partItem, $includedTransformer);
        }

        return $this->bindData($transformer, $data, $parts);
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
    private function fetchProperty($property, $item)
    {
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


    /**
     * @param TransformerInterface $transformer
     * @param array $data
     * @param array $parts
     * @return array
     */
    private function bindData(TransformerInterface $transformer, array $data, array $parts)
    {
        $map = $transformer->getBindingMap();

        foreach ($map as $property => $key) {
            if (array_key_exists($property, $parts)) {
                $data[$key] = $parts[$property];
                unset($parts[$property]);
            }
        }

        return array_merge($data, $parts);
    }

}