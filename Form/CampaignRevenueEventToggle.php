<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRevenueEventBundle\Form;

use Mautic\CampaignBundle\Form\Type\CampaignType;
use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRevenueEventBundle\Helper\IntegrationSettings;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CampaignRevenueEventToggle.
 */
class CampaignRevenueEventToggle extends AbstractTypeExtension
{
    /**
     * @var IntegrationSettings
     */
    private $integrationSettingsHelper;

    /**
     * CampaignFormSubscriber constructor.
     *
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(IntegrationSettings $integrationSettingsHelper)
    {
        $this->integrationSettingsHelper = $integrationSettingsHelper;
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return CampaignType::class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('revenue_event_toggle',
            YesNoButtonGroupType::class,
            [
                'mapped'     => false,
                'data'       => $this->isCampaignActive($options),
                'label'      => 'mautic.revenueevent.campaign.toggle.label',
                'label_attr' => ['class' => 'control-label fr-box'],
                'attr'       => [
                    'class'       => 'form-control',
                    'placeholder' => 'mautic.core.optional',
                    'tooltip'     => 'mautic.revenueevent.campaign.toggle.tooltip',
                ],
            ]
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            ['allow_extra_fields' => true]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'campaignRevenueEventToggle_config';
    }

    /**
     * @param $options
     *
     * @return bool
     */
    private function isCampaignActive($options)
    {
        $campaignId   = (int) $this->getCampaignId($options);
        $campaignList = (array) $this->getIntegrationSettingsCampaignsList();

        if (array_key_exists($campaignId, $campaignList)) {
            return $campaignList[$campaignId];
        }

        return false;
    }

    /**
     * @return array|mixed|string
     */
    private function getIntegrationSettingsCampaignsList()
    {
        return $this->integrationSettingsHelper->getIntegrationSetting(RevenueEventIntegration::CAMPAIGN_SETTINGS_NAMESPACE);
    }

    /**
     * @param $options
     *
     * @return int
     */
    private function getCampaignId($options)
    {
        $action   = $options['action'];
        $exploded = explode('/', $action);

        return (int) $exploded[count($exploded) - 1];
    }
}
