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

use Mautic\PluginBundle\Entity\Integration;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\PluginBundle\Integration\AbstractIntegration;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;

/**
 * Class Api.
 */
class IntegrationSettings
{
    /** @var IntegrationHelper */
    protected $integrationHelper;

    /** @var array */
    private $integrationEntityFeatureSettings;

    /** @var AbstractIntegration */
    private $integrationObject;

    /** @var Integration $integrationEntity */
    private $integrationEntity;

    /**
     * Api constructor.
     *
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(
        IntegrationHelper $integrationHelper
    ) {
        $this->integrationHelper = $integrationHelper;
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
            if (isset($this->integrationEntityFeatureSettings[$key])) {
                return $this->integrationEntityFeatureSettings[$key];
            } else {
                return $default;
            }
        } else {
            return $this->integrationEntityFeatureSettings;
        }
    }

    /**
     * @return array|mixed
     */
    private function loadIntegrationSettings()
    {
        if (null === $this->integrationEntityFeatureSettings) {
            $this->integrationEntityFeatureSettings = [];
            $this->integrationObject                = $this->integrationHelper->getIntegrationObject(
                RevenueEventIntegration::NAME
            );
            /** @var Integration $integrationEntity */
            $this->integrationEntity = $this->integrationObject->getIntegrationSettings();
            if ($this->integrationEntity) {
                $this->integrationEntityFeatureSettings = $this->integrationEntity->getFeatureSettings();
            }
        }

        return $this->integrationEntityFeatureSettings;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function setIntegrationSetting($key, $value)
    {
        $this->loadIntegrationSettings();

        $this->integrationEntityFeatureSettings = array_merge(
            $this->integrationEntityFeatureSettings,
            [$key => $value]
        );
        $this->integrationEntity->setFeatureSettings($this->integrationEntityFeatureSettings);
        $this->integrationObject->setIntegrationSettings($this->integrationEntity);
        $this->integrationObject->persistIntegrationSettings();

        return $this->integrationEntityFeatureSettings;
    }

}
