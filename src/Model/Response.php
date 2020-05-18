<?php

namespace Superbrave\PartnerizeBundle\Model;

/**
 * Class Response
 */
class Response
{
    /**
     * @var string
     */
    private $executionTime;

    /**
     * @var Job
     */
    private $job;

    /**
     * @return string
     */
    public function getExecutionTime(): string
    {
        return $this->executionTime;
    }

    /**
     * @param string $executionTime
     */
    public function setExecutionTime(string $executionTime): void
    {
        $this->executionTime = $executionTime;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @param Job $job
     */
    public function setJob(Job $job): void
    {
        $this->job = $job;
    }
}
