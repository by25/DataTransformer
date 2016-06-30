<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\DataTransformer\Transformer;

abstract class Transformer extends AbstractTransformer
{

    /**
     * @var string|null
     */
    private $property;

    /**
     * @var array
     */
    private $options = [
        'field' => null,
        'required' => false
    ];


    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];


    /**
     * Transformer constructor.
     * @param null|string $property Свойство, по которому будет происходить выборка значения для последующей трансформации
     * @param array $options Опции $key=>$value.
     *
     * Доступные опции:
     *  - `field`  Название ключа массива, на который будет присвоен результат трансформации:
     *      - string - Название ключа
     *      - null - Автоматически вычислить. Если коллекция, то значение $property иначе объединиться с корневым масивом
     *      - false - объединение с корневым масивом
     *
     *  - `required` - Проверка наличия $property (выкидывается исключение)
     *
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($property = null, array $options = [])
    {
        $this->property = $property;

        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                throw new \InvalidArgumentException(sprintf('Undefined option "%s"', $key));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }


    /**
     * {@inheritdoc}
     */
    public function addCollection(TransformerInterface $transformer)
    {
        $this->transformers[] = new Collection($transformer);
    }


    /**
     * {@inheritdoc}
     */
    public function getTransformers()
    {
        return $this->transformers;
    }


    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->property;
    }


    /**
     * {@inheritdoc}
     */
    public function execute($resource)
    {
        $rawData = $this->fetchDataProperty($resource, $this);

        if (!$rawData) {
            return null;
        }

        $result = $this->map($rawData);

        foreach ($this->transformers as $childTransformers) {
            $childData = $childTransformers->execute($rawData);
            if ($childData) {
                $result += $childData;
            }
        }

        return $this->returnMappedData($result);
    }


}