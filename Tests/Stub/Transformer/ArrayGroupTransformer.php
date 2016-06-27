<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\AbstractTransformer;

class ArrayGroupTransformer extends AbstractTransformer
{
    public function transformItem($item)
    {
        return [
            'id' => $item['group_id'],
            'name' => $item['group_name']
        ];
    }

}