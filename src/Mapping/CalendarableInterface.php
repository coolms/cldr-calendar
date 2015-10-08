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

interface CalendarableInterface
{
    /**
     * @param CalendarInterface $calendar
     */
    public function setCalendar(CalendarInterface $calendar);

    /**
     * @return CalendarInterface
     */
    public function getCalendar();
}
