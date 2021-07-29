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
     * @param string $email
     * @param string $templateId
     * @param mixed $variables
     * @return \Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface
     */
    public function sendEmail($email, $templateId, $variables);

    /**
     * Subscribe an email.
     *
     * @param string $email
     * @param string $templateId
     * @param mixed $variables
     * @param string $topic
     * @param string $name
     * @param string $company
     * @param string $message
     * @param string $orderId
     * @return \Appstractsoftware\MagentoAdapter\Api\MailingServiceInterface
     */
    public function sendContactEmail($email, $templateId, $variables, $topic, $name, $company, $message, $orderId = '');
}
