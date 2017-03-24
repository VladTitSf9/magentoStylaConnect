<?php

/**
 * Class Styla_Connect_Model_Styla_Api_Cache
 *
 */
class Styla_Connect_Model_Styla_Api_Cache
{
    const CACHE_TAG   = 'STYLA_CONNECT';
    const CACHE_GROUP = 'styla_connect';

    protected $_cache;

    /** @var Styla_Connect_Model_Styla_Api */
    protected $_api;

    /** @var boolean */
    protected $enabled;


    /**
     * Is this cache type enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->enabled === null) {
            $this->enabled = Mage::app()->useCache(self::CACHE_GROUP);
        }

        return $this->enabled;
    }

    /**
     * @param            $data
     * @param null       $id
     * @param array      $tags
     * @param bool|false $specificLifetime
     * @param int        $priority
     */
    public function save($data, $id = null, $tags = array(), $specificLifetime = false, $priority = 8)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $tags[] = self::CACHE_TAG;
        $tags   = array_unique($tags);

        if ($specificLifetime === false) {
            $specificLifetime = $this->getCacheLifetime();
        }

        $this->getCache()->save($data, $id, $tags, $specificLifetime, $priority);
    }

    /**
     * @param            $id
     * @param bool|false $doNotTestCacheValidity
     * @param bool|false $doNotUnserialize
     * @return false|mixed
     */
    public function load($id, $doNotTestCacheValidity = false, $doNotUnserialize = false)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->getCache()->load($id, $doNotTestCacheValidity, $doNotUnserialize);
    }

    /**
     *
     * @return Styla_Connect_Model_Styla_Api
     */
    public function getApi()
    {
        if (!$this->_api) {
            $this->_api = Mage::getSingleton('styla_connect/styla_api');
        }

        return $this->_api;
    }

    /**
     * Store the api response in cache, if possible
     *
     * @param Styla_Connect_Model_Styla_Api_Request_Type_Abstract  $request
     * @param Styla_Connect_Model_Styla_Api_Response_Type_Abstract $response
     * @param bool|int                                             $specificLifetime
     */
    public function storeApiResponse($request, $response, $specificLifetime = false)
    {
        if (!$this->isEnabled() || $response->getHttpStatus() !== 200) {
            return;
        }

        $cachedData = serialize($response->getRawResult());
        $cacheKey   = $this->getCacheKey($request);

        $this->save($cachedData, $cacheKey, array(), $specificLifetime);
    }

    public function getCacheLifetime()
    {
        return Mage::helper('styla_connect/config')->getCacheLifetime();
    }

    public function getApiVersion()
    {
        return $this->getApi()->getCurrentApiVersion();
    }

    /**
     *
     * @param Styla_Connect_Model_Styla_Api_Request_Type_Abstract $request
     * @return string
     */
    public function getCacheKey($request)
    {
        $key = implode(
            '.',
            [
                Mage::app()->getStore()->getId(),
                $request->getRequestType(),
                $request->getRequestPath(),
                $this->getApiVersion()
            ]
        );

        return $key;
    }

    /**
     * If possible, load a cached response
     *
     * @param Styla_Connect_Model_Styla_Api_Request_Type_Abstract $request
     * @return boolean|Styla_Connect_Model_Styla_Api_Response_Type_Abstract
     */
    public function getCachedApiResponse($request)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $key    = $this->getCacheKey($request);
        $cached = $this->load($key);
        if (!$cached) {
            return false;
        }

        //rebuild the response object
        $response = $this->getApi()->getResponse($request);
        $response->setHttpStatus(200);
        $response->setRawResult(unserialize($cached));

        return $response;
    }

    /**
     *
     * @return Zend_Cache_Core
     */
    protected function getCache()
    {
        if (!$this->_cache) {
            $this->_cache = Mage::app()->getCache();
        }

        return $this->_cache;
    }

}
