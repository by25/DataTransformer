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
     *
     * @return $this
     */
    public function addTransformer($property, TransformerInterface $transformer);

    /**
     * Возвращает вложенные трансформеры
     *
     * @return TransformerInterface[]
     */
    public function getTransformers();


    /**
     * @param array $map [$result_key => $property]
     * @return $this
     */
    public function setBindingMap(array $map = []);


    /**
     * Схема связывания внеших странсформеров к ключам нормализованного массива
     *
     * @return array
     */
    public function getBindingMap();


    /**
     * Трансформировать значение
     *
     * @param mixed $item
     * @return array
     */
    public function transform($item);


}