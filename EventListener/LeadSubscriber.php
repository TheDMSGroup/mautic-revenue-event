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
use MauticPlugin\MauticContactLedgerBundle\EventListener\ContactLedgerContextSubscriber;

/**
 * Class LeadSubscriber.
 */
class LeadSubscriber extends CommonSubscriber
{
    /** @var RevenueEventContextSubscriber */
    protected $context;

    /**
     * @var IntegrationSettings
     */
    private $integrationSettings;

    /**
     * LeadSubscriber constructor.
     *
     * @param RevenueEventContextSubscriber|null $context
     */
    public function __construct($context = null){ //, IntegrationSettings $integrationSettings) {
        $this->context = $context;
        //$this->integrationSettings = $integrationSettings;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_POST_SAVE => ['postSaveAttributionCheck', -1],
        ];
    }

    /**
     * @param \Mautic\LeadBundle\Event\LeadEvent $event
     */
    public function postSaveAttributionCheck(LeadEvent $event)
    {
        $lead = $event->getLead();

        if (!$this->checkForValidCampaign()) {
            return;
        }

        if ($this->checkForAttributionChange($lead)) {
            $this->dispatchRevenueEvent(
                $this->campaign()->getId(),
                $lead->getId(), //TODO: change to Event/Ledger ID?
                $lead->getFieldValue('clickid'), //TODO: check for clickID before attempting to send post
                $lead->getAttribution()
            );
        }
    }

    /**
     * @return bool
     */
    private function checkForValidCampaign()
    {
        return true;
        $campaign_list = $this->integrationSettings->getIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE);

        return ($this->campaign()->getId() && $campaign_list[$this->campaign()->getId()]);
    }

    /**
     * @param $lead
     *
     * @return bool
     */
    private function checkForAttributionChange($lead)
    {
        $changes = $lead->getChanges(false);

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
     * @param $cid
     * @param $refid
     * @param $clickid
     * @param $price
     */
    private function dispatchRevenueEvent($cid, $refid, $clickid, $price)
    {
        $payload = [
            'cid'     => $cid,
            'refid'   => $refid,
            'clickid' => $clickid,
            'price'   => $price,
        ];

        $event = new RevenueChangeEvent($payload);

        $this->dispatcher->dispatch(MauticRevenueEventEvents::REVENUE_CHANGE, $event);
    }
}
