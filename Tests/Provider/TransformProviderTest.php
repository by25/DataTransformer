<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Provider;


use Itmedia\DataTransformer\Provider\TransformProvider;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayGroupTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ObjectMethodsUserTransformer;
use PHPUnit\Framework\TestCase;

class TransformProviderTest extends TestCase
{

    public function testTransform()
    {
        $user = $this->getMockBuilder(\StdClass::class)
            ->setMethods(['getName', 'getEmail'])
            ->getMock();
        $user->method('getName')->willReturn('Andrey');
        $user->method('getEmail')->willReturn('Andrey@email.com');

        $item = [
            'user_name' => 'Tester',
            'user_email' => 'email@email.com',
            'password' => 'mypass',
            'friend' => $user,
            'user_group' => [
                [
                    'group_id' => 1,
                    'group_name' => 'User'
                ],
                [
                    'group_id' => 2,
                    'group_name' => 'Manager'
                ]
            ]
        ];

        $provider = new TransformProvider();

        $transformer = new ArrayUserTransformer(null, 'result');
        $transformer->addTransformer(new ArrayGroupTransformer('user_group', 'group[]'));
        $transformer->addTransformer(new ObjectMethodsUserTransformer('friend', 'my-friend'));

        $result = $provider->transform($item, $transformer);


        $this->assertEquals($result, [
            'result' => [
                'name' => 'Tester',
                'email' => 'email@email.com',
            ],
            'my-friend' => [
                'name' => 'Andrey',
                'email' => 'Andrey@email.com',
            ],
            'group' => [
                [
                    'id' => 1,
                    'name' => 'User'
                ],
                [
                    'id' => 2,
                    'name' => 'Manager'
                ]
            ]
        ]);


    }


}