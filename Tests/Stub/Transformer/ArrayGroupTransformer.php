<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Stub\Transformer;


use Itmedia\DataTransformer\Transformer\Transformer;

class ArrayGroupTransformer extends Transformer
{
    public function transform($resource)
    {
        $data = [
            'id' => $resource['group_id'],
            'name' => $resource['group_name'],
        ];

        if ($this->getOption('show_hi')) {
            $data['hi'] = $this->getOption('hi');
        }


        return $data;
    }

    protected function defaultOptions()
    {
        return [
            'show_hi' => false,
            'hi' => 'value'
        ];
    }


}