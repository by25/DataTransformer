<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


class Collection extends AbstractTransformer
{

    /**
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * CollectionTransformer constructor.
     * @param TransformerInterface $transformer
     */
    public function __construct(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }


    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->transformer->getProperty();
    }


    /**
     * {@inheritdoc}
     */
    public function transform($resource)
    {
        if (!is_array($resource)) {
            throw new \InvalidArgumentException('Resource must be array');
        }

        $data = [];
        foreach ($resource as $item) {
            $transformedData = $this->transformer->transform($item);
            if ($transformedData) {
                $data[] = $transformedData;
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TransformerInterface $transformer)
    {
        return $this->transformer->add($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function addCollection(TransformerInterface $transformer)
    {
        return $this->transformer->addCollection($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function getMappingOptions()
    {
        return $this->transformer->getMappingOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function getTransformers()
    {
        return $this->transformer->getTransformers();
    }


    /**
     * {@inheritdoc}
     */
    public function execute($resource)
    {
        $rawData = $this->fetchDataProperty($resource, $this);

        if ($rawData === null || !is_array($rawData)) {
            return null;
        }

        $result = $this->transform($rawData);

        foreach ($this->getTransformers() as $childTransformers) {
            foreach ($rawData as $key => $item) {
                $childData = $childTransformers->execute($item);
                if (is_array($childData) && array_key_exists($key, $result)) {
                    $result[$key] += $childData;
                }
            }
        }

        return $this->returnMappedData($result);
    }
}
