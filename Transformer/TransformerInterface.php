<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;

interface TransformerInterface
{

    /**
     * @return string
     */
    public function getProperty();

    /**
     * Карта трансформации значения
     *
     * @param mixed $resource
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function map($resource);

    /**
     * @param TransformerInterface $transformer
     */
    public function add(TransformerInterface $transformer);

    /**
     * @param TransformerInterface $transformer
     */
    public function addCollection(TransformerInterface $transformer);


    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return TransformerInterface[]
     */
    public function getTransformers();

    /**
     * @param $resource
     * @param $strict
     * @return array|null
     *
     * @throws \InvalidArgumentException
     */
    public function execute($resource, $strict);


}