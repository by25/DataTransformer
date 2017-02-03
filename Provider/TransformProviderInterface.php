<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;

use Itmedia\DataTransformer\Transformer\TransformerInterface;

interface TransformProviderInterface
{

    /**
     * TransformProvider constructor.
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options = []);


    /**
     * Трансформация единичного элемента
     *
     * @param $resource
     * @param TransformerInterface $transformer
     * @return array|null
     */
    public function transformItem($resource, TransformerInterface $transformer);


    /**
     * Трансформация коллекции
     *
     * @param $resource
     * @param TransformerInterface $transformer
     * @return array|null
     */
    public function transformCollection($resource, TransformerInterface $transformer);
}
