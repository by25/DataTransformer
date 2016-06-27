<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Provider;


use Itmedia\DataTransformer\Transformer\TransformerInterface;

interface TransformProviderInterface
{

    public function __construct($resource);


    public function addTransformer(TransformerInterface $transformer, $field = null);


    public function addCollectionTransformer(TransformerInterface $transformer, $field = null);


    public function getArray();
}