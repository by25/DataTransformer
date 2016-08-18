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
    private $mappingOptions = [
        'field' => null,
        'required' => false
    ];


    /**
     * Опции трансофрмации
     * @var array
     */
    private $transformOptions = [];


    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];


    /**
     * Transformer constructor.
     * @param null|string $property Свойство, по которому будет происходить выборка значения для последующей трансформации
     * @param array $mapping Конфигурация маппинга $key=>$value.
     *
     * Доступные ключи конфигурации:
     *  - `field`  Название ключа массива, на который будет присвоен результат трансформации:
     *      - string - Название ключа
     *      - null - Автоматически вычислить. Если коллекция, то значение $property иначе объединиться с корневым масивом
     *      - false - объединение с корневым масивом
     *
     *  - `required` - Проверка наличия $property (выкидывается исключение)
     *
     * @param array $option Настройки трансофрмации объекта, описываются в Transformer::getDefaultOptions()
     *
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($property = null, array $mapping = [], array $option = [])
    {
        $this->property = $property;

        // Mapping configuration

        foreach ($mapping as $key => $value) {
            if (!array_key_exists($key, $this->mappingOptions)) {
                throw new \InvalidArgumentException(sprintf('Undefined option "%s"', $key));
            }

            $this->mappingOptions[$key] = $value;
        }

        // Transform Options

        $this->transformOptions = $this->defaultOptions();
        $keysOptions = array_keys($this->transformOptions);

        foreach ($option as $key => $value) {
            if (!in_array($key, $keysOptions, true)) {
                throw new \InvalidArgumentException(
                    sprintf('Unknown options "%s". Define option in method Transformer::getDefaultOptions', $key)
                );
            }
            $this->transformOptions[$key] = $value;
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
    public function getMappingOptions()
    {
        return $this->mappingOptions;
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

        $result = $this->transform($rawData);

        foreach ($this->transformers as $childTransformers) {
            $childData = $childTransformers->execute($rawData);
            if ($childData) {
                $result += $childData;
            }
        }

        return $this->returnMappedData($result);
    }


    /**
     * Опции по умолчанию (key => value)
     *
     * @return array
     */
    protected function defaultOptions()
    {
        return [];
    }


    /**
     * Получить опцию трансофрмации
     *
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function getOption($key)
    {
        if (!array_key_exists($key, $this->transformOptions)) {
            throw new \InvalidArgumentException(
                sprintf('Undefined option "%s"', $key)
            );
        }
        return $this->transformOptions[$key];
    }


}