<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010\Account;

use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\ListResource;
use WSAL_Vendor\Twilio\Options;
use WSAL_Vendor\Twilio\Serialize;
use WSAL_Vendor\Twilio\Stream;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
class AddressList extends \WSAL_Vendor\Twilio\ListResource
{
    /**
     * Construct the AddressList
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid The SID of the Account that is responsible for the
     *                           resource
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, string $accountSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['accountSid' => $accountSid];
        $this->uri = '/Accounts/' . \rawurlencode($accountSid) . '/Addresses.json';
    }
    /**
     * Create the AddressInstance
     *
     * @param string $customerName The name to associate with the new address
     * @param string $street The number and street address of the new address
     * @param string $city The city of the new address
     * @param string $region The state or region of the new address
     * @param string $postalCode The postal code of the new address
     * @param string $isoCountry The ISO country code of the new address
     * @param array|Options $options Optional Arguments
     * @return AddressInstance Created AddressInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(string $customerName, string $street, string $city, string $region, string $postalCode, string $isoCountry, array $options = []) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressInstance
    {
        $options = new \WSAL_Vendor\Twilio\Values($options);
        $data = \WSAL_Vendor\Twilio\Values::of(['CustomerName' => $customerName, 'Street' => $street, 'City' => $city, 'Region' => $region, 'PostalCode' => $postalCode, 'IsoCountry' => $isoCountry, 'FriendlyName' => $options['friendlyName'], 'EmergencyEnabled' => \WSAL_Vendor\Twilio\Serialize::booleanToString($options['emergencyEnabled']), 'AutoCorrectAddress' => \WSAL_Vendor\Twilio\Serialize::booleanToString($options['autoCorrectAddress'])]);
        $payload = $this->version->create('POST', $this->uri, [], $data);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressInstance($this->version, $payload, $this->solution['accountSid']);
    }
    /**
     * Streams AddressInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return Stream stream of results
     */
    public function stream(array $options = [], int $limit = null, $pageSize = null) : \WSAL_Vendor\Twilio\Stream
    {
        $limits = $this->version->readLimits($limit, $pageSize);
        $page = $this->page($options, $limits['pageSize']);
        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }
    /**
     * Reads AddressInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return AddressInstance[] Array of results
     */
    public function read(array $options = [], int $limit = null, $pageSize = null) : array
    {
        return \iterator_to_array($this->stream($options, $limit, $pageSize), \false);
    }
    /**
     * Retrieve a single page of AddressInstance records from the API.
     * Request is executed immediately
     *
     * @param array|Options $options Optional Arguments
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return AddressPage Page of AddressInstance
     */
    public function page(array $options = [], $pageSize = \WSAL_Vendor\Twilio\Values::NONE, string $pageToken = \WSAL_Vendor\Twilio\Values::NONE, $pageNumber = \WSAL_Vendor\Twilio\Values::NONE) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressPage
    {
        $options = new \WSAL_Vendor\Twilio\Values($options);
        $params = \WSAL_Vendor\Twilio\Values::of(['CustomerName' => $options['customerName'], 'FriendlyName' => $options['friendlyName'], 'IsoCountry' => $options['isoCountry'], 'PageToken' => $pageToken, 'Page' => $pageNumber, 'PageSize' => $pageSize]);
        $response = $this->version->page('GET', $this->uri, $params);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressPage($this->version, $response, $this->solution);
    }
    /**
     * Retrieve a specific page of AddressInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return AddressPage Page of AddressInstance
     */
    public function getPage(string $targetUrl) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressPage
    {
        $response = $this->version->getDomain()->getClient()->request('GET', $targetUrl);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressPage($this->version, $response, $this->solution);
    }
    /**
     * Constructs a AddressContext
     *
     * @param string $sid The unique string that identifies the resource
     */
    public function getContext(string $sid) : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressContext
    {
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressContext($this->version, $this->solution['accountSid'], $sid);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Api.V2010.AddressList]';
    }
}
