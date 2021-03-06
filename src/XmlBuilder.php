<?php

namespace Cable\Xml\Builder;

/**
 * Class XmlBuilder
 * @package Cable\Xml\Builder
 */
class XmlBuilder
{
    /**
     * @var string
     */
    private $encoding;

    /**
     * @var string
     */
    private $version;

    /**
     * @var XmlContainer
     */
    private $container;

    /**
     * XmlBuilder constructor.
     * @param string $version
     * @param string $encoding
     * @param null $container
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8', $container = null)
    {
        $this->version = $version;
        $this->encoding = $encoding;
        $this->container = $container;
    }

    public function createXml()
    {
        $dom = new \DOMDocument($this->version, $this->encoding);
        $element = $dom->createElement($this->container->getName());

        foreach ($this->container->getAttributes() as $name => $attribute) {
            $element->setAttribute($name, $attribute);
        }

        $dom->appendChild($element);
        $container = $dom->saveXML();

        $xml = new \SimpleXMLElement($container);

        $this->arrayToXml($this->container->getData(), $xml);

        $domOutput = new \DOMDocument();
        $domOutput->formatOutput = true;

        $domOutput->loadXML($xml->asXML());

        return $domOutput->saveXML();
    }

    private function hasStringKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    private function arrayToXml($data, \SimpleXMLElement $element, $preferedKey = null)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = $preferedKey ? $preferedKey : 'item' . $key; //dealing with <0/>..<n/> issues
            }
            if (is_array($value)) {

                $attributes = [];

                if (isset($value['@data'])) {
                    $attributes = $value['@attributes'];
                    $value = $value['@data'];
                }

                if (!$this->hasStringKeys($value)) {
                    $this->arrayToXml($value, $element, $key);
                } else {
                    $subnode = $element->addChild($key);

                    if (!empty($attributes)) {
                        $this->setAttributes($subnode, $attributes);
                    }

                    $this->arrayToXml($value, $subnode);
                }

            } else {
                $element->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * @param \SimpleXMLElement $node
     * @param array $attributes
     */
    public function setAttributes(\SimpleXMLElement $node, array $attributes)
    {

        if (isset($attributes['@namespace'])) {
            $namespace = $attributes['@namespace'];
            unset($attributes['@namespace']);
        } else {
            $namespace = null;
        }



        array_walk($attributes, function ($value, $key) use ($node, $namespace) {
            $node->addAttribute($key, $value, $namespace);
        });
    }


    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param string $encoding
     * @return XmlBuilder
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return XmlBuilder
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return XmlContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param XmlContainer $container
     * @return XmlBuilder
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

}
