<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Aws\Sns;

class Notification
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var string
     */
    private $topicArn;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string|null
     */
    private $subscribeUrl;

    /**
     * @var string|null
     */
    private $subscriptionArn = null;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @var string
     */
    private $signatureVersion;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $signingCertUrl;

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
     * @return Notification
     */
    public function setHeaders(array $headers): Notification
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     *
     * @return Notification
     */
    public function setMessageId(string $messageId): Notification
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return $this->topicArn;
    }

    /**
     * @param string $topicArn
     *
     * @return Notification
     */
    public function setTopicArn(string $topicArn): Notification
    {
        $this->topicArn = $topicArn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     *
     * @return Notification
     */
    public function setToken(?string $token): Notification
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage(string $message): Notification
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubscribeUrl(): ?string
    {
        return $this->subscribeUrl;
    }

    /**
     * @param string|null $subscribeUrl
     *
     * @return Notification
     */
    public function setSubscribeUrl(?string $subscribeUrl): Notification
    {
        $this->subscribeUrl = $subscribeUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubscriptionArn(): ?string
    {
        return $this->subscriptionArn;
    }

    /**
     * @param string|null $subscriptionArn
     *
     * @return Notification
     */
    public function setSubscriptionArn(?string $subscriptionArn): Notification
    {
        $this->subscriptionArn = $subscriptionArn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     *
     * @return Notification
     */
    public function setTimestamp(\DateTime $timestamp): Notification
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureVersion(): string
    {
        return $this->signatureVersion;
    }

    /**
     * @param string $signatureVersion
     *
     * @return Notification
     */
    public function setSignatureVersion(string $signatureVersion): Notification
    {
        $this->signatureVersion = $signatureVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     *
     * @return Notification
     */
    public function setSignature(string $signature): Notification
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * @return string
     */
    public function getSigningCertUrl(): string
    {
        return $this->signingCertUrl;
    }

    /**
     * @param string $signingCertUrl
     *
     * @return Notification
     */
    public function setSigningCertUrl(string $signingCertUrl): Notification
    {
        $this->signingCertUrl = $signingCertUrl;

        return $this;
    }
}
