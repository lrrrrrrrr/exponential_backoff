<?php

namespace ExponentialBackoff;

class Retry
{
    protected const INTERVAL = 2000;
    protected const MAX_ATTEMPTS = 3;

    /** @var int */
    protected $interval;

    /** @var int */
    protected $maxAttempts;

    public function __construct()
    {
        $this->maxAttempts = static::MAX_ATTEMPTS;
        $this->interval = static::INTERVAL;
    }

    /**
     * @param \Closure $retryableFunction
     * @param array<int, string> $exceptionTypes
     *
     * @return mixed
     * @throws \Throwable
     */
    public function call(\Closure $retryableFunction, array $exceptionTypes)
    {
        if ($this->maxAttempts <= 0) {
            throw new \RuntimeException('Max attempts should be more than 0');
        }

        $attempt = 0;

        while ($this->maxAttempts > $attempt++) {
            try {
                return call_user_func($retryableFunction);
            } catch (\Throwable $exception) {
                if (
                    $this->maxAttempts <= $attempt
                || !$this->checkIfObjectTypeInArray($exception, $exceptionTypes)
                ) {
                    throw $exception;
                }

                usleep(pow(2, $attempt) * $this->interval * 1000);
            }
        }

        return null;
    }

    public function setMaxAttempts(int $maxAttempts): void
    {
        $this->maxAttempts = $maxAttempts;
    }

    public function setInterval(int $interval): void
    {
        $this->interval = $interval;
    }

    /**
     * @param object $object
     * @param array<int, string> $types
     * @return bool
     */
    protected function checkIfObjectTypeInArray(object $object, array $types): bool
    {
        return (bool)array_filter($types, function ($type) use ($object) {
            return $object instanceof $type;
        });
    }
}
