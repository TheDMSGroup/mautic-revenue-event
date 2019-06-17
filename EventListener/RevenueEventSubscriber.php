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

use Mautic\WebhookBundle\EventListener\WebhookSubscriberBase;
use MauticPlugin\MauticRevenueEventBundle\MauticRevenueEventEvents;
use MauticPlugin\MauticRevenueEventBundle\Event\RevenueChangeEvent;

class RevenueEventSubscriber extends WebhookSubscriberBase
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            MauticRevenueEventEvents::REVENUE_CHANGE => 'onRevenueChange'
        ];
    }

    /**
     * @param RevenueEventContextEvent $event
     */
    public function onRevenueChange(RevenueChangeEvent $event)
    {
        $payload = $event->getPayload();

        //trigger_error(json_encode($payload), E_USER_WARNING);

        $webhookEvent = $this->getEventWebooksByType(MauticRevenueEventEvents::REVENUE_CHANGE);

        $this->webhookModel->queueWebhooks($webhookEvent, $payload);
    }
}
