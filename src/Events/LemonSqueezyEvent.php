<?php

namespace Rias\StatamicLemonSqueezy\Events;

abstract class LemonSqueezyEvent
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
