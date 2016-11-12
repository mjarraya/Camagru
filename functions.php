<?php
date_default_timezone_set('Europe/Paris');

function accepted_cred($string)
{
	if(preg_match('/[^a-z_\-0-9]/i', $string))
	{
  		return (FALSE);
	}
	return (TRUE);
}

function make_seed()
{
list($usec, $sec) = explode(' ', microtime());
return (float) $sec + ((float) $usec * 100000);
}

function humanTiming ($time)
{

	$time = time() - $time;
	$time = ($time<1)? 1 : $time;
	$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	}

}

 ?>
