<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\ArrayTransformer\Transformer;


interface TransformerInterface
{

    /**
     * Добавить вложенный трансформер
     *
     * @param string|null $property Имя ресурса из которого будут извлечены данные для трасформации (ключ массива, название метода, название свойсва)
     * @param TransformerInterface $transformer
     */
    public function addTransformer($property, TransformerInterface $transformer);

    /**
     * Возвращает вложенные трансформеры
     *
     * @return TransformerInterface[]
     */
    public function getTransformers();


    /**
     * Трансформировать значение
     *
     * @param mixed $item
     * @return array
     */
    public function transform($item);


    /**
     * Название поля массива, если этот трансформер включен в другой трансформер
     * @return string|null
     */
    public function getIncludeKey();


}