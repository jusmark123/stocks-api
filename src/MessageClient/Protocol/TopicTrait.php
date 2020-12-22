<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

trait TopicTrait
{
    /**
     * @var string
     */
    protected $topic;

    /**
     * @var mixed
     */
    protected $message;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     *
     * @return Packet
     */
    public function setTopic(string $topic): Topic
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return Topic
     */
    public function setHeaders(array $headers): Topic
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return \array_key_exists(strtoupper($name), $this->headers);
    }

    /**
     * @param string $name
     *
     * @return [type]
     */
    public function getHeader(string $name): ?string
    {
        $name = strtoupper($name);
        if (!$this->hasHeader($name)) {
            return null;
        }

        return $this->headers[$name];
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return Topic
     */
    public function addheader(string $name, string $value): Topic
    {
        $this->headers[strtoupper($name)] = $value;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Topic
     */
    public function removeHeader(string $name): Topic
    {
        if ($this->hasHeader($name)) {
            unset($this->headers[strtoupper($name)]);
        }

        return $this;
    }
}
