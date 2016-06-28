<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\DataExtractor;
use Itmedia\DataTransformer\Exception\ExpectedCollectionException;

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
    public function map($resource)
    {
        if (!is_array($resource)) {
            throw new \InvalidArgumentException('Resource must be array');
        }

        $data = [];
        foreach ($resource as $item) {
            $data[] = $this->transformer->map($item);
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
    public function getOptions()
    {
        return $this->transformer->getOptions();
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
    public function execute($resource, $strict)
    {
        $rawData = $this->fetchDataProperty($resource, $this);
        
        if (!$rawData && !$strict) {
            return null;
        }

        $result = $this->map($rawData);

        foreach ($this->getTransformers() as $childTransformers) {
            
            foreach ($rawData as $key => $item) {
                $childData = $childTransformers->execute($item, $strict);
                if ($childData) {
                    $result[$key] += $childData;
                }
            }
            
        }

        return $this->returnMappedData($result);
    }


}