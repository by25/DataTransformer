<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Provider;


use Itmedia\DataTransformer\Provider\TransformProvider;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayGroupTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ArrayUserTransformer;
use Itmedia\DataTransformer\Tests\Stub\Transformer\ObjectMethodsUserTransformer;
use Itmedia\DataTransformer\Transformer\Collection;
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

        $resource = [
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

        $transformer = new ArrayUserTransformer();
        $transformer->add(new ObjectMethodsUserTransformer('friend', ['field' => 'my-friend']));
        $transformer->addCollection(new ArrayGroupTransformer('user_group', ['field' => 'groups']));

        $provider = new TransformProvider($resource, $transformer);


        $result = $provider->createData();

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com',
            'my-friend' => [
                'name' => 'Andrey',
                'email' => 'Andrey@email.com',
            ],
            'groups' => [
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


    public function testCollectionTransform()
    {
        $resource = [
            [
                'user_name' => 'Tester',
                'user_email' => 'email@email.com',
                'user_group' => [
                    [
                        'group_id' => 1,
                        'group_name' => 'User'
                    ],
                    [
                        'group_id' => 2,
                        'group_name' => 'Manager'
                    ]
                ],
                'client' => [
                    'user_name' => 'Client',
                    'user_email' => 'client@email.com',
                ]

            ],
            [
                'user_name' => 'Tester2',
                'user_email' => 'email2@email.com',
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
            ]
        ];


        $itemTransformer = new ArrayUserTransformer();
        $itemTransformer->add(new ArrayUserTransformer('client', ['required' => false]));
        $itemTransformer->addCollection(new ArrayGroupTransformer('user_group', ['field' => 'groups']));
        $transformer = new Collection($itemTransformer);

        $provider = new TransformProvider($resource, $transformer);

        $result = $provider->createData();

        $this->assertEquals($result, [
            [
                'name' => 'Tester',
                'email' => 'email@email.com',
                'groups' => [
                    [
                        'id' => 1,
                        'name' => 'User'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Manager'
                    ]
                ],
                'client' => [
                    'name' => 'Client',
                    'email' => 'client@email.com',
                ]

            ],
            [
                'name' => 'Tester2',
                'email' => 'email2@email.com',
                'groups' => [
                    [
                        'id' => 1,
                        'name' => 'User'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Manager'
                    ]
                ],
                'client' => [
                    'name' => null,
                    'email' => null,
                ]
            ]
        ]);
    }


}