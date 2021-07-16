<?php
namespace Appstractsoftware\MagentoAdapter\Plugin;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Sales\Api\Data\TotalExtensionFactory;

class Totals
{
    /**
     * @var ScopeConfigInterface 
     */
    private $scopeConfig;

    /**
     * @var TotalExtensionFactory
     */
    private $totalExtensionFactory;

    /**
     * Initialize dependencies.
     * 
     * @param ScopeConfigInterface $scopeConfig
     * @param TotalExtensionFactory $totalExtensionFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        TotalExtensionFactory $totalExtensionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->totalExtensionFactory = $totalExtensionFactory;
    }

    public function afterGetExtensionAttributes(\Magento\Quote\Model\Cart\Totals\Interceptor $totals, $result) {
        $freeshipping =  $this->scopeConfig->getValue('carriers/freeshipping/free_shipping_subtotal', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        if (!$result) {
            $extensionAttributes = $this->totalExtensionFactory->create();
            $extensionAttributes->setFreeShipping($freeshipping);
            $totals->setExtensionAttributes($extensionAttributes);
        } else {
            $result->setFreeShipping($freeshipping);
        }

        return $result;
    }
}
