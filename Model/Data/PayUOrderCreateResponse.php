<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\PayUOrderCreateResponseInterface;

class PayUOrderCreateResponse implements PayUOrderCreateResponseInterface
{
    /** @var string $status */
    private $status;

    /** @var string $orderId */
    private $orderId;

    /** @var string $extOrderId */
    private $extOrderId;

    /** @var string $redirectUri */
    private $redirectUri;

    /**
     * @inheritDoc
     */
    public function load($responseArray)
    {
        $this->status = $responseArray['status']->statusCode;
        $this->orderId = $responseArray['orderId'];
        // $this->extOrderId = $responseArray['extOrderId'];
        $this->redirectUri = $responseArray['redirectUri'];

        return clone $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getExtOrderId()
    {
        return $this->extOrderId;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}
