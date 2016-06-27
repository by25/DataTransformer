<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Transformer;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ObjectMethodsUserTransformer;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{

    private $user = [
        'user_name' => 'Tester',
        'user_email' => 'email@email.com',
        'property' => 1
    ];

    private $users = [
        [
            'user_name' => 'Tester',
            'user_email' => 'tester1@email.com'
        ],
        [
            'user_name' => 'Tester2',
            'user_email' => 'tester2@email.com'
        ],
    ];


    public function testArrayTransform()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->transform($this->user);

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);
    }


    public function testObjectTransform()
    {
        $user = $this->getMockBuilder(\StdClass::class)
            ->setMethods(['getName', 'getEmail'])
            ->getMock();
        $user->method('getName')->willReturn('Tester');
        $user->method('getEmail')->willReturn('email@email.com');

        $transformer = new ObjectMethodsUserTransformer();

        $result = $transformer->transform($user);
        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);
    }


    public function testOnceCreateData()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->createData($this->user, false);

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);


        $transformer = new ArrayUserTransformer('data');
        $result = $transformer->createData([
            'data' => $this->user
        ], false);

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);

    }


    public function testCollectionCreateData()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->createData($this->users, true);

        $this->assertEquals($result, [
            [
                'name' => 'Tester',
                'email' => 'tester1@email.com'
            ],
            [
                'name' => 'Tester2',
                'email' => 'tester2@email.com'
            ]
        ]);


        $transformer = new ArrayUserTransformer('data');
        $result = $transformer->createData(['data' => $this->users], true);

        $this->assertEquals($result, [
            [
                'name' => 'Tester',
                'email' => 'tester1@email.com'
            ],
            [
                'name' => 'Tester2',
                'email' => 'tester2@email.com'
            ]
        ]);

    }


    public function testExceptions()
    {
        $this->expectException(UndefinedItemPropertyException::class);
        
        $transformer = new ObjectMethodsUserTransformer('test');
        $transformer->createData($this->user, true);
    }


}