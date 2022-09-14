<?php

use Illuminate\Support\Carbon;

$dateA = Carbon::parse('today 08:00 am');
$dateB = Carbon::parse('today 10:00 am');

$baseDate = Carbon::parse('today 09:00 am');

//

dd($closestDate->toDateTimeString());
