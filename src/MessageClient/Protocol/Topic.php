<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

interface Topic extends \JsonSerializable
{
    /**
     * @return string
     */
    public function getTopic(): string;

    /**
     * @param string $topic
     *
     * @return Topic
     */
    public function setTopic(string $topic): Topic;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param array $headers
     *
     * @return Topic
     */
    public function setHeaders(array $headers): Topic;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool;

    /**
     * @param string $name
     *
     * @return [type]
     */
    public function getHeader(string $name): ?string;

    /**
     * @param string $name
     * @param string $value
     *
     * @return Topic
     */
    public function addHeader(string $name, string $value): Topic;

    /**
     * @param string $name
     *
     * @return Topic
     */
    public function removeHeader(string $name): Topic;
}
