<?php
/**
 * CoolMS2 CLDR Calendar Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/cldr-calendar for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsCldrCalendar\Mapping;

use CmsCalendar\Mapping\HolidayRuleInterface as BaseHolidayRuleInterface,
    CmsCldrCalendar\HolidayRuleInterface as GeneralHolidayRuleInterface;

interface HolidayRuleInterface extends GeneralHolidayRuleInterface, BaseHolidayRuleInterface
{
    /**
     * @param CalendarInterface $calendar
     */
    public function setCalendar(CalendarInterface $calendar);

    /**
     * @return CalendarInterface
     */
    public function getCalendar();

    /**
     * @param int $year
     */
    public function setFromYear($year);

    /**
     * @param int $year
     */
    public function setToYear($year);

    /**
     * @param int $adjust
     */
    public function setWeekendAdjust($adjust);

    /**
     * @param bool $flag
     */
    public function setIsDayOff($flag);
}
