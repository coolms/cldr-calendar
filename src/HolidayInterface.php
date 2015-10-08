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
    CmsCalendar\HolidayInterface as BaseHolidayInterface;

interface HolidayInterface extends BaseHolidayInterface
{
    /**
     * @return DateTime;
     */
    public function getObservedDate();

    /**
     * @return null|bool
     */
    public function isObserved();

    /**
     * @param null|bool $flag
     */
    public function setIsObserved($flag);
}
