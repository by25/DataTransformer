<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Tests\Stub\Transformer;


use Itmedia\ArrayTransformer\Transformer\AbstractTransformer;

class UserProfileTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        return [
            'age' => $item->getAge()
        ];
    }


}