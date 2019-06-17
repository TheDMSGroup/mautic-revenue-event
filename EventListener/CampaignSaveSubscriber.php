<?php
/**
 * Created by PhpStorm.
 * User: westonwatson
 * Date: 2019-06-17
 * Time: 13:59
 */

namespace MauticPlugin\MauticRevenueEventBundle\EventListener;

use Mautic\CampaignBundle\Event\CampaignEvent;
use Mautic\CampaignBundle\CampaignEvents;
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
     *
     */
    public function onCampaignPreSave(CampaignEvent $campaignEvent)
    {
        $integrationSettings = $this->getAndUpdateCampaignsList($campaignEvent);
        file_put_contents('/Users/westonwatson/test.log', "\n\nCampaigns:\n". json_encode($integrationSettings));
    }

    private function getAndUpdateCampaignsList(CampaignEvent $campaignEvent)
    {
        $campaignId = $campaignEvent->getCampaign()->getId();
        $revenueEventToggleValue = $_POST['campaign']['revenue_event_toggle'];
        $revenueEventCampaigns = $this->integrationSettings->getIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE);
        $revenueEventCampaigns[$campaignId] = $revenueEventToggleValue;

        return $this->integrationSettings->setIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE, $revenueEventCampaigns);
    }
}
