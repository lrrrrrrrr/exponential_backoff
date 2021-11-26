<?php


namespace ExponentialBackoff;

use Closure;
use PHPUnit\Framework\TestCase;
use \RuntimeException;

class RetryTest extends TestCase
{
    /** @var int */
    protected $calls;

    protected function setUp(): void
    {
        $this->calls = 1;
    }

    public function testCallWithOneAttempt(): void
    {
        $backoff = new Retry();
        $backoff->setInterval(2);
        $backoff->setMaxAttempts(1);
        $this->expectException(\Exception::class);
        $backoff->call($this->retryableFunction(), [\Throwable::class]);
    }

    public function testCallWithNotSuitableExceptionType(): void
    {
        $backoff = new Retry();
        $backoff->setInterval(2);
        $this->expectException(\Exception::class);
        $backoff->call($this->retryableFunction(), [RuntimeException::class]);
    }

    public function testRetry(): void
    {
        $backoff = new Retry();
        $backoff->setInterval(2);

        $this->assertEquals('done', $backoff->call($this->retryableFunction(), [\Throwable::class]));
    }

//    public function testInterval(): void
//    {
//        $backoff = new Retry();
//        $backoff->setMaxAttempts(3);
//        $backoff->setInterval(2000);
//
//        $start = time();
//        $backoff->call($this->retryableFunction(), [\Throwable::class]);
//        $end = time();
//        $this->assertEquals(12, $end-$start);
//        $this->assertEquals('done', $backoff->call($this->retryableFunction(), [\Throwable::class]));
//    }

    protected function retryableFunction(): Closure
    {
        return function () {
            if ($this->calls < 3) {
                $this->calls++;
                throw new \Exception('Try again');
            }

            return 'done';
        };
    }

}
