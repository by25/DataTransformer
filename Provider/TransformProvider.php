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
        'root_key' => null
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
     *
     * @throws \Exception
     */
    public function transformItem($resource, TransformerInterface $transformer)
    {
        $data = $transformer->execute($resource);

        $rootKey = $this->options['root_key'];
        if ($rootKey) {
            return [$rootKey => $data];
        }

        return $data;
    }


    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function transformCollection($resource, TransformerInterface $transformer)
    {
        return $this->transformItem($resource, new Collection($transformer));
    }

}