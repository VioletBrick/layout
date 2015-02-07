<?php
/** {license_text}  */
namespace Layout\Element\Type;

use Core\Support\Fluent;
use Illuminate\Support\Collection;
use Layout\Element\Output\OutputInterface as ElementOutputInterface;

abstract class TypeAbstract
    implements TypeInterface
{
    /** @var  ElementOutputInterface */
    protected $outputModel;

    /**
     * @param DataTransportPublic $publicData
     * @param DataTransportProtected $protectedData
     * @return mixed
     */
    abstract protected function process(DataTransportPublic $publicData, DataTransportProtected $protectedData);

    /**
     * @param ElementOutputInterface $output
     */
    final public function setOutputModel(ElementOutputInterface $output)
    {
        $this->outputModel = $output;
    }

    /**
     * @param DataTransportPublic $publicData
     * @param DataTransportProtected $protectedData
     * @param DataTransportChildren $childrenOutput
     * @return mixed
     */
    final public function processOutput(DataTransportPublic $publicData, DataTransportProtected $protectedData, DataTransportChildren $childrenOutput)
    {
        $this->process($publicData, $protectedData);
        
        $output = $this->outputModel;

        if ($data = $this->prepareData($publicData->getAttributes())) {
            $output->setPublicData($data);
        } else {
            $output->setPublicData([]);
        }

        if ($data = $this->prepareData($protectedData)) {
            $output->setProtectedAttributes($data);
        } else {
            $output->setProtectedAttributes([]);
        }

        foreach ($childrenOutput->getAttributes() as $name => $childOutput) {
            $output->addChildOutputResult($name, $childOutput);
        }
        
        return $output->processOutput();
    }

    /**
     * @param $data
     * @return Fluent|null
     */
    protected function prepareData($data)
    {
        if (is_object($data)) {
            if ($data instanceof Collection) {
                $data = $data->all();
            } else {
                $class = get_class($data);
                // Ignore fluent objects
                if ($class != 'Core\Support\Fluent' && $class != 'Illuminate\Support\Fluent') {
                    if (method_exists($data, 'toArray')) {
                        $data = $data->toArray();
                    }
                }
            }
        }
        
        if (is_array($data) || $data instanceof Fluent || $data instanceof \Illuminate\Support\Fluent) {
            foreach ($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $data[$key] = $this->prepareData($value);
                } else {
                    $data[$key] = $value;
                }
            }
            
            return $data;
        }
        
        return null;
    }

    /**
     * @return null
     */
    public function __invoke()
    {
        return null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
