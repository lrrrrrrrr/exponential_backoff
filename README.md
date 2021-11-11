# Exponential backoff

## Installation

```
composer require nsleta/exponential_backoff
```

## Usage

```php
    $backoff = new Backoff();
    $backoff->setInterval(3000); // optional, default interval is 2000
    $backoff->setMaxAttempts(2); // optional, default max attempts is 3
    $result = $backoff->call(function() { return 'done'; }, [RuntimeException::class]);
```
