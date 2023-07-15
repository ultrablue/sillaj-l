<?php

/**
 * Convert time into decimal time.
 * The sign is carried by the hour part. The sign of the minutes and seconds parts are ignored.
 * 10:-10:-10 doesn't make sense. -10:10:10 does make sense.
 * Minutes can be a float.
 *
 * @param $h Hours
 * @param $m Minutes
 * @param $s Seconds
 *
 * @return float the time as a decimal value
 */
function hmsToDecimal($h, $m, $s = 0)
{
    // Get the sign of the hour part.
    $sign = ($h > 0) ? 1 : (($h < 0) ? -1 : 0);
    // Do the deed.
    $decTime = $sign * (abs($h) + ((abs($m) + (abs($s) / 60)) / 60));

    // The result should be a float.
    return $decTime;
}

/**
 *  Convert a decimal number to hours, minutes, seconds format.
 *  The algorithm is adapted from this one:
 *  https://www.web-max.ca/PHP/misc_6.php
 *  I prefer this approach because string maniupalation helps to overcome rounding errors.
 *
 * @param $decimalNumber the number to convert
 */
function decimalToHms(float $decimalNumber)
{
    // Tests:
    //    input is a float.
    //               string
    //             an int.

    $decimalNumber = floatval($decimalNumber);
    // (float) ($decimalNumber);
    // dump((string) $decimalNumber);
    // dump(gettype($decimalNumber));

    // Stringify the intiger part and the decimal part. Splitting on the decimal point keeps things neat and avoids rounding errors.
    $vars = explode('.', $decimalNumber);
    if (count($vars) === 1) {
        $vars[1] = '0';
    }
    // dd($vars);
    $hours = (int) $vars[0];
    $tempma = '0.' . $vars[1];

    $tempma = $tempma * 3600;
    $minutes = (int) floor($tempma / 60);
    $seconds = (int) $tempma - ($minutes * 60);

    return [$hours, $minutes, $seconds];
}

/**
 * Converts seconds to hh:mm. For example 3601 seconds becomes 01:01.
 *
 * @param integer $seconds
 * @return string
 */
function secondsToHm(int $seconds): string
{
    return Carbon\CarbonInterval::seconds($seconds)->cascade()->format('%h:%I');
}
