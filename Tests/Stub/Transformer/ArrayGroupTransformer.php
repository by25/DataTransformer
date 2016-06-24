<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Tests\Stub\Transformer;


use Itmedia\ArrayTransformer\Transformer\AbstractTransformer;

class ArrayGroupTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name']
        ];
    }

}