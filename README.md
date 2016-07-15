DataTransformer
---------------

[![Build Status](https://travis-ci.org/by25/DataTransformer.svg?branch=master)](https://travis-ci.org/by25/DataTransformer)


Библиотека для трансформации данных в массивы, на основании предопределенной схемы (Transformer).

Install
-------

```
composer require itmedia/data-transformer
```


Пример использования
--------------------

#### Трансформеры:

Трансформер должен быть отнаследован от `Itmedia\DataTransformer\Transformer\Transformer`
и реализован метод `map($resource)`:


Можно строить карту трансформации как массивов, так и объектов.

```php
class UserTransformer extends Transformer
{
    public function map($resource)
    {
        return [
            'name' => $resource['user_name'],
            'email' => $resource['user_email']
        ];
    }

}

class GroupTransformer extends Transformer
{
    public function map($resource)
    {
        return [
            'id' => $resource['group_id'],
            'name' => $resource['group_name']
        ];
    }

}

```

#### Трансформация данных:


```php

$resource = [
    'user_name' => 'Tester',
    'user_email' => 'email@email.com',
    'password' => 'mypass',
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


$transformer = new UserTransformer();
$transformer->addCollection(new GroupTransformer('user_group', ['field' => 'groups']));

$transformProvider = new TransformProvider();

$result = $transformProvider->transformItem($resource, $transformer);
```

Результат:

```
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
    ]
]
```


Опции и варианты трансформации
------------------------------

Трансформеры могут в себя включать другие трансформеры.

```
$transformer = new UserTransformer();
$transformer->add(new UserTransformer()); // Обработка одного элемента
$transformer->addCollection(new GroupTransformer($property, $options)); // Обработка коллекции элементов
```

Где:

 - `$property` - Свойство, по которому будет происходить выборка значения для последующей трансформации. Может быть
 как ключем массива, так и названием метода объекта (default: null)

 - `$options` - Опции трансформера.


Доступные опции трансформера:

- `field`  Название ключа массива, на который будет присвоен результат трансформации (default: null):
    - string - название ключа;
    - null   - автоматически вычислить. Если коллекция, то значение $property иначе объединиться с корневым масивом;
    - false  - объединение с корневым масивом.

- `required` - Проверить существования данных по $property (выкидывается исключение) (default: false).



### Трансформация данных:

```php
$options = [
    'root_key' => 'data'
];
$transformProvider = new TransformProvider($options);
```

Опции:

- `root_key` - Обернуть возвращаемый массив в ключ root_key. (default: null)


Трансформация одного элемента:

```php
$transformProvider->transformItem($resource, $transformer);
```

Трансформация коллекции элементов:

```php
$transformProvider->transformCollection($resource, $transformer);
```



