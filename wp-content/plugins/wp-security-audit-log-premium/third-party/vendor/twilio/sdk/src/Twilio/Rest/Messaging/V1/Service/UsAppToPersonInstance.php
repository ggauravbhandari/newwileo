<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Messaging\V1\Service;

use WSAL_Vendor\Twilio\Deserialize;
use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceResource;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 *
 * @property string $sid
 * @property string $accountSid
 * @property string $brandRegistrationSid
 * @property string $messagingServiceSid
 * @property string $description
 * @property string[] $messageSamples
 * @property string $usAppToPersonUsecase
 * @property bool $hasEmbeddedLinks
 * @property bool $hasEmbeddedPhone
 * @property string $campaignStatus
 * @property string $campaignId
 * @property bool $isExternallyRegistered
 * @property array $rateLimits
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property string $url
 */
class UsAppToPersonInstance extends \WSAL_Vendor\Twilio\InstanceResource
{
    /**
     * Initialize the UsAppToPersonInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $messagingServiceSid The SID of the Messaging Service the
     *                                    resource is associated with
     * @param string $sid The SID that identifies the US A2P Compliance resource to
     *                    fetch
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, array $payload, string $messagingServiceSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'sid'), 'accountSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'account_sid'), 'brandRegistrationSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'brand_registration_sid'), 'messagingServiceSid' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'messaging_service_sid'), 'description' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'description'), 'messageSamples' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'message_samples'), 'usAppToPersonUsecase' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'us_app_to_person_usecase'), 'hasEmbeddedLinks' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'has_embedded_links'), 'hasEmbeddedPhone' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'has_embedded_phone'), 'campaignStatus' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'campaign_status'), 'campaignId' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'campaign_id'), 'isExternallyRegistered' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'is_externally_registered'), 'rateLimits' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'rate_limits'), 'dateCreated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_created')), 'dateUpdated' => \WSAL_Vendor\Twilio\Deserialize::dateTime(\WSAL_Vendor\Twilio\Values::array_get($payload, 'date_updated')), 'url' => \WSAL_Vendor\Twilio\Values::array_get($payload, 'url')];
        $this->solution = ['messagingServiceSid' => $messagingServiceSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return UsAppToPersonContext Context for this UsAppToPersonInstance
     */
    protected function proxy() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\Service\UsAppToPersonContext
    {
        if (!$this->context) {
            $this->context = new \WSAL_Vendor\Twilio\Rest\Messaging\V1\Service\UsAppToPersonContext($this->version, $this->solution['messagingServiceSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the UsAppToPersonInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the UsAppToPersonInstance
     *
     * @return UsAppToPersonInstance Fetched UsAppToPersonInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Messaging\V1\Service\UsAppToPersonInstance
    {
        return $this->proxy()->fetch();
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
        return '[Twilio.Messaging.V1.UsAppToPersonInstance ' . \implode(' ', $context) . ']';
    }
}
