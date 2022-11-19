<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace WSAL_Vendor\Twilio\Rest\Api\V2010;

use WSAL_Vendor\Twilio\Exceptions\TwilioException;
use WSAL_Vendor\Twilio\InstanceContext;
use WSAL_Vendor\Twilio\ListResource;
use WSAL_Vendor\Twilio\Options;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AuthorizedConnectAppList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountryList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\BalanceList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\CallList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ConferenceList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ConnectAppList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\KeyList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\MessageList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NewKeyList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NewSigningKeyList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NotificationList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\OutgoingCallerIdList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\QueueList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\RecordingList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ShortCodeList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\SigningKeyList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\SipList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\TokenList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\TranscriptionList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\UsageList;
use WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ValidationRequestList;
use WSAL_Vendor\Twilio\Values;
use WSAL_Vendor\Twilio\Version;
/**
 * @property AddressList $addresses
 * @property ApplicationList $applications
 * @property AuthorizedConnectAppList $authorizedConnectApps
 * @property AvailablePhoneNumberCountryList $availablePhoneNumbers
 * @property BalanceList $balance
 * @property CallList $calls
 * @property ConferenceList $conferences
 * @property ConnectAppList $connectApps
 * @property IncomingPhoneNumberList $incomingPhoneNumbers
 * @property KeyList $keys
 * @property MessageList $messages
 * @property NewKeyList $newKeys
 * @property NewSigningKeyList $newSigningKeys
 * @property NotificationList $notifications
 * @property OutgoingCallerIdList $outgoingCallerIds
 * @property QueueList $queues
 * @property RecordingList $recordings
 * @property SigningKeyList $signingKeys
 * @property SipList $sip
 * @property ShortCodeList $shortCodes
 * @property TokenList $tokens
 * @property TranscriptionList $transcriptions
 * @property UsageList $usage
 * @property ValidationRequestList $validationRequests
 * @method \Twilio\Rest\Api\V2010\Account\AddressContext addresses(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\ApplicationContext applications(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\AuthorizedConnectAppContext authorizedConnectApps(string $connectAppSid)
 * @method \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountryContext availablePhoneNumbers(string $countryCode)
 * @method \Twilio\Rest\Api\V2010\Account\CallContext calls(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\ConferenceContext conferences(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\ConnectAppContext connectApps(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberContext incomingPhoneNumbers(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\KeyContext keys(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\MessageContext messages(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\NotificationContext notifications(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\OutgoingCallerIdContext outgoingCallerIds(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\QueueContext queues(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\RecordingContext recordings(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\SigningKeyContext signingKeys(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\ShortCodeContext shortCodes(string $sid)
 * @method \Twilio\Rest\Api\V2010\Account\TranscriptionContext transcriptions(string $sid)
 */
class AccountContext extends \WSAL_Vendor\Twilio\InstanceContext
{
    protected $_addresses;
    protected $_applications;
    protected $_authorizedConnectApps;
    protected $_availablePhoneNumbers;
    protected $_balance;
    protected $_calls;
    protected $_conferences;
    protected $_connectApps;
    protected $_incomingPhoneNumbers;
    protected $_keys;
    protected $_messages;
    protected $_newKeys;
    protected $_newSigningKeys;
    protected $_notifications;
    protected $_outgoingCallerIds;
    protected $_queues;
    protected $_recordings;
    protected $_signingKeys;
    protected $_sip;
    protected $_shortCodes;
    protected $_tokens;
    protected $_transcriptions;
    protected $_usage;
    protected $_validationRequests;
    /**
     * Initialize the AccountContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid Fetch by unique Account Sid
     */
    public function __construct(\WSAL_Vendor\Twilio\Version $version, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sid' => $sid];
        $this->uri = '/Accounts/' . \rawurlencode($sid) . '.json';
    }
    /**
     * Fetch the AccountInstance
     *
     * @return AccountInstance Fetched AccountInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : \WSAL_Vendor\Twilio\Rest\Api\V2010\AccountInstance
    {
        $payload = $this->version->fetch('GET', $this->uri);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\AccountInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Update the AccountInstance
     *
     * @param array|Options $options Optional Arguments
     * @return AccountInstance Updated AccountInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : \WSAL_Vendor\Twilio\Rest\Api\V2010\AccountInstance
    {
        $options = new \WSAL_Vendor\Twilio\Values($options);
        $data = \WSAL_Vendor\Twilio\Values::of(['FriendlyName' => $options['friendlyName'], 'Status' => $options['status']]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new \WSAL_Vendor\Twilio\Rest\Api\V2010\AccountInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Access the addresses
     */
    protected function getAddresses() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressList
    {
        if (!$this->_addresses) {
            $this->_addresses = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AddressList($this->version, $this->solution['sid']);
        }
        return $this->_addresses;
    }
    /**
     * Access the applications
     */
    protected function getApplications() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationList
    {
        if (!$this->_applications) {
            $this->_applications = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ApplicationList($this->version, $this->solution['sid']);
        }
        return $this->_applications;
    }
    /**
     * Access the authorizedConnectApps
     */
    protected function getAuthorizedConnectApps() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AuthorizedConnectAppList
    {
        if (!$this->_authorizedConnectApps) {
            $this->_authorizedConnectApps = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AuthorizedConnectAppList($this->version, $this->solution['sid']);
        }
        return $this->_authorizedConnectApps;
    }
    /**
     * Access the availablePhoneNumbers
     */
    protected function getAvailablePhoneNumbers() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountryList
    {
        if (!$this->_availablePhoneNumbers) {
            $this->_availablePhoneNumbers = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountryList($this->version, $this->solution['sid']);
        }
        return $this->_availablePhoneNumbers;
    }
    /**
     * Access the balance
     */
    protected function getBalance() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\BalanceList
    {
        if (!$this->_balance) {
            $this->_balance = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\BalanceList($this->version, $this->solution['sid']);
        }
        return $this->_balance;
    }
    /**
     * Access the calls
     */
    protected function getCalls() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\CallList
    {
        if (!$this->_calls) {
            $this->_calls = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\CallList($this->version, $this->solution['sid']);
        }
        return $this->_calls;
    }
    /**
     * Access the conferences
     */
    protected function getConferences() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ConferenceList
    {
        if (!$this->_conferences) {
            $this->_conferences = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ConferenceList($this->version, $this->solution['sid']);
        }
        return $this->_conferences;
    }
    /**
     * Access the connectApps
     */
    protected function getConnectApps() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ConnectAppList
    {
        if (!$this->_connectApps) {
            $this->_connectApps = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ConnectAppList($this->version, $this->solution['sid']);
        }
        return $this->_connectApps;
    }
    /**
     * Access the incomingPhoneNumbers
     */
    protected function getIncomingPhoneNumbers() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberList
    {
        if (!$this->_incomingPhoneNumbers) {
            $this->_incomingPhoneNumbers = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberList($this->version, $this->solution['sid']);
        }
        return $this->_incomingPhoneNumbers;
    }
    /**
     * Access the keys
     */
    protected function getKeys() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\KeyList
    {
        if (!$this->_keys) {
            $this->_keys = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\KeyList($this->version, $this->solution['sid']);
        }
        return $this->_keys;
    }
    /**
     * Access the messages
     */
    protected function getMessages() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\MessageList
    {
        if (!$this->_messages) {
            $this->_messages = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\MessageList($this->version, $this->solution['sid']);
        }
        return $this->_messages;
    }
    /**
     * Access the newKeys
     */
    protected function getNewKeys() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NewKeyList
    {
        if (!$this->_newKeys) {
            $this->_newKeys = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NewKeyList($this->version, $this->solution['sid']);
        }
        return $this->_newKeys;
    }
    /**
     * Access the newSigningKeys
     */
    protected function getNewSigningKeys() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NewSigningKeyList
    {
        if (!$this->_newSigningKeys) {
            $this->_newSigningKeys = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NewSigningKeyList($this->version, $this->solution['sid']);
        }
        return $this->_newSigningKeys;
    }
    /**
     * Access the notifications
     */
    protected function getNotifications() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NotificationList
    {
        if (!$this->_notifications) {
            $this->_notifications = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\NotificationList($this->version, $this->solution['sid']);
        }
        return $this->_notifications;
    }
    /**
     * Access the outgoingCallerIds
     */
    protected function getOutgoingCallerIds() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\OutgoingCallerIdList
    {
        if (!$this->_outgoingCallerIds) {
            $this->_outgoingCallerIds = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\OutgoingCallerIdList($this->version, $this->solution['sid']);
        }
        return $this->_outgoingCallerIds;
    }
    /**
     * Access the queues
     */
    protected function getQueues() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\QueueList
    {
        if (!$this->_queues) {
            $this->_queues = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\QueueList($this->version, $this->solution['sid']);
        }
        return $this->_queues;
    }
    /**
     * Access the recordings
     */
    protected function getRecordings() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\RecordingList
    {
        if (!$this->_recordings) {
            $this->_recordings = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\RecordingList($this->version, $this->solution['sid']);
        }
        return $this->_recordings;
    }
    /**
     * Access the signingKeys
     */
    protected function getSigningKeys() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\SigningKeyList
    {
        if (!$this->_signingKeys) {
            $this->_signingKeys = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\SigningKeyList($this->version, $this->solution['sid']);
        }
        return $this->_signingKeys;
    }
    /**
     * Access the sip
     */
    protected function getSip() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\SipList
    {
        if (!$this->_sip) {
            $this->_sip = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\SipList($this->version, $this->solution['sid']);
        }
        return $this->_sip;
    }
    /**
     * Access the shortCodes
     */
    protected function getShortCodes() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ShortCodeList
    {
        if (!$this->_shortCodes) {
            $this->_shortCodes = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ShortCodeList($this->version, $this->solution['sid']);
        }
        return $this->_shortCodes;
    }
    /**
     * Access the tokens
     */
    protected function getTokens() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\TokenList
    {
        if (!$this->_tokens) {
            $this->_tokens = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\TokenList($this->version, $this->solution['sid']);
        }
        return $this->_tokens;
    }
    /**
     * Access the transcriptions
     */
    protected function getTranscriptions() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\TranscriptionList
    {
        if (!$this->_transcriptions) {
            $this->_transcriptions = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\TranscriptionList($this->version, $this->solution['sid']);
        }
        return $this->_transcriptions;
    }
    /**
     * Access the usage
     */
    protected function getUsage() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\UsageList
    {
        if (!$this->_usage) {
            $this->_usage = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\UsageList($this->version, $this->solution['sid']);
        }
        return $this->_usage;
    }
    /**
     * Access the validationRequests
     */
    protected function getValidationRequests() : \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ValidationRequestList
    {
        if (!$this->_validationRequests) {
            $this->_validationRequests = new \WSAL_Vendor\Twilio\Rest\Api\V2010\Account\ValidationRequestList($this->version, $this->solution['sid']);
        }
        return $this->_validationRequests;
    }
    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get(string $name) : \WSAL_Vendor\Twilio\ListResource
    {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->{$method}();
        }
        throw new \WSAL_Vendor\Twilio\Exceptions\TwilioException('Unknown subresource ' . $name);
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
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "{$key}={$value}";
        }
        return '[Twilio.Api.V2010.AccountContext ' . \implode(' ', $context) . ']';
    }
}
