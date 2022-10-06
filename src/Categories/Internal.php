<?php

namespace Mandrill\Categories;

use Mandrill\Client;

// phpcs:ignore Symfony.Commenting.ClassComment.Missing
class Internal
{
    // phpcs:ignore Symfony.Commenting.FunctionComment.Missing
    public function __construct(Client $master)
    {
        $this->master = $master;
    }
}
