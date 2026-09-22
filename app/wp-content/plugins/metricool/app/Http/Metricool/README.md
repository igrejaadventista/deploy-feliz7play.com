# Metricool Client internal package
To use the Client singleton in the App container, you can call the method below. This will return an instance of a Metricool client that you can use to interact with the API:
```php
/**
 * Get the MetricoolApi facade to easily access
 * the Metricool API
 */
App::getInstance()->client;
```

## Entities Facade
To make it easier to interact with the Metricool API, the client provides a facade that allows you to access various entities. These entities represent different aspects of your Metricool account, such as statistics, brands, subscription and more. This means that `App::getInstance()->client` will return an instance of the `MetricoolApi` class.

### Entities
All entities work quite similar. They register their endpoint, require the `MetricoolClient` and can do some basic operations like `get()`, `post()`, `put()`, and `delete()`. If an entity is filterable the Trait `isFilterable` can be used to add the ability to filter the results based on certain criteria. The `getAcceptedFilters()` method should be implemented in each entity to define which filters are accepted.

#### Example of the brands entity
```php
class ConnectedBrands
{
    protected MetricoolClient $client;
    private string $endpoint = 'admin/profiles-auth';

    public function __construct(MetricoolClient $client)
    {
        $this->client = $client;
    }

    public function all(): array
    {
        return $this->client->get($this->endpoint);
    }
}
```
#### Usage
```php
$response = App::getInstance()->client->connectedBrands()->get();
```

#### Response
_Truncated response for readability_
```json
[
    {
        "id": 2221200,
        "userId": 1826104,
        "ownerUserId": 1826104,
        "label": "TestingMetri-Business",
        "url": "https://help.metricool.com/es/",
        "title": "Metricool",
        "description": "Blog & Social Media Finally Together",
        "picture": "https://static.metricool.com/brand-logo/202506/2221200-file-7594769922755525893.jpeg",
        ...
    }
]
```

## Statistics
Statistics are bundled in their own facade, which can be accessed through the `statistics()` method of the client. This allows you to retrieve various statistics related to your Metricool account. They are grouped in two main categories: "distribution" and "timeline". These groups reflect the endpoint that is used to retrieve the data from the Metricool API. The other differences are the amount of accepted filters and the compatible metrics that can be retrieved.

```php
class TimelineStatistics
{
    protected string $endpoint = 'stats/timeline/';

    private array $compatibleMetrics = [
        'PageViews',
        'SessionsCount',
        'Visitors',
        'DailyPosts',
        'DailyComments',
    ];

    protected function getAcceptedFilters(): array
    {
        return [
            'start' => '/^\d+$/',
            'end' => '/^\d+$/',
        ];
    }
}

class DistributionStatistics
{
    protected string $endpoint = 'stats/distribution/';
    
    private array $compatibleMetrics = [
        'country',
        'referers',
        'sources',
    ];
    
    protected function getAcceptedFilters(): array
    {
        return [
            'start' => '/^\d+$/', // Just digits
            'end' => '/^\d+$/', // Just digits
            'country' => '/^[a-z]{2}$/', // ISO 3166-1 alpha-2 lowercase country code
        ];
    }
}
```

### Get statistics for the last 7 days
```php
// Mocking a Request object
$request = new Storage([
    'start' => Carbon::now()->subDays(4)->format('Ymd'),
    'end' => Carbon::now()->format('Ymd'),
]);

$client = App::getInstance()->client;
$response = $client->statistics()->countries()->filter([
    'start' => $request->getString('start'),
    'end' => $request->getString('end'),
])->get();
```

Response contains reference points (that can be used in the graph) for each day in the specified range, with the date as a timestamp and the value as a string:
```json
[
    [
        "1752170400000",
        "981.0"
    ],
    [
        "1752256800000",
        "895.0"
    ],
    [
        "1752343200000",
        "487.0"
    ],
    [
        "1752429600000",
        "501.0"
    ]
]
```

## Traits
Some of the entities use traits for common functionality.

### isFilterable Trait
This trait can be used in entities that support filtering. It provides methods to apply filters to the entity's requests. The `getAcceptedFilters()` method should be implemented in the entity to define which filters are accepted.

#### Usage
```php
class ExampleEntity
{
    use isFilterable;

    protected string $endpoint = 'example/endpoint';

    protected function getAcceptedFilters(): array
    {
        return [
            'start' => '/^\d+$/', // Just digits
            'end' => '/^\d+$/', // Just digits
        ];
    }
}

// Filtering an entity
$response = App::getInstance()->client->example()->filter([
    'start' => '20230101',
    'end' => '20230131',
])->get();

// Endpoint that will be requested:
// example/endpoint?start=20230101&end=20230131
```

### IsUpdatable Trait
This trait can be used in entities that support updating. It provides methods to update the entity's data. The `getFillable()` method should be implemented in the entity to define the properties that can be updated.

#### Usage
```php
class ExampleEntity
{
    use IsUpdatable;

    protected string $endpoint = 'example/endpoint';

    protected function getFillable(): array
    {
        return ['property1', 'property2'];
    }
}

// Updating an entity
$response = App::getInstance()->client->example()->update([
    'property1' => 'new value',
    'property2' => 'another value',
    'unlistedProperty' => 'should not be included', // This will be ignored
]);

// Endpoint that will be requested:
// example/endpoint?fields=new+value&fields=another+value

// With the payload:
// {
//   "property1": "new value",
//   "property2": "another value"
// }
```