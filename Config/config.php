<?php
/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Revenue Event Bundle Configurations
 */
return [
    'name'        => 'Revenue Event',
    'description' => 'W4-Engine Tracking for Lead Attribution Changes.',
    'version'     => '0.0.2',
    'author'      => 'Weston Watson',
    'services' => [
        'helpers' => [
            'mautic.revenueevent.helper.integrationsettings' => [
                'class'     => \MauticPlugin\MauticRevenueEventBundle\Helper\IntegrationSettings::class,
                'arguments' => [
                    'mautic.helper.integration',
                    'doctrine.orm.entity_manager'
                ]
            ]
        ],
        'events' => [
            'mautic.revenueevent.change.subscriber'       => [
                'class' => \MauticPlugin\MauticRevenueEventBundle\EventListener\RevenueEventSubscriber::class,
            ],
            'mautic.revenueevent.campaignform.subscriber' => [
                'class'     => \MauticPlugin\MauticRevenueEventBundle\EventListener\CampaignFormSubscriber::class,
                'arguments' => ['mautic.revenueevent.helper.integrationsettings']
            ],
        ],
        'extension' => [
            'mautic.revenueevent.campaign.form' => [
                'class'        => \MauticPlugin\MauticRevenueEventBundle\Form\CampaignRevenueEventToggle::class,
                'arguments'    => [
                    'mautic.revenueevent.helper.integrationsettings',
                ],
                'tag'          => 'form.type_extension',
                'tagArguments' => [
                    'extended_type' => \Mautic\CampaignBundle\Form\Type\CampaignType::class,
                ],
            ],
    ],
    'categories' => [],
    ]
];
