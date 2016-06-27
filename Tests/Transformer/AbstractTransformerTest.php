<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Transformer;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ObjectMethodsUserTransformer;
use PHPUnit\Framework\TestCase;

class AbstractTransformerTest extends TestCase
{

    private $user = [
        'user_name' => 'Tester',
        'user_email' => 'email@email.com'
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
        $result = $transformer->transformItem($this->user);

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

        $result = $transformer->transformItem($user);
        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);
    }


    public function testOnceCreateData()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->createData($this->user);

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);


        $transformer = new ArrayUserTransformer(null, 'user');
        $result = $transformer->createData($this->user);

        $this->assertEquals($result, [
            'user' => [
                'name' => 'Tester',
                'email' => 'email@email.com'
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', 'user');
        $result = $transformer->createData([
            'data' => $this->user
        ]);

        $this->assertEquals($result, [
            'user' => [
                'name' => 'Tester',
                'email' => 'email@email.com'
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', null);
        $result = $transformer->createData([
            'data' => $this->user
        ]);

        $this->assertEquals($result, [
            'data' => [
                'name' => 'Tester',
                'email' => 'email@email.com'
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', false);
        $result = $transformer->createData([
            'data' => $this->user
        ]);

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'

        ]);
    }


    public function testCollectionCreateData()
    {
        $transformer = new ArrayUserTransformer(null, '[]');
        $result = $transformer->createData($this->users);

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


        $transformer = new ArrayUserTransformer(null, 'data[]');
        $result = $transformer->createData($this->users);

        $this->assertEquals($result, [
            'data' => [
                [
                    'name' => 'Tester',
                    'email' => 'tester1@email.com'
                ],
                [
                    'name' => 'Tester2',
                    'email' => 'tester2@email.com'
                ]
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', '[]');
        $result = $transformer->createData(['data' => $this->users]);

        $this->assertEquals($result, [
            'data' => [
                [
                    'name' => 'Tester',
                    'email' => 'tester1@email.com'
                ],
                [
                    'name' => 'Tester2',
                    'email' => 'tester2@email.com'
                ]
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', 'users[]');
        $result = $transformer->createData(['data' => $this->users]);

        $this->assertEquals($result, [
            'users' => [
                [
                    'name' => 'Tester',
                    'email' => 'tester1@email.com'
                ],
                [
                    'name' => 'Tester2',
                    'email' => 'tester2@email.com'
                ]
            ]
        ]);

    }


    public function testExceptions()
    {
        try {

            $transformer = new ObjectMethodsUserTransformer('test');
            $transformer->createData($this->user);

            $this->assertTrue(false);
        } catch (UndefinedItemPropertyException $e) {
            $this->assertTrue(true);
        }
    }


}