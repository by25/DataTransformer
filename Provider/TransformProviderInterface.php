<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;


use Itmedia\DataTransformer\Transformer\TransformerInterface;

interface TransformProviderInterface
{

    /**
     * @param $resource
     * @param TransformerInterface $transformer
     * @return array
     */
    public function transform($resource, TransformerInterface $transformer);


}