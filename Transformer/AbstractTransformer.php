<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Transformer;


abstract class AbstractTransformer implements TransformerInterface
{


    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    /**
     * AbstractTransformer constructor.
     * @param array $transformers [[$property => $transformer]]
     */
    public function __construct(array $transformers = [])
    {
        $this->transformers = $transformers;
    }


    /**
     * {@inheritdoc}
     */
    public function addTransformer($property, TransformerInterface $transformer)
    {
        $this->transformers[$property] = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransformers()
    {
        return $this->transformers;
    }


    /**
     * {@inheritdoc}
     */
    public function getIncludeKey()
    {
        return null;
    }


}