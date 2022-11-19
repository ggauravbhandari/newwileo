<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Queue;

use WSAL_Vendor\Twilio\Deserialize;
use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceResource;
use WSAL_Vendor\Twilio\Options;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
/**
 * @property string $callSid
 * @property \DateTime $dateEnqueued
 * @property int $position
 * @property string $uri
 * @property int $waitTime
 * @property string $queueSid
 */
class MemberInstance extends \WSAL_Vendor\Twilio\InstanceResource
{
    /**
     * Initialize the MemberInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the Account that created this resource
     * @param string $queueSid The SID of the Queue the member is in
     * @param string $callSid The Call SID of the resource(s) to fetch
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, array $payload, string $accountSid, string $queueSid, string $callSid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['callSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'call_sid'), 'dateEnqueued' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_enqueued')), 'position' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'position'), 'uri' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'uri'), 'waitTime' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'wait_time'), 'queueSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'queue_sid')];
        $this->solution = ['accountSid' => $accountSid, 'queueSid' => $queueSid, 'callSid' => $callSid ?: $this->properties['callSid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return MemberContext Context for this MemberInstance
     */
    protected function proxy() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Queue\MemberContext
    {
        if (!$this->context) {
            $this->context = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Queue\MemberContext($this->version, $this->solution['accountSid'], $this->solution['queueSid'], $this->solution['callSid']);
        }
        return $this->context;
    }
    /**
     * Fetch the MemberInstance
     *
     * @return MemberInstance Fetched MemberInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Queue\MemberInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the MemberInstance
     *
     * @param string $url The absolute URL of the Queue resource
     * @param array|Options $options Optional Arguments
     * @return MemberInstance Updated MemberInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(string $url, array $options = []) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Queue\MemberInstance
    {
        return $this->proxy()->update($url, $options);
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
        return '[Twilio.Api.V2010.MemberInstance ' . \implode(' ', $context) . ']';
    }
}