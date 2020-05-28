<?php

namespace Superbrave\PartnerizeBundle\Model;

/**
 * Class HyperMedia
 */
class HyperMedia
{
    /**
     * @var string|null
     */
    private $response;

    /**
     * @var string|null
     */
    private $update;

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param string|null $response
     */
    public function setResponse(?string $response): void
    {
        $this->response = $response;
    }

    /**
     * @return string|null
     */
    public function getUpdate(): ?string
    {
        return $this->update;
    }

    /**
     * @param string|null $update
     */
    public function setUpdate(?string $update): void
    {
        $this->update = $update;
    }
}
