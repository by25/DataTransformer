<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Tests\Provider;


use Itmedia\ArrayTransformer\Provider\TransformProvider;
use Itmedia\ArrayTransformer\Tests\Stub\Transformer\ArrayGroupTransformer;
use Itmedia\ArrayTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\ArrayTransformer\Tests\Stub\Transformer\ObjectMethodsUserTransformer;
use PHPUnit\Framework\TestCase;

class TransformProviderTest extends TestCase
{

    public function testSimpleTransform()
    {
        $item = [
            'user_name' => 'Tester',
            'user_email' => 'email@email.com'
        ];

        $provider = new TransformProvider();
        $result = $provider->transform($item, new ArrayUserTransformer());

        $this->assertEquals('Tester', $result['name']);
        $this->assertEquals('email@email.com', $result['email']);


        $user = $this->getMockBuilder(\StdClass::class)
            ->setMethods(['getName', 'getEmail'])
            ->getMock();
        $user->method('getName')->willReturn('Tester');
        $user->method('getEmail')->willReturn('email@email.com');

        $result = $provider->transform($user, new ObjectMethodsUserTransformer());

        $this->assertEquals('Tester', $result['name']);
        $this->assertEquals('email@email.com', $result['email']);
    }


    public function testMultiTransform()
    {
        $user = $this->getMockBuilder(\StdClass::class)
            ->setMethods(['getName', 'getEmail'])
            ->getMock();
        $user->method('getName')->willReturn('Andrey');
        $user->method('getEmail')->willReturn('Andrey@email.com');

        $item = [
            'user_name' => 'Tester',
            'user_email' => 'email@email.com',
            'friend' => $user,
            'user_group' => [
                'group_id' => 1,
                'group_name' => 'User'
            ]
        ];


        $provider = new TransformProvider();

        $transformer = new ArrayUserTransformer();
        $transformer
            ->addTransformer('friend', new ObjectMethodsUserTransformer())
            ->addTransformer('user_group', new ArrayGroupTransformer());

        $result = $provider->transform($item, $transformer);

        $pattern = [
            'name' => 'Tester',
            'email' => 'email@email.com',
            'friend' => [
                'name' => 'Andrey',
                'email' => 'Andrey@email.com',
            ],
            'user_group' => [
                'id' => 1,
                'name' => 'User'
            ]
        ];
        $this->assertEquals($pattern, $result);
    }

    public function testMappedTransform()
    {

        $user = $this->getMockBuilder(\StdClass::class)
            ->setMethods(['getName', 'getEmail'])
            ->getMock();
        $user->method('getName')->willReturn('Andrey');
        $user->method('getEmail')->willReturn('Andrey@email.com');

        $item = [
            'user_name' => 'Tester',
            'user_email' => 'email@email.com',
            'friend' => $user,
            'user_group' => [
                'group_id' => 1,
                'group_name' => 'User'
            ]
        ];


        $provider = new TransformProvider();

        $transformer = new ArrayUserTransformer();
        $transformer
            ->addTransformer('friend', new ObjectMethodsUserTransformer())
            ->addTransformer('user_group', new ArrayGroupTransformer())
            ->setBindingMap([
                'user_group' => 'group',
                'friend' => 'renamed_key'
            ]);

        $result = $provider->transform($item, $transformer);

        $pattern = [
            'name' => 'Tester',
            'email' => 'email@email.com',
            'renamed_key' => [
                'name' => 'Andrey',
                'email' => 'Andrey@email.com',
            ],
            'group' => [
                'id' => 1,
                'name' => 'User'
            ]
        ];
        $this->assertEquals($pattern, $result);
    }


}