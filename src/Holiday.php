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
    CmsCalendar\Holiday as BaseHoliday;

class Holiday extends BaseHoliday implements HolidayInterface
{
    /**
     * @var DateTime
     */
    protected $observedDate;

    /**
     * @var null|bool
     */
    protected $isObserved;

    /**
     * {@inheritDoc}
     */
    public function getObservedDate()
    {
        if (null === $this->observedDate) {
            $this->observedDate = clone $this->getRule()->getObservedDate($this->getYear());
        }

        return $this->observedDate;
    }

    /**
     * {@inheritDoc}
     */
    public function isObserved()
    {
        return $this->isObserved;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsObserved($flag)
    {
        $this->isObserved = null === $flag ? $flag : (bool) $flag;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function _reset()
    {
        parent::_reset();
        $this->observedDate = null;
    }
}
