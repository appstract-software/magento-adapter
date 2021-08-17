<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\Przelewy24RegisterTransactionResponseInterface;

class Przelewy24RegisterTransactionResponse implements Przelewy24RegisterTransactionResponseInterface
{
    /** @var int $error */
    private $error;

    /** @var string $token */
    private $token;

    /** @var string $sessionId */
    private $sessionId;

    /**
     * @inheritDoc
     */
    public function load($responseArray)
    {
        $this->error = $responseArray['error'];
        $this->token = $responseArray['token'];
        $this->sessionId = $responseArray['sessionId'];

        return clone $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }
}
