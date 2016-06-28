<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\DataTransformer\Transformer\Collection;
use Itmedia\DataTransformer\Transformer\TransformerInterface;

class TransformProvider implements TransformProviderInterface
{

    /**
     * @var mixed
     */
    private $resource;

    /**
     * @var array
     */
    private $output = [];


    /**
     * {@inheritdoc}
     */
    public function __construct($resource, TransformerInterface $transformer)
    {
        $this->output = $transformer->execute($resource);
    }


    public function createData()
    {
        return $this->output;
    }


    private function fetchDataProperty($resource, TransformerInterface $transformer)
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

        throw new UndefinedItemPropertyException(sprintf(
            'Undefined property "%s"', $transformer->getProperty()
        ));
    }

}