<?php

namespace App\Service;

class LRUCache
{
    /**
     * @var int $capacity
     */
    protected int $capacity;

    /**
     * @var array $queue
     */
    protected array $queue;

    /**
     * LRUCache constructor.
     *
     * @param int $capacity
     */
    function __construct(int $capacity = 0)
    {
        $this->capacity = $capacity;
        $this->queue = [];
    }

    /**
     * @param string $key
     * @param $value
     * @param int $expire
     */
    public function put(string $key, $value, int $expire): void
    {
        if ($this->capacity <= count($this->queue)) {
            $this->queue = array_slice($this->queue, 0, count($this->queue) - 1);
        }

        $this->queue = array_merge([
            $key => [
                'value'    => $value,
                'expireAt' => time() + $expire,
            ],
        ], $this->queue);
    }

    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->queue[$key]['value'];
        }


        throw new \Exception("Not found: $key");
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        $exist = false;

        if (isset($this->queue[$key]) && time() < $this->queue[$key]['expireAt']) {
            $exist = true;

            $tmpItem = $this->queue[$key];

            unset($this->queue[$key]);

            $this->queue = array_merge([$key => $tmpItem], $this->queue);
        }

        if (!$exist) {
            unset($this->queue[$key]);
        }

        return $exist;
    }

    /**
     * @param string $key
     */
    public function remove(string $key)
    {
        if ($this->has($key)) {
            unset($this->queue[$key]);
        }
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return count($this->queue);
    }
}