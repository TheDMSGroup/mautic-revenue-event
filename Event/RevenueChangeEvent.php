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
     * W4 Engine (DEV).
     */
    const W4_DEV_API_ENDPOINT = 'http://engine.w4dev.net/postBack';

    /**
     * W4 Engine (PROD).
     */
    const W4_API_ENDPOINT = 'http://eng.trkcnv.com/postBack';

    /**
     * @var array
     */
    private $payload;

    /**
     * @var
     */
    private $endpoint;

    /**
     * RevenueChangeEvent constructor.
     *
     * @param array $webhook_payload
     */
    public function __construct($payload, $endpoint = false)
    {
        $this->payload  = $payload;
        $this->endpoint = $endpoint;
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
        if (!$this->endpoint) {
            $this->endpoint = self::W4_API_ENDPOINT;
        }

        return $this->endpoint.'?'.http_build_query($this->payload);
    }
}
