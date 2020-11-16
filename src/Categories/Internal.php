<?php

namespace Mandrill\Categories;

use Mandrill\Client;

class Internal
{
    public function __construct(Client $master)
    {
        $this->master = $master;
    }
}
