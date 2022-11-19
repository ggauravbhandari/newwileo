<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Messaging;

use WSAL_Vendor\Twilio\Domain;
use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceContext;
use WSAL_Vendor\Twilio\Rest\Messaging\V1\BrandRegistrationList;
use WSAL_Vendor\Twilio\Rest\Messaging\V1\DeactivationsList;
use WSAL_Vendor\Twilio\Rest\Messaging\V1\ExternalCampaignList;
use WSAL_Vendor\Twilio\Rest\Messaging\V1\ServiceList;
use WSAL_Vendor\Twilio\Rest\Messaging\V1\UsecaseList;
use WSAL_Vendor\Twilio\Version;
/**
 * @property BrandRegistrationList $brandRegistrations
 * @property DeactivationsList $deactivations
 * @property ExternalCampaignList $externalCampaign
 * @property ServiceList $services
 * @property UsecaseList $usecases
 * @method \Twilio\Rest\Messaging\V1\BrandRegistrationContext brandRegistrations(string $sid)
 * @method \Twilio\Rest\Messaging\V1\ServiceContext services(string $sid)
 */
class V1 extends \WSAL_Vendor\Twilio\Version
{
    protected $_brandRegistrations;
    protected $_deactivations;
    protected $_externalCampaign;
    protected $_services;
    protected $_usecases;
    /**
     * Construct the V1 version of Messaging
     *
     * @param Domain $domain Domain that contains the version
     */
    public function __construct(\WSAL_Vendor\Twilio\Domain $domain)
    {
        parent::__construct($domain);
        $this->version = 'v1';
    }
    protected function getBrandRegistrations() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\BrandRegistrationList
    {
        if (!$this->_brandRegistrations) {
            $this->_brandRegistrations = new \WSAL_Vendor\Twilio\Rest\Messaging\V1\BrandRegistrationList($this);
        }
        return $this->_brandRegistrations;
    }
    protected function getDeactivations() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\DeactivationsList
    {
        if (!$this->_deactivations) {
            $this->_deactivations = new \WSAL_Vendor\Twilio\Rest\Messaging\V1\DeactivationsList($this);
        }
        return $this->_deactivations;
    }
    protected function getExternalCampaign() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\ExternalCampaignList
    {
        if (!$this->_externalCampaign) {
            $this->_externalCampaign = new \WSAL_Vendor\Twilio\Rest\Messaging\V1\ExternalCampaignList($this);
        }
        return $this->_externalCampaign;
    }
    protected function getServices() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\ServiceList
    {
        if (!$this->_services) {
            $this->_services = new \WSAL_Vendor\Twilio\Rest\Messaging\V1\ServiceList($this);
        }
        return $this->_services;
    }
    protected function getUsecases() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\UsecaseList
    {
        if (!$this->_usecases) {
            $this->_usecases = new \WSAL_Vendor\Twilio\Rest\Messaging\V1\UsecaseList($this);
        }
        return $this->_usecases;
    }
    /**
     * Magic getter to lazy load root resources
     *
     * @param string $name Resource to return
     * @return \Twilio\ListResource The requested resource
     * @throws TwilioException For unknown resource
     */
    public function __get(string $name)
    {
        $method = 'get' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return $this->{$method}();
        }
        throw new \WSAL_Vendor\Twilio\Exceptions\TwilioException('Unknown resource ' . $name);
    }
    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments) : \WSAL_Vendor\Twilio\InstanceContext
    {
        $property = $this->{$name};
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }
        throw new \WSAL_Vendor\Twilio\Exceptions\TwilioException('Resource does not have a context');
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Messaging.V1]';
    }
}