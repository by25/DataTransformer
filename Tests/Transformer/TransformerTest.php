<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Tests\Transformer;


use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;
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
        $result = $transformer->map($this->user);

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

        $result = $transformer->map($user);
        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);
    }


    public function testOnceExecute()
    {
        $transformer = new ArrayUserTransformer();
        $result = $transformer->execute($this->user);

        $this->assertEquals($result, [
            'name' => 'Tester',
            'email' => 'email@email.com'
        ]);


        $transformer = new ArrayUserTransformer('data');
        $result = $transformer->execute([
            'data' => $this->user
        ]);

        $this->assertEquals($result, [
            'data' => [
                'name' => 'Tester',
                'email' => 'email@email.com'
            ]
        ]);


        $transformer = new ArrayUserTransformer('data', ['field' => false]);
        $result = $transformer->execute([
            'data' => $this->user
        ]);

        $this->assertEquals($result, [
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