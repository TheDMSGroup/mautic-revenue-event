<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRevenueEventBundle\Helper;

use Doctrine\ORM\EntityManager;
use Mautic\PluginBundle\Entity\IntegrationEntity;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;

/**
 * Class Api.
 */
class IntegrationSettings
{
    /** @var IntegrationHelper */
    protected $integrationHelper;

    private $integrationSettings;

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $integrationObject;

    /**
     * Api constructor.
     *
     * @param IntegrationHelper        $integrationHelper
     */
    public function __construct(
        IntegrationHelper $integrationHelper,
        EntityManager $entityManager
    ) {
        $this->integrationHelper = $integrationHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * Get all global Source integration settings, or a single feature setting.
     *
     * @param string $key
     * @param string $default
     *
     * @return array|mixed|string
     */
    public function getIntegrationSetting($key = '', $default = '')
    {
        $this->loadIntegrationSettings();

        if ($key) {
            if (isset($this->integrationSettings[$key])) {
                return $this->integrationSettings[$key];
            } else {
                return $default;
            }
        } else {
            return $this->integrationSettings;
        }
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return array
     */
    public function setIntegrationSetting($key = '', $value)
    {
        $this->loadIntegrationSettings();

        $this->integrationSettings[$key] = $value;

        $repo = $this->entityManager->getRepository('MauticPluginBundle:IntegrationEntity');
        $repo->saveEntity($this->integrationObject);

        return $this->integrationSettings;
    }

    /**
     * @return array|mixed
     */
    private function loadIntegrationSettings()
    {
        if (null === $this->integrationSettings) {
            $this->integrationSettings = [];
            $this->integrationObject   = $this->integrationHelper->getIntegrationObject(RevenueEventIntegration::NAME);
            $objectSettings            = $this->integrationObject->getIntegrationSettings();
            if ($objectSettings) {
                $this->integrationSettings = $objectSettings->getFeatureSettings();
            }
        }

        return $this->integrationSettings;
    }

}
