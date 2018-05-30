<?php

namespace Calendar\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Calendar\Models\LeapYear;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ildar Asanov <ifrops@gmail.com>
 */

class LeapYearController
{
    public function index(Request $request, $year = 2012)
    {
        if ((new LeapYear())->isLeapYear($year)) {
            return'Yep, this is a leap year! ' . $year;
        } else {
           return 'Nope, this is not a leap year: ' . $year;
        }
    }
}