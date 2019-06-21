<?php
/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRevenueEventBundle\EventListener;

use GuzzleHttp\Client;
use Mautic\WebhookBundle\EventListener\WebhookSubscriberBase;
use MauticPlugin\MauticRevenueEventBundle\Event\RevenueChangeEvent;
use MauticPlugin\MauticRevenueEventBundle\MauticRevenueEventEvents;

class RevenueEventSubscriber extends WebhookSubscriberBase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * RevenueEventSubscriber constructor.
     */
    public function __construct()
    {
        $this->client = new Client();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            MauticRevenueEventEvents::REVENUE_CHANGE => 'onRevenueChange',
        ];
    }

    /**
     * @param RevenueEventContextEvent $event
     */
    public function onRevenueChange(RevenueChangeEvent $event)
    {
        return $this->client->getAsync($event->getEndpoint());
    }
}
