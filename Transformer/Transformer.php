<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\DataExtractor;

abstract class Transformer implements TransformerInterface
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

    public function add(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }


    public function addCollection(TransformerInterface $transformer)
    {
        $this->transformers[] = new Collection($transformer);
    }


    /**
     * @return TransformerInterface[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return null|string
     */
    public function getProperty()
    {
        return $this->property;
    }

    public function execute($resource)
    {

        $rawData = DataExtractor::fetchDataProperty($resource, $this);
//        var_dump($this->property, static::class, $rawData);

        $result = $this->transform($rawData);

        foreach ($this->transformers as $childTransformers) {
            $childData = $childTransformers->execute($rawData);
            if ($childData) {
                $result = array_merge($result, $childData);
            }
        }

        $key = $this->getOptions()['field'];
        if ($key === null) {
            $key = $this->getProperty();
        }
        if ($key) {
            return [
                $key => $result
            ];
        } else {
            return $result;
        }

    }


}