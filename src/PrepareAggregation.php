<?php

namespace Yosmy\Mongo;

use LogicException;
use DateTime;
use DatePeriod;
use DateInterval;
use DateTimeZone;

/**
 * @di\service()
 */
class PrepareAggregation
{
    const GROUP_BY_DAY = 'by-day';
    const GROUP_BY_MONTH = 'by-month';
    const GROUP_BY_YEAR = 'by-year';

    /**
     * @param int    $lower
     * @param int    $greater
     * @param string $timezone
     * @param string $group
     * @param array  $response
     *
     * @return array
     */
    public function prepare(
        ?int $lower,
        ?int $greater,
        string $timezone,
        string $group,
        array $response
    ) {
        switch ($group) {
            case self::GROUP_BY_DAY:
                $stats = $this->initializeByDay($response, $lower, $greater, $timezone);

                $stats = $this->fillByDay($response, $stats);

                $stats = $this->flatByDay($stats);

                break;
            case self::GROUP_BY_MONTH:
                $stats = $this->initializeByMonth($response, $lower, $greater, $timezone);

                $stats = $this->fillByMonth($response, $stats);

                $stats = $this->flatByMonth($stats);

                break;
            default:
                throw new LogicException();
        }

        return $stats;
    }

    /**
     * @param array  $response
     * @param int    $from
     * @param int    $to
     * @param string $timezone
     *
     * @return array
     */
    private function initializeByDay(
        array $response, 
        ?int $from, 
        ?int $to,
        string $timezone
    ) {
        /* Find lower and greater dates */

        $lower = new DateTime();
        $lower->setTimezone(new DateTimeZone($timezone));
        $lower->setDate(
            $response[0]['_id']['year'],
            $response[0]['_id']['month'],
            $response[0]['_id']['day']
        );

        if ($lower->getTimestamp() > $from) {
            $lower = new DateTime();
            $lower->setTimezone(new DateTimeZone($timezone));
            $lower->setTimestamp($from);
        }

        $greater = new DateTime();
        $greater->setTimezone(new DateTimeZone($timezone));
        $greater->setDate(
            $response[count($response) - 1]['_id']['year'],
            $response[count($response) - 1]['_id']['month'],
            $response[count($response) - 1]['_id']['day']
        );

        if ($greater->getTimestamp() < $to) {
            $greater = new DateTime();
            $greater->setTimezone(new DateTimeZone($timezone));
            $greater->setTimestamp($to);
        }

        $empty = [];
        foreach ($response[0] as $field => $value) {
            if ($field == '_id') {
                continue;
            }

            $empty[$field] = 0;
        }

        $stats = [];
        $period = new DatePeriod($lower, dateInterval::createFromDateString('1 day'), $greater);
        foreach ($period as $day) {
            /** @var DateTime $day */
            $stats[$day->format("Y")][$day->format("n")][$day->format("j")] = $empty;
        }

        return $stats;
    }

    /**
     * @param array $response
     * @param array $stats
     *
     * @return array
     */
    private function fillByDay($response, $stats)
    {
        foreach ($response as $item) {
            $id = $item['_id'];
            unset($item['_id']);

            $stats[$id['year']][$id['month']][$id['day']] = $item;
        }

        return $stats;
    }

    /**
     * @param array $stats
     *
     * @return array
     */
    private function flatByDay(array $stats)
    {
        $flat = [];
        foreach ($stats as $year => $months) {
            foreach ($months as $month => $days) {
                foreach ($days as $day => $fields) {
                    $flat[] = array_merge([
                        'year' => $year,
                        'month' => $month,
                        'day' => $day,
                    ], $fields);
                }
            }
        }

        return $flat;
    }

    /**
     * @param array $response
     * @param int   $from
     * @param int   $to
     * @param string $timezone
     *
     * @return array
     */
    private function initializeByMonth(
        array $response,
        ?int $from,
        ?int $to,
        string $timezone
    ) {
        /* Find lower and greater dates */

        $lower = new DateTime();
        $lower->setTimezone(new DateTimeZone($timezone));
        $lower->setDate(
            $response[0]['_id']['year'],
            $response[0]['_id']['month'],
            1
        );

        if ($lower->getTimestamp() > $from) {
            $lower = new DateTime();
            $lower->setTimezone(new DateTimeZone($timezone));
            $lower->setTimestamp($from);
        }

        $greater = new DateTime();
        $greater->setTimezone(new DateTimeZone($timezone));
        $greater->setDate(
            $response[count($response) - 1]['_id']['year'],
            $response[count($response) - 1]['_id']['month'],
            1
        );

        if ($greater->getTimestamp() < $to) {
            $greater = new DateTime();
            $greater->setTimezone(new DateTimeZone($timezone));
            $greater->setTimestamp($to);
        }

        $empty = [];
        foreach ($response[0] as $field => $value) {
            if ($field == '_id') {
                continue;
            }

            $empty[$field] = 0;
        }

        $stats = [];
        $period = new DatePeriod($lower, dateInterval::createFromDateString('1 month'), $greater);
        foreach ($period as $month) {
            /** @var DateTime $month */
            $stats[$month->format("Y")][$month->format("n")] = $empty;
        }

        return $stats;
    }

    /**
     * @param array $response
     * @param array $stats
     *
     * @return array
     */
    private function fillByMonth($response, $stats)
    {
        foreach ($response as $item) {
            $id = $item['_id'];
            unset($item['_id']);

            $stats[$id['year']][$id['month']] = $item;
        }

        return $stats;
    }

    /**
     * @param array $stats
     * @return array
     */
    private function flatByMonth(array $stats)
    {
        $flat = [];
        foreach ($stats as $year => $months) {
            foreach ($months as $month => $fields) {
                $flat[] = array_merge([
                    'year' => $year,
                    'month' => $month,
                ], $fields);
            }
        }

        return $flat;
    }
}
