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

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use MauticPlugin\MauticRevenueEventBundle\Helper\IntegrationSettings;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;

class CampaignSaveSubscriber extends CommonSubscriber
{
    const CAMPAIGN_INTEGRATION_SETTINGS_NAMESPACE = 'campaigns';

    private $integrationSettings;

    /**
     * CampaignSaveSubscriber constructor.
     *
     * @param CampaignEvent $campaignEvent
     */
    public function __construct(IntegrationSettings $integrationSettings)
    {
        $this->integrationSettings = $integrationSettings;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CampaignEvents::CAMPAIGN_PRE_SAVE => 'onCampaignPreSave',
        ];
    }

    /**
     * @param CampaignEvent $campaignEvent
     */
    public function onCampaignPreSave(CampaignEvent $campaignEvent)
    {
        $integrationSettings = $this->getAndUpdateCampaignsList($campaignEvent);
    }

    /**
     * @param CampaignEvent $campaignEvent
     *
     * @return array
     */
    private function getAndUpdateCampaignsList(CampaignEvent $campaignEvent)
    {
        $campaignId                         = $campaignEvent->getCampaign()->getId();
        $revenueEventToggleValue            = $_POST['campaign']['revenue_event_toggle'];
        $revenueEventCampaigns              = $this->integrationSettings->getIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE);
        $revenueEventCampaigns[$campaignId] = $revenueEventToggleValue;

        return $this->integrationSettings->setIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE, $revenueEventCampaigns);
    }
}
