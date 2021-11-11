# Exponential backoff

## Installation

```
composer require nsleta/exponential_backoff
```

## Usage

```php
    $retry = new ExponentialBackoff\Retry();
    $retry->setInterval(3000); // optional, default interval is 2000
    $retry->setMaxAttempts(2); // optional, default max attempts is 3
    $result = $retry->call(function() { return 'done'; }, [RuntimeException::class]);
```
