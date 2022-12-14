<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Recording;

use WSAL_Vendor\Twilio\Deserialize;
use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceResource;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Recording\AddOnResult\PayloadList;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
/**
 * @property string $sid
 * @property string $accountSid
 * @property string $status
 * @property string $addOnSid
 * @property string $addOnConfigurationSid
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property \DateTime $dateCompleted
 * @property string $referenceSid
 * @property array $subresourceUris
 */
class AddOnResultInstance extends \WSAL_Vendor\Twilio\InstanceResource
{
    protected $_payloads;
    /**
     * Initialize the AddOnResultInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the Account that created the resource
     * @param string $referenceSid The SID of the recording to which the
     *                             AddOnResult resource belongs
     * @param string $sid The unique string that identifies the resource to fetch
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, array $payload, string $accountSid, string $referenceSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sid'), 'accountSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'account_sid'), 'status' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'status'), 'addOnSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'add_on_sid'), 'addOnConfigurationSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'add_on_configuration_sid'), 'dateCreated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_created')), 'dateUpdated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_updated')), 'dateCompleted' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_completed')), 'referenceSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'reference_sid'), 'subresourceUris' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'subresource_uris')];
        $this->solution = ['accountSid' => $accountSid, 'referenceSid' => $referenceSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return AddOnResultContext Context for this AddOnResultInstance
     */
    protected function proxy() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Recording\AddOnResultContext
    {
        if (!$this->context) {
            $this->context = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Recording\AddOnResultContext($this->version, $this->solution['accountSid'], $this->solution['referenceSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Fetch the AddOnResultInstance
     *
     * @return AddOnResultInstance Fetched AddOnResultInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Recording\AddOnResultInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Delete the AddOnResultInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Access the payloads
     */
    protected function getPayloads() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Recording\AddOnResult\PayloadList
    {
        return $this->proxy()->payloads;
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
        return '[Twilio.Api.V2010.AddOnResultInstance ' . \implode(' ', $context) . ']';
    }
}
