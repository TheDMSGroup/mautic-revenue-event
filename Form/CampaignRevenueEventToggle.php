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

use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use MauticPlugin\MauticRevenueEventBundle\Integration\RevenueEventIntegration;
use Mautic\CampaignBundle\Form\Type\CampaignType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRevenueEventBundle\Helper\IntegrationSettings;

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
        $on_or_off = true;

        $this->integrationSettingsHelper->setIntegrationSetting('campaigns', [1 => false,2 => true,3 => false,4 => false,5 => true,5 => false]);

        $msg = json_encode($this->integrationSettingsHelper->getIntegrationSetting('campaigns'));

        file_put_contents('/Users/westonwatson/test.log', $msg , FILE_APPEND);

        $builder->add('revenue_event_toggle',
            YesNoButtonGroupType::class,
            [
                'mapped' => false,
                'data' => $on_or_off,
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


}
