<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip;

use WSAL_Vendor\Twilio\Deserialize;
use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceResource;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\IpAccessControlList\IpAddressList;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
/**
 * @property string $sid
 * @property string $accountSid
 * @property string $friendlyName
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property array $subresourceUris
 * @property string $uri
 */
class IpAccessControlListInstance extends \WSAL_Vendor\Twilio\InstanceResource
{
    protected $_ipAddresses;
    /**
     * Initialize the IpAccessControlListInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid A 34 character string that uniquely identifies
     *                           this resource.
     * @param string $sid A string that identifies the resource to fetch
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, array $payload, string $accountSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sid'), 'accountSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'account_sid'), 'friendlyName' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'friendly_name'), 'dateCreated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_created')), 'dateUpdated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_updated')), 'subresourceUris' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'subresource_uris'), 'uri' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'uri')];
        $this->solution = ['accountSid' => $accountSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return IpAccessControlListContext Context for this
     *                                    IpAccessControlListInstance
     */
    protected function proxy() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\IpAccessControlListContext
    {
        if (!$this->context) {
            $this->context = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\IpAccessControlListContext($this->version, $this->solution['accountSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Fetch the IpAccessControlListInstance
     *
     * @return IpAccessControlListInstance Fetched IpAccessControlListInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\IpAccessControlListInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the IpAccessControlListInstance
     *
     * @param string $friendlyName A human readable description of this resource
     * @return IpAccessControlListInstance Updated IpAccessControlListInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(string $friendlyName) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\IpAccessControlListInstance
    {
        return $this->proxy()->update($friendlyName);
    }
    /**
     * Delete the IpAccessControlListInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Access the ipAddresses
     */
    protected function getIpAddresses() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\IpAccessControlList\IpAddressList
    {
        return $this->proxy()->ipAddresses;
    }
    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->{$method}();
        }
        throw new \WSAL_Vendor\Twilio\Exceptions\TwilioException('Unknown property: ' . $name);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "{$key}={$value}";
        }
        return '[Twilio.Api.V2010.IpAccessControlListInstance ' . \implode(' ', $context) . ']';
    }
}
