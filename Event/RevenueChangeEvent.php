<?php

namespace MauticPlugin\MauticRevenueEventBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class RevenueChangeEvent.
 */
class RevenueChangeEvent extends Event
{
    /**
     * RevenueChangeEvent constructor.
     *
     * @param array $webhook_payload
     */
    public function __construct(array $webhook_payload)
    {
        $this->webhook_payload = $webhook_payload;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->webhook_payload;
    }
}
