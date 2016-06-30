<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\Transformer;

class ArrayUserTransformer extends Transformer
{
    public function map($resource)
    {
        return [
            'name' => $resource['user_name'],
            'email' => $resource['user_email']
        ];
    }

}