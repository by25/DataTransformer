<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\Transformer;

class ArrayGroupTransformer extends Transformer
{
    public function map($resource)
    {
        return [
            'id' => $resource['group_id'],
            'name' => $resource['group_name']
        ];
    }

}