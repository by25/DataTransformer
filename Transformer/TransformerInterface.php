<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;


interface TransformerInterface
{


    /**
     * TransformerInterface constructor.
     * @param null|string $inputProperty Ключ массива или метод объекта входных данных, если null - обрабатывается весь набор данных
     * @param null|string $outputKey На какой ключ вызодного массива замапить данные, если null - то маппим в корень (array_merge).
     *                                      Поле может включать "[]", например "groups[]". В этом случае данные будут обработаны как коллекция
     *                                      и присвоемы в к ключу "groups"
     * @param TransformerInterface[] $transformers Вложенные трансформеры для обработки вложенных данных
     */
    public function __construct($inputProperty = null, $outputKey = null, array $transformers = []);


    /**
     * Ключ массива или метод объекта входных данных, если null - обрабатывается весь набор данных
     *
     * @return string|null
     */
    public function getProperty();

    /**
     * На какой ключ вызодного массива замапить данные, если null - то маппим в корень (array_merge).
     *
     * @return string|null
     */
    public function getOutputKey();


    /**
     * Добавить вложенный трансформер
     * @param TransformerInterface $transformer
     *
     * @return $this
     */
    public function addTransformer(TransformerInterface $transformer);

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
    public function transformItem($item);

    /**
     * Трансформация данных
     *
     * @param $item
     * @return array
     */
    public function createData($item);


}