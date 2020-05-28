<?php

namespace Superbrave\PartnerizeBundle\Model;

/**
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
     * @var HyperMedia
     */
    private $hyperMedia;

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
     * @return HyperMedia
     */
    public function getHyperMedia(): HyperMedia
    {
        return $this->hyperMedia;
    }

    /**
     * @param HyperMedia $hyperMedia
     */
    public function setHyperMedia(HyperMedia $hyperMedia): void
    {
        $this->hyperMedia = $hyperMedia;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->hyperMedia->getResponse();
    }
}
