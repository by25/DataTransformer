<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\AbstractTransformer;

class UserProfileTransformer extends AbstractTransformer
{
    public function transform($item)
    {
        return [
            'age' => $item->getAge()
        ];
    }


}