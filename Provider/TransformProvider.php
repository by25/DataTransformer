<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\DataTransformer\Transformer\TransformerInterface;

class TransformProvider implements TransformProviderInterface
{


    /**
     * Трансформация элемента
     *
     * @param $resource
     * @param TransformerInterface $transformer
     * @return array
     *
     * @throws UndefinedItemPropertyException
     */
    public function transform($resource, TransformerInterface $transformer)
    {
        $data = $transformer->createData($resource);

        foreach ($transformer->getTransformers() as $includedTransformer) {
            $transformedPart = $this->transform($resource, $includedTransformer);
            $data = array_merge($data, $transformedPart);
        }

        return $data;
    }


}