<?php

/**
* Simple XML Reader
*
* @license Public Domain
* @author Dmitry Pyatkov(aka dkrnl) <dkrnl@yandex.ru>
* @url http://github.com/dkrnl/SimpleXMLReader
*/
class SimpleXMLReader extends XMLReader
{

    /**
     * Callbacks
     *
     * @var array
     */
    protected $callback = array();

    /**
     * Add node callback
     *
     * @param  string   $name
     * @param  callback $callback
     * @param  integer  $nodeType
     * @return SimpleXMLReader
     */
    public function registerCallback($name, $callback, $nodeType = XMLREADER::ELEMENT)
    {
        if (isset($this->callback[$nodeType][$name])) {
            throw new Exception("Already exists callback $name($nodeType).");
        }
        if (!is_callable($callback)) {
            throw new Exception("Already exists parser callback $name($nodeType).");
        }
        $this->callback[$nodeType][$name] = $callback;
        return $this;
    }

    /**
     * Remove node callback
     *
     * @param  string  $name
     * @param  integer $nodeType
     * @return SimpleXMLReader
     */
    public function unRegisterCallback($name, $nodeType = XMLREADER::ELEMENT)
    {
        if (!isset($this->callback[$nodeType][$name])) {
            throw new Exception("Unknow parser callback $name($nodeType).");
        }
        unset($this->callback[$nodeType][$name]);
        return $this;
    }

    /**
     * Run parser
     *
     * @return void
     */
    public function parse()
    {
        if (empty($this->callback)) {
            throw new Exception("Empty parser callback.");
        }
        $continue = true;
        while ($continue && $this->read()) {
            if (isset($this->callback[$this->nodeType][$this->name])) {
                $continue = call_user_func($this->callback[$this->nodeType][$this->name], $this);
            }
        }
    }

    /**
     * Run XPath query on current node
     *
     * @param  string $path
     * @param  string $version
     * @param  string $encoding
     * @return array(SimpleXMLElement)
     */
    public function expandXpath($path, $version = "1.0", $encoding = "UTF-8")
    {
        return $this->expandSimpleXml($version, $encoding)->xpath($path);
    }

    /**
     * Expand current node to string
     *
     * @param  string $version
     * @param  string $encoding
     * @return SimpleXMLElement
     */
    public function expandString($version = "1.0", $encoding = "UTF-8")
    {
        return $this->expandSimpleXml($version, $encoding)->asXML();
    }

    /**
     * Expand current node to SimpleXMLElement
     *
     * @param  string $version
     * @param  string $encoding
     * @param  string $className
     * @return SimpleXMLElement
     */
    public function expandSimpleXml($version = "1.0", $encoding = "UTF-8", $className = null)
    {
        $element = $this->expand();
        $document = new DomDocument($version, $encoding);
        $node = $document->importNode($element, true);
        $document->appendChild($node);
        return simplexml_import_dom($node, $className);
    }

    /**
     * Expand current node to DomDocument
     *
     * @param  string $version
     * @param  string $encoding
     * @return DomDocument
     */
    public function expandDomDocument($version = "1.0", $encoding = "UTF-8")
    {
        $element = $this->expand();
        $document = new DomDocument($version, $encoding);
        $node = $document->importNode($element, true);
        $document->appendChild($node);
        return $document;
    }

}
?>
up
down
8godseth at o2 dot pl ¶11 years ago
Thanks rein_baarsma33 AT hotmail DOT com for bugfixes.

This is my new child of XML parsing method  based on my and yours modification.

XML2ASSOC Is a complete solution for parsing ordinary XML

<?php
/**
* XML2Assoc Class to creating
* PHP Assoc Array from XML File
*
* @author godseth (AT) o2.pl & rein_baarsma33 (AT) hotmail.com (Bugfixes in parseXml Method)
* @uses XMLReader
*
*/

class Xml2Assoc {

    /**
     * Optimization Enabled / Disabled
     *
     * @var bool
     */
    protected $bOptimize = false;

    /**
     * Method for loading XML Data from String
     *
     * @param string $sXml
     * @param bool $bOptimize
     */

    public function parseString( $sXml , $bOptimize = false) {
        $oXml = new XMLReader();
        $this -> bOptimize = (bool) $bOptimize;
        try {

            // Set String Containing XML data
            $oXml->XML($sXml);

            // Parse Xml and return result
            return $this->parseXml($oXml);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Method for loading Xml Data from file
     *
     * @param string $sXmlFilePath
     * @param bool $bOptimize
     */
    public function parseFile( $sXmlFilePath , $bOptimize = false ) {
        $oXml = new XMLReader();
        $this -> bOptimize = (bool) $bOptimize;
        try {
            // Open XML file
            $oXml->open($sXmlFilePath);

            // // Parse Xml and return result
            return $this->parseXml($oXml);

        } catch (Exception $e) {
            echo $e->getMessage(). ' | Try open file: '.$sXmlFilePath;
        }
    }

    /**
     * XML Parser
     *
     * @param XMLReader $oXml
     * @return array
     */
    protected function parseXml( XMLReader $oXml ) {

        $aAssocXML = null;
        $iDc = -1;

        while($oXml->read()){
            switch ($oXml->nodeType) {

                case XMLReader::END_ELEMENT:

                    if ($this->bOptimize) {
                        $this->optXml($aAssocXML);
                    }
                    return $aAssocXML;

                case XMLReader::ELEMENT:

                    if(!isset($aAssocXML[$oXml->name])) {
                        if($oXml->hasAttributes) {
                            $aAssocXML[$oXml->name][] = $oXml->isEmptyElement ? '' : $this->parseXML($oXml);
                        } else {
                            if($oXml->isEmptyElement) {
                                $aAssocXML[$oXml->name] = '';
                            } else {
                                $aAssocXML[$oXml->name] = $this->parseXML($oXml);
                            }
                        }
                    } elseif (is_array($aAssocXML[$oXml->name])) {
                        if (!isset($aAssocXML[$oXml->name][0]))
                        {
                            $temp = $aAssocXML[$oXml->name];
                            foreach ($temp as $sKey=>$sValue)
                            unset($aAssocXML[$oXml->name][$sKey]);
                            $aAssocXML[$oXml->name][] = $temp;
                        }

                        if($oXml->hasAttributes) {
                            $aAssocXML[$oXml->name][] = $oXml->isEmptyElement ? '' : $this->parseXML($oXml);
                        } else {
                            if($oXml->isEmptyElement) {
                                $aAssocXML[$oXml->name][] = '';
                            } else {
                                $aAssocXML[$oXml->name][] = $this->parseXML($oXml);
                            }
                        }
                    } else {
                        $mOldVar = $aAssocXML[$oXml->name];
                        $aAssocXML[$oXml->name] = array($mOldVar);
                        if($oXml->hasAttributes) {
                            $aAssocXML[$oXml->name][] = $oXml->isEmptyElement ? '' : $this->parseXML($oXml);
                        } else {
                            if($oXml->isEmptyElement) {
                                $aAssocXML[$oXml->name][] = '';
                            } else {
                                $aAssocXML[$oXml->name][] = $this->parseXML($oXml);
                            }
                        }
                    }

                    if($oXml->hasAttributes) {
                        $mElement =& $aAssocXML[$oXml->name][count($aAssocXML[$oXml->name]) - 1];
                        while($oXml->moveToNextAttribute()) {
                            $mElement[$oXml->name] = $oXml->value;
                        }
                    }
                    break;
                case XMLReader::TEXT:
                case XMLReader::CDATA:

                    $aAssocXML[++$iDc] = $oXml->value;

            }
        }

        return $aAssocXML;
    }

    /**
     * Method to optimize assoc tree.
     * ( Deleting 0 index when element
     *  have one attribute / value )
     *
     * @param array $mData
     */
    public function optXml(&$mData) {
        if (is_array($mData)) {
            if (isset($mData[0]) && count($mData) == 1 ) {
                $mData = $mData[0];
                if (is_array($mData)) {
                    foreach ($mData as &$aSub) {
                        $this->optXml($aSub);
                    }
                }
            } else {
                foreach ($mData as &$aSub) {
                    $this->optXml($aSub);
                }
            }
        }
    }

}

?>