<?php

namespace MauticPlugin\MauticRevenueEventBundle\Event;

use MauticPlugin\MauticRevenueEventBundle\Contract\RevenueEvents;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class RevenueChangeEvent.
 */
class RevenueChangeEvent extends Event implements RevenueEvents
{
    /**
     * W4 Engine (PROD).
     */
    const W4_API_ENDPOINT = 'https://eng.trkcnv.com/postBack';

    /**
     * @var array
     */
    private $payload;

    /**
     * RevenueChangeEvent constructor.
     *
     * @param array $webhook_payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return self::W4_API_ENDPOINT.'?'.http_build_url($this->payload);
    }
}
