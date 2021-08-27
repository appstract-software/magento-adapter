<?php

namespace Appstractsoftware\MagentoAdapter\Api\Newsletter;

/**
 * Newsletter interface.
 * @api
 */
interface NewsletterManagementInterface
{
    /**
     * Subscribe an email.
     *
     * @param string $email
     * @return \Appstractsoftware\MagentoAdapter\Api\Newsletter\NewsletterSubscribeInterface
     */
    public function subscribe($email);

    /**
     * Unsubscribe an email.
     *
     * @param string $email
     * @return \Appstractsoftware\MagentoAdapter\Api\Newsletter\NewsletterSubscribeInterface
     */
    public function unsubscribe($email);
}
