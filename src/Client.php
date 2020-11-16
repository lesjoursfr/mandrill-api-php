<?php

namespace Mandrill;

class Client
{
    public $apikey;
    public $ch;
    public $root = 'https://mandrillapp.com/api/1.0';
    public $debug = false;

    public static $error_map = [
        'ValidationError' => Exceptions\ValidationError::class,
        'Invalid_Key' => Exceptions\InvalidKey::class,
        'PaymentRequired' => Exceptions\PaymentRequired::class,
        'Unknown_Subaccount' => Exceptions\UnknownSubaccount::class,
        'Unknown_Template' => Exceptions\UnknownTemplate::class,
        'ServiceUnavailable' => Exceptions\ServiceUnavailable::class,
        'Unknown_Message' => Exceptions\UnknownMessage::class,
        'Invalid_Tag_Name' => Exceptions\InvalidTagName::class,
        'Invalid_Reject' => Exceptions\InvalidReject::class,
        'Unknown_Sender' => Exceptions\UnknownSender::class,
        'Unknown_Url' => Exceptions\UnknownUrl::class,
        'Unknown_TrackingDomain' => Exceptions\UnknownTrackingDomain::class,
        'Invalid_Template' => Exceptions\InvalidTemplate::class,
        'Unknown_Webhook' => Exceptions\UnknownWebhook::class,
        'Unknown_InboundDomain' => Exceptions\UnknownInboundDomain::class,
        'Unknown_InboundRoute' => Exceptions\UnknownInboundRoute::class,
        'Unknown_Export' => Exceptions\UnknownExport::class,
        'IP_ProvisionLimit' => Exceptions\IPProvisionLimit::class,
        'Unknown_Pool' => Exceptions\UnknownPool::class,
        'NoSendingHistory' => Exceptions\NoSendingHistory::class,
        'PoorReputation' => Exceptions\PoorReputation::class,
        'Unknown_IP' => Exceptions\UnknownIP::class,
        'Invalid_EmptyDefaultPool' => Exceptions\InvalidEmptyDefaultPool::class,
        'Invalid_DeleteDefaultPool' => Exceptions\InvalidDeleteDefaultPool::class,
        'Invalid_DeleteNonEmptyPool' => Exceptions\InvalidDeleteNonEmptyPool::class,
        'Invalid_CustomDNS' => Exceptions\InvalidCustomDNS::class,
        'Invalid_CustomDNSPending' => Exceptions\InvalidCustomDNSPending::class,
        'Metadata_FieldLimit' => Exceptions\MetadataFieldLimit::class,
        'Unknown_MetadataField' => Exceptions\UnknownMetadataField::class,
    ];

    public function __construct($apikey = null)
    {
        if (!$apikey) {
            $apikey = getenv('MANDRILL_APIKEY');
        }
        if (!$apikey) {
            $apikey = $this->readConfigs();
        }
        if (!$apikey) {
            throw new Exceptions\Error('You must provide a Mandrill API key');
        }
        $this->apikey = $apikey;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mandrill-PHP/1.0.55');
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 600);

        $this->root = rtrim($this->root, '/').'/';

        $this->templates = new Categories\Templates($this);
        $this->exports = new Categories\Exports($this);
        $this->users = new Categories\Users($this);
        $this->rejects = new Categories\Rejects($this);
        $this->inbound = new Categories\Inbound($this);
        $this->tags = new Categories\Tags($this);
        $this->messages = new Categories\Messages($this);
        $this->whitelists = new Categories\Whitelists($this);
        $this->ips = new Categories\Ips($this);
        $this->internal = new Categories\Internal($this);
        $this->subaccounts = new Categories\Subaccounts($this);
        $this->urls = new Categories\Urls($this);
        $this->webhooks = new Categories\Webhooks($this);
        $this->senders = new Categories\Senders($this);
        $this->metadata = new Categories\Metadata($this);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    public function call($url, $params)
    {
        $params['key'] = $this->apikey;
        $params = json_encode($params);
        $ch = $this->ch;

        curl_setopt($ch, CURLOPT_URL, $this->root.$url.'.json');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);

        $start = microtime(true);
        $this->log('Call to '.$this->root.$url.'.json: '.$params);
        if ($this->debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }

        $response_body = curl_exec($ch);
        $info = curl_getinfo($ch);
        $time = microtime(true) - $start;
        if ($this->debug) {
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        $this->log('Completed in '.number_format($time * 1000, 2).'ms');
        $this->log('Got response: '.$response_body);

        if (curl_error($ch)) {
            throw new Exceptions\HttpError("API call to $url failed: ".curl_error($ch));
        }
        $result = json_decode($response_body, true);
        if (null === $result) {
            throw new Exceptions\Error('We were unable to decode the JSON response from the Mandrill API: '.$response_body);
        }

        if (floor($info['http_code'] / 100) >= 4) {
            throw $this->castError($result);
        }

        return $result;
    }

    public function readConfigs()
    {
        $paths = ['~/.mandrill.key', '/etc/mandrill.key'];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $apikey = trim(file_get_contents($path));
                if ($apikey) {
                    return $apikey;
                }
            }
        }

        return false;
    }

    public function castError($result)
    {
        if ('error' !== $result['status'] || !$result['name']) {
            throw new Exceptions\Error('We received an unexpected error: '.json_encode($result));
        }

        $class = (isset(self::$error_map[$result['name']])) ? self::$error_map[$result['name']] : Exceptions\Error::class;

        return new $class($result['message'], $result['code']);
    }

    public function log($msg)
    {
        if ($this->debug) {
            error_log($msg);
        }
    }
}
