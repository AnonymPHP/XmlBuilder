<?php

namespace  Cable\Xml\Builder;


class XmlContainer
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $name;

    /**
     *
     * XmlContainer constructor.
     * @param array $attributes
     * @param string $name
     * @param null $data
     */
    public function __construct($attributes = [], $name = '', $data = null)
    {
        $this->setAttributes($attributes)
            ->setData($data)
            ->setName($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return XmlContainer
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return XmlContainer
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return XmlContainer
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

}
