<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;


interface Przelewy24RegisterTransactionResponseInterface
{
    /**
     * Load data for dto.
     *
     * @param array
     * @return Appstractsoftware\MagentoAdapter\Api\Data\Przelewy24RegisterTransactionResponseInterface
     */
    public function load($data);

    /**
     *
     * @return int
     */
    public function getError();

    /**
     *
     * @return string
     */
    public function getToken();
    /**
     *
     * @return string
     */
    public function getSessionId();
}
