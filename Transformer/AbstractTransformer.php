<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


abstract class AbstractTransformer implements TransformerInterface
{
    
    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @var array
     */
    private $bindingMap = [];
    

    /**
     * AbstractTransformer constructor.
     * @param array $transformers [[$property => $transformer]]
     * @param array $map
     */
    public function __construct(array $transformers = [], array $map = [])
    {
        foreach ($transformers as $property => $transformer) {
            $this->addTransformer($property, $transformer);
        }

        $this->setBindingMap($map);
    }

    /**
     * {@inheritdoc}
     */
    public function setBindingMap(array $map = [])
    {
        $this->bindingMap = $map;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingMap()
    {
        return $this->bindingMap;
    }

    /**
     * {@inheritdoc}
     */
    public function addTransformer($property, TransformerInterface $transformer)
    {
        $this->transformers[$property] = $transformer;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransformers()
    {
        return $this->transformers;
    }


}