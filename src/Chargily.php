<?php
namespace Medboubazine\Chargily;

use Medboubazine\Chargily\Core\Configurations;
use Medboubazine\Chargily\Core\RedirectUrl;
use Medboubazine\Chargily\Core\WebhookUrl;

class Chargily{        
    /**
     * configurations
     *
     * @var Configurations
     */
    protected Configurations $configurations;    
    /**
     * cachedUrl
     *
     * @var null|string
     */
    protected $cachedRedirectUrl = null;
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
     * getRedirectUrl
     *
     * @return null|string
     */
    public function getRedirectUrl()
    {
        $this->configurations->validateRedirectConfigurations();
        //
        return $this->cachedRedirectUrl = ($this->cachedRedirectUrl) ? $this->cachedRedirectUrl : (new RedirectUrl($this->configurations))->getRedirectUrl() ;
    }    
    /**
     * checkResponse
     *
     * @param  array $params
     * @return void
     */
    public function checkResponse()
    {
        $this->configurations->validateWebhookConfigurations();

        return (new WebhookUrl($this->configurations))->check();
    }    
    /**
     * getResponseDetails
     *
     * @return array
     */
    public function getResponseDetails()
    {
        $this->configurations->validateWebhookConfigurations();

        return (new WebhookUrl($this->configurations))->getResponseDetails();
    }
}
