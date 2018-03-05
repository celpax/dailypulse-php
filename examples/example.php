<?php

require 'vendor/autoload.php';

use Celpax\Dailypulse\Client;

DEFINE('ACCESS_KEY_ID','your-access-key-here');
DEFINE('SECRET_ACCESS_KEY','your-secret-access-key-here');

$dailyPulseClient=new Client(ACCESS_KEY_ID,SECRET_ACCESS_KEY);

// Retrieve the first site
$response=$dailyPulseClient->getSites();
$site_id=$response->json()[0]->id;

// Get the mood KPI
$response=$dailyPulseClient->getMoodKPI($site_id);
$green=$response->json()->green;

?>

<html>
 <head>
  <title>DailyPulse PHP Example</title>
 </head>
 <body>
 <p> Your mood KPI is <?=$green?>% Green!</p>
 </body>
</html>
