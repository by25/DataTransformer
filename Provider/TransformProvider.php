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

        foreach ($transformer->getTransformers() as $property => $includedTransformer) {

            $partItem = $this->fetchProperty($property, $item);
            $partData = $includedTransformer->transform($partItem);

            $key = $includedTransformer->getIncludeKey();
            if ($key) {
                $data[$key] = $partData;
            } else {
                $data = array_merge($data, $partData);
            }
        }

        return $data;
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
        if (is_array($item) && in_array($property, $item, true)) {
            return $item[$property];
        }

        if (method_exists($item, $property)) {
            return $item->{$property}();
        }

        if (property_exists($item, $property)) {
            return $item->{$property};
        }

        throw new UndefinedItemPropertyException(sprintf(
            'Undefined property "%s"', $property
        ));
    }

}