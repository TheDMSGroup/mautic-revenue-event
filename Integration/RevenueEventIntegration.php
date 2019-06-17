<?php

namespace MauticPlugin\MauticRevenueEventBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

class RevenueEventIntegration extends AbstractIntegration
{
    /**
     * Integration Display Name
     */
    const DISPLAY_NAME = 'Revenue Event';

    /**
     * Integration Name
     */
    const NAME = 'RevenueEvent';

    /**
     * Integration Authentication Type.
     */
    const AUTH_TYPE = 'none';

    /**
     * @return string
     */
    public function getAuthenticationType()
    {
        return self::AUTH_TYPE;
    }

    /**
     * @return array
     */
    public function getSupportedFeatures()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return self::DISPLAY_NAME;
    }
}
