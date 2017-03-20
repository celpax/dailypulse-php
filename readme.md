# DailyPulse Client API

This is the Celpax's PHP DailyPulse Client API. Note that:

It matches pretty much the [NodeJS API](https://github.com/celpax/dailypulse) Client.

##  API Client Architecture

- The client connects to the server using JSON/REST web services. 
- All requests run over HTTPS and the URI is signed with a timestamped cryptographic token implemented using HMAC-SHA512.
- The client uses internally [Guzzle](http://guzzle.readthedocs.org) to send the REST requests and parse the received JSON.
- A Response object encapsulates the downloaded JSON, HTTP Status and exception information from either client or server side.

## Installation

The PHP DailyPulse client is distributed as a [composer package](https://packagist.org/packages/celpax/dailypulse).

You can either include the a dependency in your `composer.json` file or use composer to download the code to your project.

To install the package to your project do

```sh
php composer.phar require celpax/dailypulse
```

That will download the package and all the dependencies in to the vendor directory. Remember to:

```php
include 'vendor/autoload.php';
```

If you also use composer, you just need to add the dependency to your `composer.json` as

```json
{
    "require": {
        "celpax/dailypulse": "~1.0"
    }
}
```

Check the [Composer Package Manager](https://getcomposer.org/) for additional information.

## Instanciate the client

In order to instanciate the dailypulse client and start making request you need to do:

```php
use Celpax\Dailypulse\Client;

DEFINE('ACCESS_KEY_ID','your-access-key-here');
DEFINE('SECRET_ACCESS_KEY','your-secret-access-key-here');

$dailyPulseClient=new Client(ACCESS_KEY_ID,SECRET_ACCESS_KEY);
```

You can download your access and secret api keys from DailyPulse dashboard.

## Get your sites

DailyPulse can be deployed on one or more company sites. In either case you will need to know the Site ID before you can download metrics related to it.

You can get your sites as follows:
```php
    $response=$dailyPulseClient->getSites();
    $site_id=$response->json()[0]['id'];
```

## Mood KPI

You can retrieve the latest calculated Mood KPI for a give site as follows:

```php
    $response=$dailyPulseClient->getMoodKPI($site_id);
    $green=$response->json()['green'];
```

Note that in some cases the Mood KPI cannot be calculated (for example during rollout) and will be returned as null. A date member will also be included indicating when the Mood KPI was last updated.

Alternatively, you can retrieve the latest calculated global Mood KPI of all the sites of the account as follows:

```php
    $response=$dailyPulseClient->getGlobalMoodKPI();
    $green=$response->json()['green'];
```
## Historical Mood KPI

You can retrieve the historical calculated Mood KPI for a given site and the number of days to fetch since today as follows:

```php
   $response=$dailyPulseClient->getHistoricalMoodKPI($site_id, $number_of_days);
   $green=$response->json()[0]['green'];
```

The maximum number of allowed days to be fetched can be configured in the Celpax Dashboard console. You need administrator privileges for accessing to the configuration section. 

Alternatively you can retrieve the historical calculated global mood KPI of all the sites of the account as follows:

```php
   $response=$dailyPulseClient->getHistoricalGlobalMoodKPI($number_of_days);
   $green=$response->json()[0]['green'];
```

## Pulses in a Typical Day

Dailypulse will track how many pulses are registered in a typical day. DailyPulse will detect and exclude from this statist days such as weekends in which a couple of people turn up to work, or company parties when there might be an unusual number of pulses.

Again, pulses per typical day might not be calculated for a given site yet, in which case null can be returned.

You can get it in a similar way by doing:

```php
    $response=$dailyPulseClient->getPulsesPerTypicalDay($site_id);
    $pulses=$response->json()['pulses'];
});
```
A date member will also be returned indicating when the pulses per typical day was last updated.

Alternatively, you can retrieve the global pulses in a typical day of all the sites of the account as follows:
 
```php
    $response=$dailyPulseClient->getGlobalPulsesPerTypicalDay();
    $pulses=$response->json()['pulses'];
});
```

## Historical Pulses in a Typical Day

You can retrieve the historical Pulses in a Typical day for a given site and the number of days to fetch since today as follows:

```php
    $response=$dailyPulseClient->getHistoricalPulsesPerTypicalDay($site_id, $number_of_days);
    $pulses=$response->json()[0]['pulses'];
```

The maximum number of allowed days to be fetched can be configured in the Celpax Dashboard console. You need administrator privileges for accessing to the configuration section. 

Alternatively, you can retrieve the historical global pulses per typical day of all the sites of the account as follows:
```php
    $response=$dailyPulseClient->getHistoricalGlobalPulsesPerTypicalDay($number_of_days);
    $pulses=$response->json()[0]['pulses'];
```

## User Interface Design

We have released user interface elements, such as: colours, fonts, widgets available in the github project: [DailyPulse-Resources](https://github.com/celpax/dailypulse-resources)

The resources provided match the User Interface of the DailyPulse Dashboard.

## Testing

An echo test method has also been include so that you can test your setup as much as you want before pulling real data. 

You can use it as follows, for example in one of your unit tests:

```php
    $response=$this->dailyPulseClient->echoMsg('hello');
    $this->assertFalse($response->isException());
    $this->assertEquals(200, $response->statusCode());
    $obj=$response->json();
    $this->assertEquals('hello', $obj['msg']);
```
## Additional information

The tests run through all the API calls available, check them.
