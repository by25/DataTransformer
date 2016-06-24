<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Provider;


use Itmedia\ArrayTransformer\Transformer\TransformerInterface;

interface TransformProviderInterface
{

    /**
     * @param $item
     * @param TransformerInterface $transformer
     * @return array
     */
    public function transform($item, TransformerInterface $transformer);

    /**
     * @param array $collection
     * @param TransformerInterface $transformer
     * @return array
     */
    public function transformCollection(array $collection, TransformerInterface $transformer);


}