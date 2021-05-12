<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;


interface PayUOrderCreateResponseInterface
{
    /**
     * Load data for dto.
     *
     * @param array
     * @return Appstractsoftware\MagentoAdapter\Api\Data\PayUOrderCreateResponseInterface
     */
    public function load($product);

    /**
     *
     * @return string
     */
    public function getStatus();

    /**
     *
     * @return string
     */
    public function getOrderId();

    /**
     *
     * @return string
     */
    public function getExtOrderId();
    /**
     *
     * @return string
     */
    public function getRedirectUri();
}
