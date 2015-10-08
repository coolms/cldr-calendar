<?php
/**
 * CoolMS2 CLDR Calendar Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/cldr-calendar for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsCldrCalendar;

use DateTime,
    CmsCalendar\HolidayRuleInterface as BaseHolidayRuleInterface;

interface HolidayRuleInterface extends BaseHolidayRuleInterface
{
    const WA_DISABLED       = null;
    const WA_PREV_WEEKDAY   = -1;
    const WA_CLOSER_WEEKDAY = 0;
    const WA_NEXT_WEEKDAY   = 1;

    /**
     * @param int $year
     * @return DateTime
     */
    public function getObservedDate($year);

    /**
     * @return int
     */
    public function getFromYear();

    /**
     * @return int
     */
    public function getToYear();

    /**
     * @return int
     */
    public function getWeekendAdjust();

    /**
     * @return bool
     */
    public function getIsDayOff();
}
