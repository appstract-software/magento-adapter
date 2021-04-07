<?php

namespace Appstractsoftware\MagentoAdapter\Api;

/**
 * Newsletter interface.
 * @api
 */
interface MailingServiceInterface
{
    /**
     * Subscribe an email.
     *
     * @param string $mail
     * @param string $name
     * @param string $templateId
     * @param string $message
     * @return \Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface
     */
    public function sendMail($mail, $name, $templateId, $message);
}





