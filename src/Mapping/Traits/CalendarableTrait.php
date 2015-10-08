<?php
/**
 * CoolMS2 CLDR Calendar Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/cldr-calendar for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsCldrCalendar\Mapping\Traits;

use ArrayObject,
    Zend\Form\Annotation as Form,
    CmsCldrCalendar\Mapping\CalendarInterface;

/**
 * CalendarableTrait
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait CalendarableTrait
{
    /**
     * @var CalendarInterface
     *
     * @todo ToMany relation on calendar property is temporary solution.
     *       Actually we need a ToOne relation with a appropriate territory.
     *       {@see https://github.com/doctrine/doctrine2/pull/970)
     *
     * @Form\Exclude()
     */
    protected $calendar = [];

    /**
     * __construct
     */
    public function __construct()
    {
        $this->calendar = new ArrayObject($this->calendar);
    }

    /**
     * @param CalendarInterface $calendar
     */
    public function setCalendar(CalendarInterface $calendar)
    {
        foreach ($this->calendar as $key => $data) {
            unset($this->calendar[$key]);
        }

        $this->calendar[] = $calendar;
    }

    /**
     * @return CalendarInterface
     */
    public function getCalendar()
    {
        foreach ($this->calendar as $calendar) {
            return $calendar;
        }
    }
}
