<?php

namespace MauticPlugin\MauticRevenueEventBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use MauticPlugin\MauticRevenueEventBundle\Event\RevenueChangeEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RevenueEventIntegration extends AbstractIntegration
{
    /**
     * Integrations Settings Namespace for Campaign List.
     */
    const CAMPAIGN_SETTINGS_NAMESPACE = 'campaigns';

    /**
     * Translations Label Text.
     */
    const ENDPOINT_SETTING_LABEL = 'mautic.revenueevent.integration.endpoint.label';

    /**
     * Translations Tooltip Text.
     */
    const ENDPOINT_SETTING_TOOLTIP = 'mautic.revenueevent.integration.endpoint.tooltip';

    /**
     * Form Extension Namespace.
     */
    const ENDPOINT_SETTING_NAMESPACE = 'revenueevent_conversion_tracker_endpoint';

    /**
     * Integration Display Name.
     */
    const DISPLAY_NAME = 'Revenue Event';

    /**
     * Integration Name.
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

    /**
     * @param        $builder
     * @param array  $data
     * @param string $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ('features' === $formArea) {
            $builder->add(
                self::ENDPOINT_SETTING_NAMESPACE,
                TextType::class,
                [
                    'label'       => self::ENDPOINT_SETTING_LABEL,
                    'label_attr'  => [
                        'class' => 'control-label',
                    ],
                    'data'        => isset($data[self::ENDPOINT_SETTING_NAMESPACE]) ? (string) $data[self::ENDPOINT_SETTING_NAMESPACE] : RevenueChangeEvent::W4_API_ENDPOINT,
                    'attr'        => [
                        'class'   => 'form-control',
                        'tooltip' => $this->translator->trans(self::ENDPOINT_SETTING_TOOLTIP),
                    ],
                ]
            );
        }
    }
}
