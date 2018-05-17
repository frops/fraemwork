<?php

namespace App\Controllers;

//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ildar Asanov <ifrops@gmail.com>
 */

class LeapYearController
{
    public function index($year = 2012)
    {
        if ($this->isLeapYear($year)) {
            return new Response('Yep, this is a leap year! ' . $year);
        }

        return new Response('Nope, this is not a leap year: ' . $year);
    }

    private function isLeapYear($year = null): bool
    {
        if (null === $year) {
            $year = date('Y');
        }

        return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
    }
}