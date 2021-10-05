<?php
namespace Medboubazine\Chargily\Core;

use GuzzleHttp\Client;
use Medboubazine\Chargily\Exceptions\InvalidResponseException;

class WebhookUrl{    
    /**
     * method
     *
     * @var string
     */
    protected string $method = 'POST';
    /**
     * configurations
     *
     * @var Configurationxed
     */
    protected Configurations $configurations;    
    /**
     * cachedHeaders
     *
     * @var null|array
     */
    protected $cachedHeaders = null;    
    /**
     * cachedContent
     *
     * @var null|string
     */
    protected $cachedContent = null;
    
    /**
     * __construct
     *
     * @param  Configurations $configurations
     * @return void
     */
    public function __construct(Configurations $configurations)
    {
        $this->configurations = $configurations;
    }    
    /**
     * checkResponse
     *
     * @return bool
     */
    public function check()
    {
        $computedSignature = hash_hmac('sha256',$this->getContent(),$this->configurations->getApiSecret());

        return @hash_equals($computedSignature,$this->getSignature()) ?? false;

    }    
    /**
     * getInvoiceDetails
     *
     * @return array
     */
    public function getResponseDetails(){
        return $this->getContentToArray() ?? [];
    }
    
    /**
     * getSignature
     *
     * @return null|string
     */
    protected function getSignature(){
        if (isset($this->getHeaders()['signature'])) {
            return $this->getHeaders()['signature'];
        }elseif (isset($this->getHeaders()['Signature'])) {
            return $this->getHeaders()['Signature'];
        }
        return null;
    }     
    /**
     * getContent
     *
     * @return null|string
     */
    protected function getContent(){
        return $this->cachedContent = ($this->cachedContent) ? $this->cachedContent : file_get_contents("php://input");
    }    
    /**
     * getContentToArray
     *
     * @return array
     */
    protected function getContentToArray(){
        return json_decode(json_encode($this->getContent()),true) ?? [];
    }
    /**
     * getHeaders
     *
     * @return array
     */
    protected function getHeaders(){
        return $this->cachedHeaders = ($this->cachedHeaders) ? $this->cachedHeaders : getallheaders();
    }
}