<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;

interface TransformerInterface
{

    /**
     * Свойство, по которому будет происходить выборка значения для последующей трансформации
     *
     * @return string
     */
    public function getProperty();

    /**
     * Трансформировать занчение
     *
     * @param mixed $resource
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function transform($resource);

    /**
     * Добавить трансформер
     *
     * @param TransformerInterface $transformer
     */
    public function add(TransformerInterface $transformer);

    /**
     * Добавить трансформер для коллекции.
     * Аналог `Transformer::add(new Collection($transformer))`
     *
     * @param TransformerInterface $transformer
     */
    public function addCollection(TransformerInterface $transformer);


    /**
     * Опции
     *
     * @return array
     */
    public function getMappingOptions();

    /**
     * Вложенные трансформеры
     *
     * @return TransformerInterface[]
     */
    public function getTransformers();

    /**
     * @param $resource
     * @return array|null
     *
     * @throws \InvalidArgumentException
     */
    public function execute($resource);


}