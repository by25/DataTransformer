<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


use Itmedia\DataTransformer\DataExtractor;
use Itmedia\DataTransformer\Exception\ExpectedCollectionException;

class Collection implements TransformerInterface
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


    public function getProperty()
    {
        return $this->transformer->getProperty();
    }


    public function transform($resource)
    {
//        return $this->transformer->transform($resource);
        if (!is_array($resource)) {
            throw new ExpectedCollectionException('Resource must be array');
        }

        $data = [];
        foreach ($resource as $item) {
            $data[] = $this->transformer->transform($item);
        }

        return $data;
    }

    public function add(TransformerInterface $transformer)
    {
        return $this->transformer->add($transformer);
    }

    public function addCollection(TransformerInterface $transformer)
    {
        return $this->transformer->addCollection($transformer);
    }

    public function getOptions()
    {
        return $this->transformer->getOptions();
    }

    public function getTransformers()
    {
        return $this->transformer->getTransformers();
    }


    public function execute($resource)
    {
        $rawData = DataExtractor::fetchDataProperty($resource, $this);
        $result = $this->transform($rawData);

        foreach ($this->getTransformers() as $childTransformers) {

            foreach ($rawData as $key => $item) {
                $childData = $childTransformers->execute($item);
                if ($childData) {
                    $result[$key] = array_merge($result[$key], $childData);
                }
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