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

use Mautic\FormBundle\Event\SubmissionEvent;
use Mautic\FormBundle\Event\FormBuilderEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\FormBundle\FormEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;
use MauticPlugin\MauticRevenueEventBundle\Helper\IntegrationSettings;

class CampaignFormSubscriber extends CommonSubscriber
{
    private $integrationSettings;
    private $integrationHelper;
    private $integrationObject;

    /**
     * CampaignFormSubscriber constructor.
     *
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(IntegrationSettings $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_ON_SUBMIT => 'onFormSubmit',
            FormEvents::FORM_ON_BUILD  => ['onFormBuild', 0],
        ];
    }

    public function onFormBuild(FormBuilderEvent $event)
    {
        file_put_contents('/Users/westonwatson/test.log', "\n" . __CLASS__ . __LINE__ . "\n" , FILE_APPEND);
    }

    public function onFormSubmit(SubmissionEvent $event)
    {
        file_put_contents('/Users/westonwatson/test.log', "\n" . __CLASS__ . __LINE__ . "\n" , FILE_APPEND);
    }
}
