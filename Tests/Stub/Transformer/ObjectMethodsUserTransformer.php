<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\Transformer;

class ObjectMethodsUserTransformer extends Transformer
{
    public function transform($resource)
    {
        return [
            'email' => $resource->getEmail(),
            'name' => $resource->getName(),
        ];
    }


}