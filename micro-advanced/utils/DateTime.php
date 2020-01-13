<?php
/**
 * Advanced microFramework
 * -
 * @copyright Copyright (c) 2019 Advanced microFramework
 * @author    Advanced microFramework Team (Denzel Code, Soull Darknezz)
 */

namespace advanced\utils;

use DateTimeZone;
use advanced\Bootstrap;

/**
 * DateTime class
 */
class DateTime extends \DateTime {

    public $format = 'm-d-Y H:i:s'; // F jS, Y

    public function __construct(string $time = 'now', DateTimeZone $timezone = null) {
        parent::__construct($time, $timezone);
    }

    public function getFormat() : string {
        return $this->format;
    }

    public function setFormat(string $format) {
        $this->format = $format;
    }

    public function getAgo() : string {
        $time = (new DateTime())->getTimestamp() - $this->getTimestamp();

        $lang = Bootstrap::getLanguage(false)->get('time.ago');

        if ($time < 1) return $lang['just_now'];

        $string = [365 * 24 * 60 * 60 => 'year', 30 * 24 * 60 * 60 => 'month', 24 * 60 * 60 => 'day', 60 * 60 => 'hour', 60 => 'minute', 1 => 'second'];

        foreach ($string as $seconds => $str) {
            $d = $time / $seconds;

            if ($d >= 1) {
                $r = round($d);

                return $r . ' ' . ($r > 1 ? $lang['plural'][$str] : $lang['singular'][$str]) . ' ' . $lang['ago'];
            }
        }
    }
}