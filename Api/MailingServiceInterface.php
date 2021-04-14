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
     * @param string $templateId
     * @param mixed $variables
     * @return \Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface
     */
    public function sendMail($mail, $templateId, $variables);
}





