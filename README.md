# SYSTEM LOGGER

> A package used to perform DataDog and system logs

### Installation and Setup

1. Run `composer require 0nedark/system_logger` to install the package
2. You will also have to set `DATADOG_PROJECT_NAME`, `DATADOG_SERVICE_NAME` and `DATADOG_ENVIRONMENT` ENV variables

### Basic Use

```php
use MVF\SystemLogger\Reporters\Remotes\DataDog\DataDog;
use MVF\SystemLogger\Reporters\Host\BasicStdOut;
use MVF\SystemLogger\SystemLogger;

$logger = new SystemLogger();
$logger->info(
    ["some_kind_of_tag", "key" => "another_tag"],
    "message",
    new BasicStdOut(),
    DataDog::histogram("metric_name_suffix", 2)
);
```

There are two type of reporters available, `host` reporters, `BasicStdOut` in the above example, and `remote` 
reporters, `DataDog.histogram(...)` in the above example. `host` reporters are used to log messages, these reporters do 
not receive the list of tags and therefore are not able to do anything with it. `remote` reporters are used to make more
complex logs, they receive only the list of tags, the message is appended to the tag list as 
`"message" => "the message"`. You can create your own reporters by implementing `HostLogInterface` or 
`RemoteLogInterface` more about this later.

### SystemLogger

include:

- `use MVF\SystemLogger\SystemLogger;`

methods:    

- `->info(array $tags, string $message, (HostLogInterface|RemoteLogInterface) ...$loggers)`

- `->warning(array $tags, string $message, (HostLogInterface|RemoteLogInterface) ...$loggers)`

- `->error(array $tags, string $message, (HostLogInterface|RemoteLogInterface) ...$loggers)`

The `system logger` is used as a central storage unit, it does `not` perform any logging by itself instead it is 
responsible for message processing and reduction of code duplication since the same tags and message are passed to all 
of the provided reporters.

#### Default Tags

The following are the default tags that will be appended to the provided list.

- `info`, `warning` or `error` one of these will be appended based on which system logger function was called.

#### Message Placeholders

There are two kinds of message placeholders `tag` and `reporter`.

```php
$logger->info(
    [
        "name" => "Bob"                     // tag key name
    ], 
    "There are :1 messages from :name",
    new BasicStdOut(),                      // reporter index 0
    DataDog::histogram("messages", 2),      // reporter index 1
    ...                                     // reporter index ...
);
```

`tag` placeholder is identified with a `:` followed by the `key` of one of your tags, in the above example `:name` is a
valid `tag`. `reporter` placeholder is identified by `:` followed by the `index` of one of your
reporters so in the above example `:1` would be replaced with the value of `DataDog.histogram("messages", 2)` which in
this case would be `2`.

#####Notes
- `:0` would not be replaced since `BasicStdOut` implements `HostLogInterface` and host reporters do not have any 
return value.

### Default Reporters

These are responsible for the actual logging of information. At the moment there are two default reporters built in 
`BasicStdOut` and `DataDog`.

#### BasicStdOut

This is a basic reporter that will simply echo messages to the standard out.

#### DataDog

include:

- `use MVF\SystemLogger\Reporters\Remotes\DataDog\DataDog;`

methods:

- `::increment(string $suffix, int $value, float $sample_rate = 1.0): Increment`
- `::decrement(string $suffix, int $value, float $sample_rate = 1.0): Decrement`
- `::gauge(string $suffix, int $value): Gauge`
- `::histogram(string $suffix, int $value, float $sample_rate = 1.0): Histogram`
- `::time(string $suffix, (float|callable) $time): Time`
- `::unique(string $suffix, int $value): Unique`

`suffix` is the last part of your DataDog metric name. All DataDog metric names will consist of 
`DATADOG_PROJECT_NAME.DATADOG_ENVIRONMENT.suffix` where `DATADOG_PROJECT_NAME` and `DATADOG_ENVIRONMENT` are loaded from ENV, if these variables are not
set then the beginning of your metric will default to `notset.notset.suffix`. In Addition all DataDog logs will be sent
with additional tag `DATADOG_SERVICE_NAME` which is also loaded from ENV, if this variable is not set then it will default to
value `notset`.

### Custom Reporters

You can create your own `host` or `remote` reporters by creating a class and implementing `HostLogInterface` or 
`RemoteLogInterface` respectively.

#### HostLogInterface

include:

- `use MVF\SystemLogger\HostLogInterface;`

methods:

- `->info(string $message): \Exception|null` : should make a log with info severity.

- `->warning(string $message): \Exception|null` : should make a log with warning severity.

- `->error(string $message): \Exception|null` : should make a log with error severity.

Each method receives the `message` with replaced `placeholders`. If an exception is thrown in the reporter then it 
should be caught and returned, the system logger will re-throw it once all other logs are performed. An example of
laravel standard out reporter can be found [here](https://bitbucket.org/mvfglobal/mercury/src/2e033fa6d894045b5ecd1d56cb1c46993e8b7cb4/app/Services/LaravelLogger.php?at=master&fileviewer=file-view-default).

#### RemoteLogInterface

include:

- `use MVF\SystemLogger\RemoteLogInterface;`

methods:

- `->send(string[] $tags): \Exception|null` : `tags` array will always have at least these values 
`[ "<info|warning|error>", "message" => "<message>" ]`. If an exception is thrown in the reporter then it should be
caught and returned, the system logger will re-throw it once all other logs are performed.

- `->getValue(): string|int` : if your remote reporter has a value that may be returned then this function should return
it, otherwise return string `NOT_IMPLEMENTED`.