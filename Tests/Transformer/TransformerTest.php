<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Transformer;

use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayGroupTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ObjectMethodsUserTransformer;
use Itmedia\DataTransformer\Transformer\Collection;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{
    private $user = [
        'user_name' => 'Tester',
        'user_email' => 'email@email.com',
        'property' => 1
    ];


    public function testArrayTransform()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->transform($this->user);

        self::assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);
    }


    public function testObjectTransform()
    {
        $user = $this->getMockBuilder('\StdClass')
            ->setMethods(['getName', 'getEmail'])
            ->getMock();
        $user->method('getName')->willReturn('Tester');
        $user->method('getEmail')->willReturn('email@email.com');

        $transformer = new ObjectMethodsUserTransformer();

        $result = $transformer->transform($user);
        self::assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);
    }


    public function testTransformOptions()
    {
        $resource = [
            'group_id' => 1,
            'group_name' => 'Group name'
        ];

        $transformer = new ArrayGroupTransformer(null, [], [
            'show_hi' => true
        ]);
        $result = $transformer->transform($resource);
        self::assertEquals($result, [
            'id' => 1,
            'name' => 'Group name',
            'hi' => 'value'
        ]);


        $transformer = new ArrayGroupTransformer(null, [], [
            'show_hi' => true,
            'hi' => 'new value'
        ]);
        $result = $transformer->transform($resource);
        self::assertEquals($result, [
            'id' => 1,
            'name' => 'Group name',
            'hi' => 'new value'
        ]);
    }


    public function testTransformOptionsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $resource = [
            'group_id' => 1,
            'group_name' => 'Group name'
        ];

        $transformer = new ArrayGroupTransformer(null, [], [
            'invalid_option' => true
        ]);
        $transformer->transform($resource);
    }


    public function testOnceExecute()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->execute($this->user);

        self::assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);


        $transformer = new ArrayUserTransformer('data');
        $result = $transformer->execute([
            'data' => $this->user
        ]);

        self::assertEquals($result, [
            'data' => [
                'name' => 'Tester',
                'email' => 'email@email.com'
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', ['field' => false]);
        $result = $transformer->execute([
            'data' => $this->user
        ]);

        self::assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'

        ]);
    }

    public function testExceptions()
    {
        $this->expectException(UndefinedItemPropertyException::class);

        $transformer = new ObjectMethodsUserTransformer('test', ['required' => true]);
        $transformer->execute($this->user);
    }
}
