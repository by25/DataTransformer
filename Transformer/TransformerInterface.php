<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;

use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;

interface TransformerInterface
{

    /**
     * TransformerInterface constructor.
     * @param null|string $property Ключ массива или метод объекта входных данных, если null - обрабатывается весь набор данных
     */
    public function __construct($property = null);


    public function getProperty();

    /**
     * Трансформировать значение
     *
     * @param mixed $item
     * @return array
     */
    public function transform($item);

    /**
     * Трансформация данных
     *
     * @param $resource
     * @param bool $isCollection
     * @return array
     *
     * @throws UndefinedItemPropertyException
     */
    public function createData($resource, $isCollection);


}