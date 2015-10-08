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

use DateTime,
    CmsCalendar\Mapping\CalendarInterface as BaseCalendarInterface;

interface CalendarInterface extends BaseCalendarInterface
{
    /**
     * @param array $days
     */
    public function setWeekend(array $days);

    /**
     * @return array
     */
    public function getWeekend();

    /**
     * @param DateTime|string|int $startDate
     * @param DateTime|string|int $endDate
     * @return array
     */
    public function getWeekendDays($startDate = null, $endDate = null);

    /**
     * @param DateTime|string|int $date
     * @return DateTime
     */
    public function getNextWeekendDay($date = null);

    /**
     * @param DateTime|string|int $date
     * @return DateTime
     */
    public function getPreviuosWeekendDay($date = null);
}
