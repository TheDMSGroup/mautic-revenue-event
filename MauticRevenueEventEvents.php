<?php
/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticRevenueEventBundle;

/**
 * Class MauticRevenueEventEvents.
 */
final class MauticRevenueEventEvents
{
    /**
     * Listening for attribution changes triggers PixelFire event to Engine Track Conversion Service.
     */
    const REVENUE_CHANGE = 'mautic.contactledger.revenue.change';
}
