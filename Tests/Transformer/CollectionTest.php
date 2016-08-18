<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Transformer;


use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\DataTransformer\Transformer\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    private $users = [
        [
            'user_name' => 'Tester',
            'user_email' => 'tester1@email.com'
        ],
        [
            'user_name' => 'Tester2',
            'user_email' => 'tester2@email.com'
        ],
        [
            'user_name' => 'Tester-ignored',
            'user_email' => 'tester2@email.com',
            'ignore_me' => 'bla-bla'
        ],
    ];


    public function testExecute()
    {
        $transformer = new Collection(new ArrayUserTransformer());
        $result = $transformer->execute($this->users);

        self::assertEquals($result, [
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

    public function testExecuteFieldMapping()
    {
        $transformer = new Collection(new ArrayUserTransformer('data', ['field' => false]));
        $result = $transformer->execute(['data' => $this->users]);

        self::assertEquals($result, [
            [
                'name' => 'Tester',
                'email' => 'tester1@email.com'
            ],
            [
                'name' => 'Tester2',
                'email' => 'tester2@email.com'
            ]
        ]);


        $transformer = new Collection(new ArrayUserTransformer('data'));
        $result = $transformer->execute(['data' => $this->users]);

        self::assertEquals($result, [
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


        $transformer = new Collection(new ArrayUserTransformer('data', ['field' => 'my-field']));
        $result = $transformer->execute(['data' => $this->users]);

        self::assertEquals($result, [
            'my-field' => [
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

}