Polr API Client
===============

This package is a PHP client API library for open source project [Polr Project](https://docs.polrproject.org/en/latest/developer-guide/api/#api-endpoints). It supports all APIs from Polr v2.2.0. Pull requests and improvement suggestions are welcome.  
Note: If you are working with Symfony, use [this](https://github.com/adeelnawaz/polr-api-bundle) instead.

Installation via composer
------------

In your console, navigate to your project directory and execute the
following command to download the latest stable version of this package:

```console
$ composer require adeelnawaz/polr-api-client
```

Usage
-----
_(see `examples/` directory for PHP example code for each API method)_

In order to use the API, instantiate an object of `PolrAPI`. Upon instantiation
of the object, the following have to be provided:
- the URL to the API
- the secret key of the user
- the quota of the user
- JMS serializer object

After instantiating the API, create DTO object(s) (`DTO\Link`, etc) for the method you
intend to use and call the method. This will result in calling the respective REST API
endpoint and returning the relative `DTO\Response` object.  
_(See Docblocks of the `PolrApi` methods for further information on input/output DTOs)_

In case of a failed API call, the `PolrApi` methods throw `ApiResponseException`. The
exception has getters for `code`, `message`, and a machine readable short string
`error_code` returned by the Polr REST API.

Example:

```
// Register composer spl autoload
$autoloader = require __DIR__ . '/../vendor/autoload.php';

// Register doctrine annotation autoload for JMS to work properly
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($autoloader, "loadClass"));

// Build JMS Serializer
$serializer = \JMS\Serializer\SerializerBuilder::create()
    // Note: in order to use lookupLink API, this subscriber must be added
    ->configureListeners(function (JMS\Serializer\EventDispatcher\EventDispatcher $dispatcher) {
        $dispatcher->addSubscriber(new \Adeelnawaz\PolrApiClient\EventSubscriber\LinkDeserealizeSubscriber());
    })
    ->build();

// Instantiate PolrAPI object
$api = new \Adeelnawaz\PolrApiClient\PolrAPI(
    "POLR_API_URL",
    "POLR_API_KEY",
    "POLR_API_QUOTA",
    $serializer
);

// Prepare DTO for API method input
$link = new \Adeelnawaz\PolrApiClient\DTO\Link();
$link->setUrl('https://www.google.com/search?tbm=isch&source=hp&biw=1863&bih=916&ei=IksNW5eLHqzisAfvgKKQBg&q=samurai+jack&oq=samurai+jack&gs_l=img.3..0l10.799.2671.0.2891.13.10.0.3.3.0.54.372.9.9.0....0...1ac.1.64.img..1.12.380.0...0.NlHgI6Y6mmY')
    ->setIsSecret(true);

try {
    /**
     * @var \Adeelnawaz\PolrApiClient\DTO\Response\Link $responseLink
     */
    $responseLink = $api->shortenLink($link);

    print_r($responseLink);
} catch (\Adeelnawaz\PolrApiClient\Exception\ApiResponseException $e) {
    echo "Error: ({$e->getCode()} - {$e->getErrorCode()}) \"{$e->getMessage()}\"\n";
}
```
**Note**  
The Quota is requests per second. Specify the right quota for the key/user otherwise you might run into API overuse, resulting in exceptions. Set `0` for unlimited

Response
--------

The response object for each method is either a DTO or array of DTOs from `\Adeelnawaz\PolrApiClient\DTO\Response` namespace. Please see API method docblocks for
applicable response DTO for each method.

**Error Response**

In case of an error the API methods throw `Adeelnawaz\PolrApiClient\Exception\ApiResponseException`. The valuable
information contained within the exception includes
- `getCode()` - returns the status code returned by Polr API. See [Polr documentation](https://docs.polrproject.org/en/latest/developer-guide/api/#error-responses) for possible codes
- `getMessage()` - returns the error message returned by Polr API
- `getErrorCode()` - returns the machine readable short error string such as _QUOTA_EXCEEDED_
