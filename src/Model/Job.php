<?php

namespace Superbrave\PartnerizeBundle\Model;

use DateTime;/**
 * A job response can be deserialized into this class, though it does not contain all the properties returned by the
 * Partnerize API at this point. More properties can be added if needed in the future.
 * Class Job
 */
class Job
{
    /**
     * @var string
     */
    private $jobId;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $response;

    /**
     * @var DateTime
     */
    private $completedAt;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     */
    public function setJobId(string $jobId): void
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
    }

    /**
     * @return DateTime
     */
    public function getCompletedAt(): DateTime
    {
        return $this->completedAt;
    }

    /**
     * @param DateTime $completedAt
     */
    public function setCompletedAt(DateTime $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
