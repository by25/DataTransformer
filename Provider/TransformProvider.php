<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\DataTransformer\Transformer\TransformerInterface;

class TransformProvider
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
     * TransformProvider constructor.
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }


    public function addTransformer(TransformerInterface $transformer, $field = null)
    {
        $this->transform($transformer, false, $field, true);
        return $this;
    }


    public function addCollectionTransformer(TransformerInterface $transformer, $field = null)
    {
        $this->transform($transformer, true, $field, true);
        return $this;
    }


    /**
     * @throws \Exception
     * @return array
     */
    public function getArray()
    {
        return $this->output;
    }


    private function transform(TransformerInterface $transformer, $isCollection, $field, $required)
    {
        $result = null;
        try {
            $result = $transformer->createData($this->resource, (bool)$isCollection);
        } catch (UndefinedItemPropertyException $exc) {
            if ($required) {
                throw $exc;
            }
        }


        if ($field) {
            $this->output[$field] = $result;
        } elseif (is_array($result)) {
            $this->output = array_merge($this->output, $result);
        }
    }

}