<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Tests\Stub\Transformer;


use Itmedia\ArrayTransformer\Transformer\AbstractTransformer;

class ObjectMethodsUserTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        return [
            'email' => $item->getEmail(),
            'name' => $item->getName(),
        ];
    }


}