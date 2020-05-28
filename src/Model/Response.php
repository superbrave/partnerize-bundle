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
     * @var Job|null
     */
    private $job;

    /**
     * @var array|null
     */
    private $conversionItems;

    /**
     * @var array|null
     */
    private $errors;

    /**
     * @var int|null
     */
    private $errorsCount;

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
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job|null $job
     */
    public function setJob(?Job $job): void
    {
        $this->job = $job;
    }

    /**
     * @return array|null
     */
    public function getConversionItems(): ?array
    {
        return $this->conversionItems;
    }

    /**
     * @param array|null $conversionItems
     */
    public function setConversionItems(?array $conversionItems): void
    {
        $this->conversionItems = $conversionItems;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array|null $errors
     */
    public function setErrors(?array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return int|null
     */
    public function getErrorsCount(): ?int
    {
        return $this->errorsCount;
    }

    /**
     * @param int|null $errorsCount
     */
    public function setErrorsCount(?int $errorsCount): void
    {
        $this->errorsCount = $errorsCount;
    }
}
