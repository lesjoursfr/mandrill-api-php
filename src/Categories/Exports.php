<?php

namespace Mandrill\Categories;

use Mandrill\Client;

/**
 * Start an export, or get information on export jobs in progress.
 */
class Exports
{
    public $master;

    // phpcs:ignore Symfony.Commenting.FunctionComment.Missing
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     * Returns information about an export job. If the export job's state is 'complete',
     * the returned data will include a URL you can use to fetch the results. Every export
     * job produces a zip archive, but the format of the archive is distinct for each job
     * type. The api calls that initiate exports include more details about the output format
     * for that job type.
     *
     * @param string $id an export job identifier
     *
     * @return struct the information about the export
     *                - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *                - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - type string the type of the export job - activity, reject, or whitelist
     *                - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - state string the export job's state - waiting, working, complete, error, or expired.
     *                - result_url string the url for the export job's results, if the job is completed.
     */
    public function info($id)
    {
        $_params = ['id' => $id];

        return $this->master->call('exports/info', $_params);
    }

    /**
     * Returns a list of your exports.
     *
     * @return array the account's exports
     *               - return[] struct the individual export info
     *               - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *               - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *               - type string the type of the export job - activity, reject, or whitelist
     *               - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format
     *               - state string the export job's state - waiting, working, complete, error, or expired.
     *               - result_url string the url for the export job's results, if the job is completed.
     */
    public function getList()
    {
        $_params = [];

        return $this->master->call('exports/list', $_params);
    }

    /**
     * Begins an export of your rejection blacklist. The blacklist will be exported to a zip archive
     * containing a single file named rejects.csv that includes the following fields: email,
     * reason, detail, created_at, expires_at, last_event_at, expires_at.
     *
     * @param string $notifyEmail an optional email address to notify when the export job has finished
     *
     * @return struct information about the rejects export job that was started
     *                - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *                - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - type string the type of the export job
     *                - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format, or null for jobs that have not run
     *                - state string the export job's state
     *                - result_url string the url for the export job's results, if the job is complete
     */
    public function rejects($notifyEmail = null)
    {
        $_params = ['notify_email' => $notifyEmail];

        return $this->master->call('exports/rejects', $_params);
    }

    /**
     * Begins an export of your rejection whitelist. The whitelist will be exported to a zip archive
     * containing a single file named whitelist.csv that includes the following fields:
     * email, detail, created_at.
     *
     * @param string $notifyEmail an optional email address to notify when the export job has finished
     *
     * @return struct information about the whitelist export job that was started
     *                - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *                - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - type string the type of the export job
     *                - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format, or null for jobs that have not run
     *                - state string the export job's state
     *                - result_url string the url for the export job's results, if the job is complete
     */
    public function whitelist($notifyEmail = null)
    {
        $_params = ['notify_email' => $notifyEmail];

        return $this->master->call('exports/whitelist', $_params);
    }

    /**
     * Begins an export of your activity history. The activity will be exported to a zip archive
     * containing a single file named activity.csv in the same format as you would be able to export
     * from your account's activity view. It includes the following fields: Date, Email Address,
     * Sender, Subject, Status, Tags, Opens, Clicks, Bounce Detail. If you have configured any custom
     * metadata fields, they will be included in the exported data.
     *
     * @param string $notifyEmail an optional email address to notify when the export job has finished
     * @param string $dateFrom    start date as a UTC string in YYYY-MM-DD HH:MM:SS format
     * @param string $dateTo      end date as a UTC string in YYYY-MM-DD HH:MM:SS format
     * @param array  $tags        an array of tag names to narrow the export to; will match messages that contain ANY of the tags
     *                            - tags[] string a tag name
     * @param array  $senders     an array of senders to narrow the export to
     *                            - senders[] string a sender address
     * @param array  $states      an array of states to narrow the export to; messages with ANY of the states will be included
     *                            - states[] string a message state
     * @param array  $apiKeys     an array of api keys to narrow the export to; messsagse sent with ANY of the keys will be included
     *                            - api_keys[] string an API key associated with your account
     *
     * @return struct information about the activity export job that was started
     *                - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *                - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *                - type string the type of the export job
     *                - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format, or null for jobs that have not run
     *                - state string the export job's state
     *                - result_url string the url for the export job's results, if the job is complete
     */
    public function activity($notifyEmail = null, $dateFrom = null, $dateTo = null, $tags = null, $senders = null, $states = null, $apiKeys = null)
    {
        $_params = ['notify_email' => $notifyEmail, 'date_from' => $dateFrom, 'date_to' => $dateTo, 'tags' => $tags, 'senders' => $senders, 'states' => $states, 'api_keys' => $apiKeys];

        return $this->master->call('exports/activity', $_params);
    }
}
