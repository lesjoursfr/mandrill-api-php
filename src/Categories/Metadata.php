<?php

namespace Mandrill\Categories;

use Mandrill\Client;

/**
 * Manage your custom metadata fields in your account.
 */
class Metadata
{
    public $master;

    // phpcs:ignore Symfony.Commenting.FunctionComment.Missing
    public function __construct(Client $master)
    {
        $this->master = $master;
    }

    /**
     * Get the list of custom metadata fields indexed for the account.
     *
     * @return array the custom metadata fields for the account
     *               - return[] struct the individual custom metadata field info
     *               - name string the unique identifier of the metadata field to update
     *               - state string the current state of the metadata field, one of "active", "delete", or "index"
     *               - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function getList()
    {
        $_params = [];

        return $this->master->call('metadata/list', $_params);
    }

    /**
     * Add a new custom metadata field to be indexed for the account.
     *
     * @param string $name         a unique identifier for the metadata field
     * @param string $viewTemplate optional Mustache template to control how the metadata is rendered in your activity log
     *
     * @return struct the information saved about the new metadata field
     *                - name string the unique identifier of the metadata field to update
     *                - state string the current state of the metadata field, one of "active", "delete", or "index"
     *                - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function add($name, $viewTemplate = null)
    {
        $_params = ['name' => $name, 'view_template' => $viewTemplate];

        return $this->master->call('metadata/add', $_params);
    }

    /**
     * Update an existing custom metadata field.
     *
     * @param string $name         the unique identifier of the metadata field to update
     * @param string $viewTemplate optional Mustache template to control how the metadata is rendered in your activity log
     *
     * @return struct the information for the updated metadata field
     *                - name string the unique identifier of the metadata field to update
     *                - state string the current state of the metadata field, one of "active", "delete", or "index"
     *                - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function update($name, $viewTemplate)
    {
        $_params = ['name' => $name, 'view_template' => $viewTemplate];

        return $this->master->call('metadata/update', $_params);
    }

    /**
     * Delete an existing custom metadata field. Deletion isn't instataneous, and /metadata/list will continue to return the field until the asynchronous deletion process is complete.
     *
     * @param string $name the unique identifier of the metadata field to update
     *
     * @return struct the information for the deleted metadata field
     *                - name string the unique identifier of the metadata field to update
     *                - state string the current state of the metadata field, one of "active", "delete", or "index"
     *                - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function delete($name)
    {
        $_params = ['name' => $name];

        return $this->master->call('metadata/delete', $_params);
    }
}
