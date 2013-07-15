<?php
/**
 * PHP versions 5.3.8
 *
 * @copyright  2013 Zeppi <giuslee@gmail.com>
 * @author     giuslee@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace ZpBiorythme\Models;

/**
 * Biorythme
 *
 * Calculate your biorhythm, i.e.
 * <code>
 * $data =  ZpBiorythme\Models\Biorythme::getBiorythme(new Birthday('your birthday'), new \DateTime('now'));
 * </code>
 *
 * @author     giuslee@gmail.com 
 */
class Biorythme
{
    const MATH_2_PI = 6.2831853071796;

    /**
     * Get biorythme
     *
     * @param Birthday $birthday
     * @param \DateTime $to_date
     * @param int $calc_for_days max 360, min 30
     *
     * @throws \Exception
     * @return array
     */
    public static function getBiorythme(Birthday $birthday, \DateTime $to_date, $calc_for_days = 30)
    {
        if($calc_for_days > 360 || $calc_for_days < 30)
        {
            throw new \Exception('Calc days is out of range');
        }

        $nb_of_days_alive = (int)$to_date->diff($birthday)->format('%a');

        /**
         * Prepare the window calculation
         */
        $median = (int)($calc_for_days/2);
        $start_date = $to_date->sub(\DateInterval::createFromDateString("$median day"));
        $start_days = $nb_of_days_alive - $median;

        // Interval to add in each iteration
        $interval_1_days = \DateInterval::createFromDateString('1 day');

        $datas = array();
        for($i=0; $i < $calc_for_days; $i++)
        {
            $datas[] = array(
                "date" => $start_date->format('Y-m-d'),
                "emotionnel" => self::getPercentCyclicPeriod($start_days, 28),
                "physique" => self::getPercentCyclicPeriod($start_days, 23),
                "intellectuel" => self::getPercentCyclicPeriod($start_days, 33),
                "spirituel" => self::getPercentCyclicPeriod($start_days, 53),
                "intuitif" => self::getPercentCyclicPeriod($start_days, 38),
            );

            $start_date = $start_date->add($interval_1_days);
            $start_days++;
        }

        return $datas;
    }

    /**
     * Get percent cyclic period
     *
     * @param integer $nb_of_days_alive
     * @param integer $cyclic_period
     *
     * @return float
     */
    public static function getPercentCyclicPeriod($nb_of_days_alive, $cyclic_period)
    {
        return round(100 * sin(self::getCyclicPeriod($nb_of_days_alive, $cyclic_period)), 3);
    }

    /**
     * Calculating the part of a cyclic period
     *
     * This is the same that's ($nb_of_days_alive % $cyclic_period)/$cyclic_period
     *
     * @param integer $nb_of_days_alive
     * @param integer $cyclic_period
     *
     * @return float
     */
    public static function getCyclicPeriod($nb_of_days_alive, $cyclic_period)
    {
        $div = $nb_of_days_alive/$cyclic_period;
        return self::MATH_2_PI * ($div - (int)$div);
    }
}
