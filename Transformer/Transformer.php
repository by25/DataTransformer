<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\DataExtractor;

abstract class Transformer extends AbstractTransformer
{

    /**
     * @var string|null
     */
    private $property;

    /**
     * @var array
     */
    private $options = [
        'field' => null,
        'required' => false
    ];


    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];


    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($property = null, array $options = [])
    {
        $this->property = $property;

        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                throw new \InvalidArgumentException(sprintf('Undefined option "%s"', $key));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }


    /**
     * {@inheritdoc}
     */
    public function addCollection(TransformerInterface $transformer)
    {
        $this->transformers[] = new Collection($transformer);
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
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->property;
    }


    /**
     * {@inheritdoc}
     */
    public function execute($resource, $strict)
    {
        $rawData = $this->fetchDataProperty($resource, $this);

        if (!$rawData && !$strict) {
            return null;
        }

        $result = $this->map($rawData);

        foreach ($this->transformers as $childTransformers) {
            $childData = $childTransformers->execute($rawData, $strict);
            if ($childData) {
                $result += $childData;
            }
        }

        return $this->returnMappedData($result);
    }


}