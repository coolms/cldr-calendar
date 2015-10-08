<?php
/**
 * CoolMS2 CLDR Calendar Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/cldr-calendar for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsCldrCalendar\Stdlib;

use DateTime,
    Traversable,
    Zend\Stdlib\ArrayUtils,
    CmsCalendar\Stdlib\CalendarUtils as BaseCalendarUtils,
    CmsCldrCalendar\Holiday,
    CmsCldrCalendar\HolidayInterface,
    CmsCldrCalendar\HolidayRuleInterface,
    CmsCldrCalendar\TransferredRuleInterface;

/**
 * Declared abstract, as we have no need for instantiation.
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
abstract class CalendarUtils extends BaseCalendarUtils
{
    const FILTER_HOLIDAY_DAY_OFF        = 1;
    const FILTER_HOLIDAY_WEEKDAY        = 4;
    const FILTER_HOLIDAY_WEEKEND        = 8;
    const FILTER_HOLIDAY_ALL            = 15;

    const FILTER_WEEKEND_DAY_OFF        = 1;
    const FILTER_WEEKEND_TRANSFERRED    = 2;
    const FILTER_WEEKEND_ALL            = 3;

    /**
     * @var string
     */
    protected static $defaultHolidayClass = Holiday::class;

    /**
     * {@inheritDoc}
     *
     * @param array $weekend
     * @param int $filter
     */
    public static function getHolidays(
        $holidayRules,
        $startDate = null,
        $endDate = null,
        array $weekend = [],
        $filter = self::FILTER_HOLIDAY_ALL
    ) {
        if ($holidayRules instanceof Traversable) {
            $holidayRules = ArrayUtils::iteratorToArray($holidayRules, false);
        }

        if (!is_array($holidayRules)) {
            throw new \InvalidArgumentException(sprintf(
                'Holidays must be type of array or Traversable; %s given',
                is_object($holidayRules) ? get_class($holidayRules) : gettype($holidayRules)
            ));
        }

        foreach ($holidayRules as $key => $holidayRule) {
            if (!$holidayRule instanceof HolidayRuleInterface) {
                throw new \InvalidArgumentException(sprintf(
                    '$holidayRule must be instance of %s; %s given',
                    HolidayRuleInterface::class,
                    is_object($holidayRule) ? get_class($holidayRule) : gettype($holidayRule)
                ));
            }

            if ($filter == static::FILTER_HOLIDAY_ALL) {
                continue;
            }

            if ((bool)($filter & static::FILTER_HOLIDAY_DAY_OFF) !== $holidayRule->getIsDayOff()) {
                unset($holidayRules[$key]);
            }
        }

        $indexDate = new DateTime();
        $holidays  = parent::getHolidays($holidayRules, $startDate, $endDate);
        foreach ($holidays as $index => $holiday) {
            /* @var $subHoliday HolidayInterface */
            foreach ($holiday as $key => $subHoliday) {
                /* @var $holidayRule HolidayRuleInterface */
                $holidayRule = $subHoliday->getRule();
                if ($holidayRule->getFromYear() && $holidayRule->getFromYear() > $index ||
                   ($holidayRule->getToYear()   && $holidayRule->getToYear()   < $index)
                ) {
                    unset($holidays[$index][$key]);
                    continue;
                }

                if (null === $subHoliday->isObserved() &&
                    HolidayRuleInterface::WA_DISABLED !== $holidayRule->getWeekendAdjust() &&
                    $subHoliday->getDate() != $subHoliday->getObservedDate()
                ) {
                    $subHoliday->setIsObserved(false);
                    $index = $subHoliday->getObservedDate()->format('Y-m-d');
                    $observed = clone $subHoliday;
                    $observed->setIsObserved(true);
                    $holidays[$index][] = $observed;
                }

                if ($filter == static::FILTER_HOLIDAY_ALL) {
                    continue;
                }

                $indexDate->modify($index);
                if (!($filter & static::FILTER_HOLIDAY_WEEKDAY)
                    && !in_array($indexDate->format('w'), $weekend)
                ) {
                    unset($holidays[$index][$key]);
                    continue;
                }

                if (!($filter & static::FILTER_HOLIDAY_WEEKEND)
                    && in_array($indexDate->format('w'), $weekend)
                ) {
                    unset($holidays[$index][$key]);
                }
            }

            if (!$holidays[$index]) {
                unset($holidays[$index]);
            }
        }

        ksort($holidays);

        return $holidays;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $weekend
     * @param int $filter
     */
    public static function getNextHoliday(
        $holidayRules,
        $date = null,
        array $weekend = [],
        $filter = self::FILTER_HOLIDAY_ALL
    ) {
        return parent::getNextHoliday($holidayRules, $date, $weekend, $filter);
    }

    /**
     * {@inheritDoc}
     *
     * @param array $weekend
     * @param int $filter
     */
    public static function getPreviousHoliday(
        $holidayRules,
        $date = null,
        array $weekend = [],
        $filter = self::FILTER_HOLIDAY_ALL
    ) {
        return parent::getPreviousHoliday($holidayRules, $date, $weekend, $filter);
    }

    /**
     * {@inheritDoc}
     *
     * @param array<HolidayRuleInterface>|Traversable $holidayRules
     * @param int $filter
     */
    public static function getWeekendDays(
        $startDate,
        $endDate,
        array $weekend,
        $holidayRules = [],
        $filter = self::FILTER_WEEKEND_ALL
    ) {
        $days = parent::getWeekendDays($startDate, $endDate, $weekend);

        if (!$holidayRules || $filter == static::FILTER_WEEKEND_ALL) {
            return $days;
        }

        $holidays = static::getHolidays(
            $holidayRules,
            $startDate,
            $endDate,
            $weekend,
            static::FILTER_HOLIDAY_DAY_OFF | static::FILTER_HOLIDAY_WEEKDAY
        );

        $transferredWeekendDays = [];
        /* @var $subHoliday HolidayInterface */
        foreach ($holidays as $holiday) {
            foreach ($holiday as $subHoliday) {
                if ($subHoliday->getRule() instanceof TransferredRuleInterface) {
                    $transferredWeekendDays[] = $subHoliday->getRule()->getWeekendDay()->format('Y-m-d');
                    continue 2;
                }
            }
        }

        if (!$transferredWeekendDays) {
            return $days;
        }

        $flippedTransferredWeekendDays = array_flip($transferredWeekendDays);

        if ($filter & static::WEEKEND_TRANSFERRED) {
            $days = array_intersect_key($days, $flippedTransferredWeekendDays);
        }

        if ($filter & static::WEEKEND_DAY_OFF) {
            $days = array_diff_key($days, $flippedTransferredWeekendDays);
        }

        return $days;
    }
}
