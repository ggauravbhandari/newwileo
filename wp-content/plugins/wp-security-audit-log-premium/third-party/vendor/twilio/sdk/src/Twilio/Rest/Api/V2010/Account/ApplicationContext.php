<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account;

use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceContext;
use WSAL_Vendor\Twilio\Options;
use WSAL_Vendor\Twilio\Serialize;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
class ApplicationContext extends \WSAL_Vendor\Twilio\InstanceContext
{
    /**
     * Initialize the ApplicationContext
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid The SID of the Account that created the resource
     *                           to fetch
     * @param string $sid The unique string that identifies the resource
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, $accountSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['accountSid' => $accountSid, 'sid' => $sid];
        $this->uri = '/Accounts/' . \rawurlencode($accountSid) . '/Applications/' . \rawurlencode($sid) . '.json';
    }
    /**
     * Delete the ApplicationInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
    }
    /**
     * Fetch the ApplicationInstance
     *
     * @return ApplicationInstance Fetched ApplicationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationInstance
    {
        $payload = $this->version->fetch('GET', $this->uri);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['sid']);
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
        $options = new \WSAL_Vendor\Twilio\Values($options);
        $data = \WSAL_Vendor\Twilio\Values::of(['FriendlyName' => $options['friendlyName'], 'ApiVersion' => $options['apiVersion'], 'VoiceUrl' => $options['voiceUrl'], 'VoiceMethod' => $options['voiceMethod'], 'VoiceFallbackUrl' => $options['voiceFallbackUrl'], 'VoiceFallbackMethod' => $options['voiceFallbackMethod'], 'StatusCallback' => $options['statusCallback'], 'StatusCallbackMethod' => $options['statusCallbackMethod'], 'VoiceCallerIdLookup' => \WSAL_Vendor\Twilio\Serialize::booleanToString($options['voiceCallerIdLookup']), 'SmsUrl' => $options['smsUrl'], 'SmsMethod' => $options['smsMethod'], 'SmsFallbackUrl' => $options['smsFallbackUrl'], 'SmsFallbackMethod' => $options['smsFallbackMethod'], 'SmsStatusCallback' => $options['smsStatusCallback'], 'MessageStatusCallback' => $options['messageStatusCallback']]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['sid']);
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
        return '[Twilio.Api.V2010.ApplicationContext ' . \implode(' ', $context) . ']';
    }
}
