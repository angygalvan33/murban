<?php
class Banxico
{
    const banxicourl = 'http://www.banxico.org.mx:80/DgieWSWeb/DgieWS?WSDL';
    private $_client;
    private $_debug = false;
    private $resultado;
    
    public function __construct() {
        
    }

    public function getExRate()
    {
        $this->resultado = array();
        $client = $this->_getClient();
        try
        {
            $result = $client->tiposDeCambioBanxico();
        }
        catch (SoapFault $e)
        {
            $this->resultado['error'] = 1;
            $this->resultado['result'] = $e->getMessage();
            //return $e->getMessage();
        }
        if(!empty($result))
        {
            $dom = new DOMDocument();
            $dom->loadXML($result);
            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('bm', "http://ws.dgie.banxico.org.mx");
            $val = $xpath->evaluate("//*[@IDSERIE='SF60653']/*/@OBS_VALUE");
            
            $this->resultado['error'] = 0;
            $this->resultado['result'] = $val->item(0)->value;
            
            //return ($val->item(0)->value);
        }
        return json_encode($this->resultado);
    }
    /**
     * @return SoapClient
     */
    private function _getClient()
    {
        if(empty($this->_client)) {
            $this->_client = $this->_setClient();
        }
        return $this->_client;
    }
    /**
     * @return SoapClient
     */
    private function _setClient()
    {
        return new SoapClient(null,
            array('location' => self::banxicourl,
            'uri'      => 'http://DgieWSWeb/DgieWS?WSDL',
            'encoding' => 'ISO-8859-1',
            'trace'    => $this->_getDebug()
            ));
    }
    private function _getDebug()
    {
        return $this->_debug;
    }
}