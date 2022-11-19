<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\Domain;

use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceContext;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
class IpAccessControlListMappingContext extends \WSAL_Vendor\Twilio\InstanceContext
{
    /**
     * Initialize the IpAccessControlListMappingContext
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid The unique id of the Account that is responsible
     *                           for this resource.
     * @param string $domainSid A string that uniquely identifies the SIP Domain
     * @param string $sid A 34 character string that uniquely identifies the
     *                    resource to fetch.
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, $accountSid, $domainSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['accountSid' => $accountSid, 'domainSid' => $domainSid, 'sid' => $sid];
        $this->uri = '/Accounts/' . \rawurlencode($accountSid) . '/SIP/Domains/' . \rawurlencode($domainSid) . '/IpAccessControlListMappings/' . \rawurlencode($sid) . '.json';
    }
    /**
     * Fetch the IpAccessControlListMappingInstance
     *
     * @return IpAccessControlListMappingInstance Fetched
     *                                            IpAccessControlListMappingInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\Domain\IpAccessControlListMappingInstance
    {
        $payload = $this->version->fetch('GET', $this->uri);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\Sip\Domain\IpAccessControlListMappingInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['domainSid'], $this->solution['sid']);
    }
    /**
     * Delete the IpAccessControlListMappingInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
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
        return '[Twilio.Api.V2010.IpAccessControlListMappingContext ' . \implode(' ', $context) . ']';
    }
}
