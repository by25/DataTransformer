<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;

abstract class AbstractTransformer implements TransformerInterface
{

    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @var string|null
     */
    private $inputProperty;

    /**
     * @var string|null
     */
    private $outputKey;


    /**
     * {@inheritdoc}
     */
    public function __construct($inputProperty = null, $outputKey = null, array $transformers = [])
    {
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }

        $this->inputProperty = $inputProperty;
        if ($outputKey === null || $outputKey === false) {
            $this->outputKey = $outputKey;
        } else {
            $this->outputKey = trim($outputKey);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->inputProperty;
    }


    /**
     * {@inheritdoc}
     */
    public function getOutputKey()
    {
        return $this->outputKey;
    }


    /**
     * {@inheritdoc}
     */
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
        return $this;
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
     *
     * @throws \Exception
     */
    public function createData($item)
    {
        $data = $this->fetchDataProperty($this->getProperty(), $item);

        if (!$this->isCollection()) {
            $result = $this->transformItem($data);
            return $this->mapToOutputArray($result);
        }

        $result = [];
        foreach ($data as $value) {
            $result[] = $this->transformItem($value);
        }

        return $this->mapToOutputArray($result);
    }


    /**
     * Является коллекцией?
     *
     * @return bool
     */
    private function isCollection()
    {
        return strpos((string)$this->getOutputKey(), '[]') !== false;
    }


    /**
     * @param $data
     * @return array
     */
    private function mapToOutputArray($data)
    {
        $key = $this->isCollection() ? str_replace('[]', '', $this->getOutputKey()) : $this->getOutputKey();

        if ($key === null or $key === '') {
            return $this->getProperty() ? [$this->getProperty() => $data] : $data;
        }

        if ($key === false) {
            return $data;
        }

        return [$key => $data];
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
    private function fetchDataProperty($property, $item)
    {
        if (!$property) {
            return $item;
        }

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

}