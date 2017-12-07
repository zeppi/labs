<?php
/**
 * PHP versions 5.3.8
 *
 * @copyright  2013 Zeppi <giuslee@gmail.com>
 * @author     giuslee@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace  ZpBiorythme\Models;

/**
 * Class Birthday
 *
 * Extend DateTime for beter implementation Exception
 *
 * @package App\Models
 * @author     giuslee@gmail.com
 */
class Birthday extends \DateTime
{
    /**
     * __construct
     *
     * @param string $time
     * @param \DateTimeZone $timezone
     * @throws BirthdayExcpetion
     *
     * @see \DateTime
     */
    public function __construct ($time='now', \DateTimeZone $timezone = null)
    {
        try
        {
            if(is_null($timezone))
            {
                parent::__construct($time);
            }
            else
            {
                parent::__construct($time, $timezone);
            }
        }
        catch(\Exception $e)
        {
            throw new BirthdayExcpetion($e->getMessage(), $e->getCode(), $e);
        }
    }
}

