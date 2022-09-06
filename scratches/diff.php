<?php

use Illuminate\Support\Carbon;

$lastWeek = Carbon::parse('last week monday');

$diff = $lastWeek->diff();

dd($diff);
