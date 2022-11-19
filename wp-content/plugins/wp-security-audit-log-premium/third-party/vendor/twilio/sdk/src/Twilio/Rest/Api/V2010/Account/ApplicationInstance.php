<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account;

use WSAL_Vendor\Twilio\Deserialize;
use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceResource;
use WSAL_Vendor\Twilio\Options;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
/**
 * @property string $accountSid
 * @property string $apiVersion
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property string $friendlyName
 * @property string $messageStatusCallback
 * @property string $sid
 * @property string $smsFallbackMethod
 * @property string $smsFallbackUrl
 * @property string $smsMethod
 * @property string $smsStatusCallback
 * @property string $smsUrl
 * @property string $statusCallback
 * @property string $statusCallbackMethod
 * @property string $uri
 * @property bool $voiceCallerIdLookup
 * @property string $voiceFallbackMethod
 * @property string $voiceFallbackUrl
 * @property string $voiceMethod
 * @property string $voiceUrl
 */
class ApplicationInstance extends \WSAL_Vendor\Twilio\InstanceResource
{
    /**
     * Initialize the ApplicationInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the Account that created the resource
     * @param string $sid The unique string that identifies the resource
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, array $payload, string $accountSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'account_sid'), 'apiVersion' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'api_version'), 'dateCreated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_created')), 'dateUpdated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_updated')), 'friendlyName' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'friendly_name'), 'messageStatusCallback' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'message_status_callback'), 'sid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sid'), 'smsFallbackMethod' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sms_fallback_method'), 'smsFallbackUrl' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sms_fallback_url'), 'smsMethod' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sms_method'), 'smsStatusCallback' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sms_status_callback'), 'smsUrl' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sms_url'), 'statusCallback' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'status_callback'), 'statusCallbackMethod' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'status_callback_method'), 'uri' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'uri'), 'voiceCallerIdLookup' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'voice_caller_id_lookup'), 'voiceFallbackMethod' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'voice_fallback_method'), 'voiceFallbackUrl' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'voice_fallback_url'), 'voiceMethod' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'voice_method'), 'voiceUrl' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'voice_url')];
        $this->solution = ['accountSid' => $accountSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return ApplicationContext Context for this ApplicationInstance
     */
    protected function proxy() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationContext
    {
        if (!$this->context) {
            $this->context = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationContext($this->version, $this->solution['accountSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the ApplicationInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the ApplicationInstance
     *
     * @return ApplicationInstance Fetched ApplicationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the ApplicationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ApplicationInstance Updated ApplicationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationInstance
    {
        return $this->proxy()->update($options);
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
        return '[Twilio.Api.V2010.ApplicationInstance ' . \implode(' ', $context) . ']';
    }
}