<?php

namespace Appstractsoftware\MagentoAdapter\Api\Newsletter;

/**
 * NewsletterSubscribeInterface
 */
interface NewsletterSubscribeInterface
{
    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus();
}
