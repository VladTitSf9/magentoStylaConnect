<?php

/**
 * Class Styla_Connect_Model_Styla_Api_Request_Type_Abstract
 *
 */
abstract class Styla_Connect_Model_Styla_Api_Request_Type_Abstract
{
    protected $_requestPath;
    protected $_requestType;
    protected $_params;

    protected $_connectionType = Zend_Http_Client::GET;
    
    protected $_requestTimeout;
    protected $_requestConnectTimeout;
    
    /**
     *
     * @return string
     */
    abstract public function getApiUrl();

    /**
     * Initialize this request with data to pass on to the api service
     *
     * @param string $requestPath
     * @param null   $params
     * @return Styla_Connect_Model_Styla_Api_Request_Type_Abstract
     */
    public function initialize($requestPath, $params = null)
    {
        $this->_requestPath = $requestPath;
        $this->_params      = $params;

        return $this;
    }

    /**
     * Get request params (used for POST-type requests)
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
    }

    /**
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Get the curl connection type - GET/POST
     *
     * @return string
     */
    public function getConnectionType()
    {
        return $this->_connectionType;
    }

    /**
     * Set the curl connection type - GET/POST
     *
     * @param  $type
     */
    public function setConnectionType($type)
    {
        $this->_connectionType = $type;
    }

    /**
     * Get the request path of this request
     *
     * @param bool $urlEncoded
     * @return string $requestPath
     */
    public function getRequestPath($urlEncoded = false)
    {
        $requestPath = $this->_requestPath;
        if ($urlEncoded) {
            $requestPath = urlencode($requestPath);
        }

        return $requestPath;
    }

    /**
     * Get the type name of this request
     *
     * @return string
     */
    public function getRequestType()
    {
        return $this->_requestType;
    }

    /**
     * Get the class type name for the response type for this request
     *
     * @return string
     */
    public function getResponseType()
    {
        return $this->_requestType;
    }

    /**
     *
     * @return Styla_Connect_Model_Styla_Api
     */
    public function getApi()
    {
        return Mage::getSingleton('styla_connect/styla_api');
    }

    /**
     *
     * @return Styla_Connect_Helper_Config
     */
    public function getConfigHelper()
    {
        return Mage::helper('styla_connect/config');
    }
    
    /**
     * Get the connection timeout settings for this request.
     * 
     * @return false|array
     */
    public function getConnectionTimeoutOptions()
    {
        $options = array();
        
        if(null !== $this->_requestConnectTimeout) {
            $options[CURLOPT_CONNECTTIMEOUT] = $this->_requestConnectTimeout;
        }
        if(null !== $this->_requestTimeout) {
            $options[CURLOPT_TIMEOUT] = $this->_requestTimeout;
        }
        
        return empty($options) ? false : $options;
    }
}