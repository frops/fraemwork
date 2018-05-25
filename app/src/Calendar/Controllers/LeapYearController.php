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
            $response = new Response('Yep, this is a leap year! ' . $year);
        } else {
            $response = new Response('Nope, this is not a leap year: ' . $year);
        }

        $response->setContent(
            $response->getContent() .
            "The computed content of the response"
        );

        if ($response->isNotModified($request)) {
            return $response;
        }

        $response->setCache([
            'public' => true,
            'etag' => 'abcde',
            'last_modified' => date_create_from_format('Y-m-d H:i:s', '2005-10-15 10:00:00'),
            'max_age' => 10,
            's_maxage' => 10,
        ]);

        return $response;
    }
}