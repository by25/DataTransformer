<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;

use Itmedia\DataTransformer\Transformer\Collection;
use Itmedia\DataTransformer\Transformer\TransformerInterface;


class TransformProvider implements TransformProviderInterface
{
    private $options = [
        'strict' => true
    ];


    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        foreach ($options as $key => $option) {
            if (!array_key_exists($key, $this->options)) {
                throw new \InvalidArgumentException(sprintf('Undefined option "%s"', $key));
            }
            $this->options[$key] = $option;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function transformItem($resource, TransformerInterface $transformer)
    {
        return $transformer->execute($resource, $this->options['strict']);
    }


    /**
     * {@inheritdoc}
     */
    public function transformCollection($resource, TransformerInterface $transformer)
    {
        return $this->transformItem($resource, new Collection($transformer));
    }

}