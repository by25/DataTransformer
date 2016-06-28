<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;

use Itmedia\DataTransformer\Exception\UndefinedItemPropertyException;

interface TransformerInterface
{


    public function getProperty();

    /**
     * Трансформировать значение
     *
     * @param mixed $resource
     * @return array
     */
    public function transform($resource);


    public function add(TransformerInterface $transformer);

    public function addCollection(TransformerInterface $transformer);


    public function getOptions();

    public function getTransformers();

    public function execute($resource);


}