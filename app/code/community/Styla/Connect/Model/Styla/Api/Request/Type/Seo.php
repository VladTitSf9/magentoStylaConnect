<?php

/**
 * Class Styla_Connect_Model_Styla_Api_Request_Type_Seo
 */
class Styla_Connect_Model_Styla_Api_Request_Type_Seo extends Styla_Connect_Model_Styla_Api_Request_Type_Abstract
{
    const API_URL_SEO = '%s/clients/%s?url=%s';

    protected $_requestType = Styla_Connect_Model_Styla_Api::REQUEST_TYPE_SEO;

    public function getApiUrl()
    {
        $apiUrl = self::API_URL_SEO;

        $apiBaseUrl  = $this->getConfigHelper()->getApiSeoUrl();
        $clientName  = $this->getConfigHelper()->getUsername();
        $requestPath = $this->getRequestPath();

        if (strlen($requestPath) > 1) {
            $requestPath = rtrim($requestPath, '/');
        }

        $apiUrl = sprintf($apiUrl, $apiBaseUrl, $clientName, $requestPath);

        return $apiUrl;
    }
}