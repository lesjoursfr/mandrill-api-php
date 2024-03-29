<?php

namespace Mandrill\Categories;

use Mandrill\Client;

/**
 * Manage your inbound domains and routes.
 */
class Inbound
{
    public $master;

    // phpcs:ignore Symfony.Commenting.FunctionComment.Missing
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     * List the domains that have been configured for inbound delivery.
     *
     * @return array the inbound domains associated with the account
     *               - return[] struct the individual domain info
     *               - domain string the domain name that is accepting mail
     *               - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *               - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function domains()
    {
        $_params = [];

        return $this->master->call('inbound/domains', $_params);
    }

    /**
     * Add an inbound domain to your account.
     *
     * @param string $domain a domain name
     *
     * @return struct information about the domain
     *                - domain string the domain name that is accepting mail
     *                - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function addDomain($domain)
    {
        $_params = ['domain' => $domain];

        return $this->master->call('inbound/add-domain', $_params);
    }

    /**
     * Check the MX settings for an inbound domain. The domain must have already been added with the add-domain call.
     *
     * @param string $domain an existing inbound domain
     *
     * @return struct information about the inbound domain
     *                - domain string the domain name that is accepting mail
     *                - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function checkDomain($domain)
    {
        $_params = ['domain' => $domain];

        return $this->master->call('inbound/check-domain', $_params);
    }

    /**
     * Delete an inbound domain from the account. All mail will stop routing for this domain immediately.
     *
     * @param string $domain an existing inbound domain
     *
     * @return struct information about the deleted domain
     *                - domain string the domain name that is accepting mail
     *                - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function deleteDomain($domain)
    {
        $_params = ['domain' => $domain];

        return $this->master->call('inbound/delete-domain', $_params);
    }

    /**
     * List the mailbox routes defined for an inbound domain.
     *
     * @param string $domain the domain to check
     *
     * @return array the routes associated with the domain
     *               - return[] struct the individual mailbox route
     *               - id string the unique identifier of the route
     *               - pattern string the search pattern that the mailbox name should match
     *               - url string the webhook URL where inbound messages will be published
     */
    public function routes($domain)
    {
        $_params = ['domain' => $domain];

        return $this->master->call('inbound/routes', $_params);
    }

    /**
     * Add a new mailbox route to an inbound domain.
     *
     * @param string $domain  an existing inbound domain
     * @param string $pattern the search pattern that the mailbox name should match
     * @param string $url     the webhook URL where the inbound messages will be published
     *
     * @return struct the added mailbox route information
     *                - id string the unique identifier of the route
     *                - pattern string the search pattern that the mailbox name should match
     *                - url string the webhook URL where inbound messages will be published
     */
    public function addRoute($domain, $pattern, $url)
    {
        $_params = ['domain' => $domain, 'pattern' => $pattern, 'url' => $url];

        return $this->master->call('inbound/add-route', $_params);
    }

    /**
     * Update the pattern or webhook of an existing inbound mailbox route. If null is provided for any fields, the values will remain unchanged.
     *
     * @param string $id      the unique identifier of an existing mailbox route
     * @param string $pattern the search pattern that the mailbox name should match
     * @param string $url     the webhook URL where the inbound messages will be published
     *
     * @return struct the updated mailbox route information
     *                - id string the unique identifier of the route
     *                - pattern string the search pattern that the mailbox name should match
     *                - url string the webhook URL where inbound messages will be published
     */
    public function updateRoute($id, $pattern = null, $url = null)
    {
        $_params = ['id' => $id, 'pattern' => $pattern, 'url' => $url];

        return $this->master->call('inbound/update-route', $_params);
    }

    /**
     * Delete an existing inbound mailbox route.
     *
     * @param string $id the unique identifier of an existing route
     *
     * @return struct the deleted mailbox route information
     *                - id string the unique identifier of the route
     *                - pattern string the search pattern that the mailbox name should match
     *                - url string the webhook URL where inbound messages will be published
     */
    public function deleteRoute($id)
    {
        $_params = ['id' => $id];

        return $this->master->call('inbound/delete-route', $_params);
    }

    /**
     * Take a raw MIME document destined for a domain with inbound domains set up, and send it to the inbound hook exactly as if it had been sent over SMTP.
     *
     * @param string     $rawMessage    the full MIME document of an email message
     * @param array|null $to            optionally define the recipients to receive the message - otherwise we'll use the To, Cc, and Bcc headers provided in the document
     *                                  - to[] string the email address of the recipient
     * @param string     $mailFrom      the address specified in the MAIL FROM stage of the SMTP conversation. Required for the SPF check.
     * @param string     $helo          the identification provided by the client mta in the MTA state of the SMTP conversation. Required for the SPF check.
     * @param string     $clientAddress the remote MTA's ip address. Optional; required for the SPF check.
     *
     * @return array an array of the information for each recipient in the message (usually one) that matched an inbound route
     *               - return[] struct the individual recipient information
     *               - email string the email address of the matching recipient
     *               - pattern string the mailbox route pattern that the recipient matched
     *               - url string the webhook URL that the message was posted to
     */
    public function sendRaw($rawMessage, $to = null, $mailFrom = null, $helo = null, $clientAddress = null)
    {
        $_params = ['raw_message' => $rawMessage, 'to' => $to, 'mail_from' => $mailFrom, 'helo' => $helo, 'client_address' => $clientAddress];

        return $this->master->call('inbound/send-raw', $_params);
    }
}
