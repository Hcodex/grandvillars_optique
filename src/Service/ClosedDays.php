<?php

namespace App\Service;

use DateTime;

class ClosedDays
{
    public $data = "test";

    public function getHollydays()
    {

        $year = intval(date('Y'));

        $easterDate  = easter_date($year);
        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $holidays = array(
            // These days have a fixed date
            date('Y-m-d', mktime(0, 0, 0, 1,  1,  $year)),  // 1er janvier
            date('Y-m-d',mktime(0, 0, 0, 5,  1,  $year)),  // Fête du travail
            date('Y-m-d',mktime(0, 0, 0, 5,  8,  $year)),  // Victoire des alliés
            date('Y-m-d',mktime(0, 0, 0, 7,  14, $year)),  // Fête nationale

            //a supprimer
            date('Y-m-d',mktime(0, 0, 0, 7,  6, $year)),  // Fête nationale
            date('Y-m-d',mktime(0, 0, 0, 7,  8, $year)),  // Fête nationale

            date('Y-m-d',mktime(0, 0, 0, 8,  15, $year)),  // Assomption
            date('Y-m-d',mktime(0, 0, 0, 11, 1,  $year)),  // Toussaint
            date('Y-m-d',mktime(0, 0, 0, 11, 11, $year)),  // Armistice
            date('Y-m-d',mktime(0, 0, 0, 12, 25, $year)),  // Noel

            // These days have a date depending on easter
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 2,  $easterYear)),
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear)),
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)),
        );

        sort($holidays);

        return $holidays;
    }
}
