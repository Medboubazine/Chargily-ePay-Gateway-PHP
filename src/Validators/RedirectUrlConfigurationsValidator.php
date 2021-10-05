<?php

namespace Medboubazine\Chargily\Validators;

use Medboubazine\Chargily\Exceptions\InvalidConfigurationsException;

class RedirectUrlConfigurationsValidator
{
    /**
     * configurations
     *
     * @var mixed
     */
    protected array $configurations;    
    /**
     * debug
     *
     * @var bool
     */
    protected bool $debug;    
    /**
     * availlable_modes
     *
     * @var array
     */
    protected array $availlable_modes = ["CIB","EDAHABIA"];
    protected array $availlable_urls = ["back_url","webhook_url"];
    /**
     * __construct
     *
     * @param  array $configurations
     * @return void
     */
    public function __construct(array $configurations,bool $debug = true)
    {
        $this->configurations = $configurations;
        $this->debug = $debug;
    }    
    /**
     * validate
     *
     * @param  array $array
     * @return true
     */
    public function validate()
    {
        $array = $this->configurations;
        //
        if (!isset($array['api_key']) OR !is_string($array['api_key'])) {
            $this->throwException("configurations.api_key is required and must be string");
        }
        if (!isset($array['api_secret']) OR !is_string($array['api_secret'])) {
            $this->throwException("configurations.api_secret is required and must be string");
        }
        if (!isset($array['urls']) OR !is_array($array['urls']) OR empty($array['urls'])) {
            $this->throwException("configurations.urls is required and must be array with two keys (back_url,webhook_url)");
        }
        foreach ($this->availlable_urls as $key) {
            if (!isset($array['urls'][$key]) OR !is_string($array['urls'][$key]) OR !filter_var($array['urls'][$key], FILTER_VALIDATE_URL)) {
                $this->throwException("configurations.urls.{$key} is required and must be valid url");
            }
        }
        if (!isset($array['mode']) OR !is_string($array['mode']) OR !in_array($array['mode'],$this->availlable_modes)) {
            $this->throwException("configurations.mode is required and must be string");
        }
        if (!isset($array['payment']) OR !is_array($array['payment']) OR empty($array['payment'])) {
            $this->throwException("configurations.payment is required and must be array");
        }
            $payment = $array['payment'];
            if (!isset($payment['number']) OR !is_string($payment['number']) OR empty($payment['number'])) {
                $this->throwException("configurations.payment.number is required and must be string");
            }
            if (!isset($payment['client_name']) OR !is_string($payment['client_name']) OR empty($payment['client_name'])) {
                $this->throwException("configurations.payment.client_name is required and must be string");
            }
            if (!isset($payment['amount']) OR !is_numeric($payment['amount'])) {
                $this->throwException("configurations.payment.amount is required and must be numeric");
            }
            if ($array['mode'] === "EDAHABIA") {
                if ($payment['amount'] < 100) {
                    $this->throwException("configurations.payment.amount must be grather or equal than 100 when mode is EDAHABIA");
                }
            }else if ($array['mode'] === "CIB") {
                if ($payment['amount'] < 200) {
                    $this->throwException("configurations.payment.amount must be grather or equal than 200 when mode is CIB");
                }
            }
            if (!isset($payment['discount']) OR !is_numeric($payment['discount']) OR $payment['discount'] < 0 OR $payment['discount'] > 99.99) {
                $this->throwException("configurations.payment.discount is required and must be numeric and must be between 0 to 99.99");
            }
            if (!isset($payment['description']) OR !is_string($payment['description'])) {
                $this->throwException("configurations.payment.description is required and must be string");
            }
        if (!isset($array['options']) OR !is_array($array['options'])) {
            $this->throwException("configurations.options is required and must be array");
        }
        $options = $array['options'];
            if (!isset($options['headers']) OR !is_array($options['headers'])) {
                $this->throwException("configurations.options.headers is required and must be array");
            }
            foreach ($options['headers'] as $key => $value) {
                if (!is_string($key)) {
                    $this->throwException("configurations.options.headers.{$key} key must be string");
                }
                if (!is_string($value)) {
                    $this->throwException("configurations.options.headers.{$key} value must be string");
                }
            }
            if (!isset($options['timeout']) OR !is_numeric($options['timeout']) OR $options['timeout'] < 1) {
                $this->throwException("configurations.options.timeout is required and must be numeric and greather than or equal 1");
            }
        return $array;
    }    
    /**
     * throwException
     *
     * @param  string $message
     * @param  int $code
     * @return void
     */
    protected function throwException(string $message,int $code = 0)
    {
        if ($this->debug) {
            throw new InvalidConfigurationsException($message,$code);
        }else{
            return http_response_code(500);
        }
    }
}
