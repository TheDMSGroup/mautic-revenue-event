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

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use MauticPlugin\MauticRevenueEventBundle\Event\RevenueChangeEvent;
use MauticPlugin\MauticRevenueEventBundle\Helper\IntegrationSettings;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;
use MauticPlugin\MauticRevenueEventBundle\MauticRevenueEventEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LeadSubscriber.
 */
class LeadSubscriber extends CommonSubscriber
{
    /** @var \MauticPlugin\MauticContactLedgerBundle\EventListener\ContactLedgerContextSubscriber */
    protected $context;

    /**
     * @var IntegrationSettings
     */
    private $integrationSettings;

    /**
     * LeadSubscriber constructor.
     */
    public function __construct($integrationSettings = null)
    {
        $this->integrationSettings = $integrationSettings;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_POST_SAVE => ['postSaveAttributionCheck', -2],
        ];
    }

    /**
     * @param \Mautic\LeadBundle\Event\LeadEvent $event
     */
    public function postSaveAttributionCheck(LeadEvent $event)
    {
        $container = $event->getDispatcher()->getContainer();
        $this->context = $this->container->get('@mautic.contactledger.subscriber.context_create', ContainerInterface::IGNORE_ON_INVALID_REFERENCE);

        $lead = $event->getLead();

        //Only send events for configured campaigns
        if (!$this->checkForValidCampaign()) {
            return;
        }

        //Ensure that attribution has changed
        if (!$this->checkForAttributionChange($lead)) {
            return;
        }

        //Only send event if lead has clickId
        if ($clickId = $lead->getFieldValue('clickid')) {
            $this->dispatchRevenueEvent(
                $this->campaign()->getId(),
                $lead->getId(),
                $clickId,
                $lead->getAttribution()
            );
        }
    }

    /**
     * @return bool
     */
    private function checkForValidCampaign()
    {
        if (!$this->campaign()) {
            return false;
        }

        $campaignId   = $this->campaign()->getId();
        $campaignList = $this->integrationSettings->getIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE);

        if (array_key_exists($campaignId, $campaignList ? $campaignList : [])) {
            return $campaignList[$campaignId];
        }

        return false;
    }

    /**
     * @param $lead
     *
     * @return bool
     */
    private function checkForAttributionChange($lead)
    {
        $changes = $lead->getChanges(false);

        if (!isset($changes['fields']) || !isset($changes['fields']['attribution'])) {
            $changes = $lead->getChanges(true);
        }

        if (isset($changes['fields']) && isset($changes['fields']['attribution'])) {
            $oldValue = $changes['fields']['attribution'][0];
            $newValue = $changes['fields']['attribution'][1];

            // Ensure this is the latest change, even if it came from the PastChanges array on the contact.
            if ($oldValue !== $newValue && $newValue === $lead->getAttribution()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return |null
     */
    private function campaign()
    {
        return $this->context ? $this->context->getCampaign() : null;
    }

    /**
     * @param      $cid
     * @param      $refid
     * @param      $clickid
     * @param      $price
     * @param bool $performance
     */
    private function dispatchRevenueEvent($cid, $refid, $clickid, $price, $performance = false)
    {
        $payload = [
            'cid'     => $cid,
            'refid'   => $refid,
            'clickid' => $clickid,
            'price'   => $price,
        ];

        if ($performance) {
            $payload['performance'] = 'true';
        }

        $endpoint = $this->integrationSettings->getIntegrationSetting(RevenueEventIntegration::ENDPOINT_SETTING_NAMESPACE);

        $this->dispatcher->dispatch(MauticRevenueEventEvents::REVENUE_CHANGE, (new RevenueChangeEvent($payload, $endpoint)));
    }
}
