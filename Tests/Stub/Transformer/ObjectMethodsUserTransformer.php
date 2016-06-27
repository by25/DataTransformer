<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\AbstractTransformer;

class ObjectMethodsUserTransformer extends AbstractTransformer
{
    public function transformItem($item)
    {
        return [
            'email' => $item->getEmail(),
            'name' => $item->getName(),
        ];
    }


}