<?php
namespace Appstractsoftware\MagentoAdapter\Model\Config\Source;

class ConfiguratorPreferredStylesOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Get all options of configurator preferred styles step.
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Elegancki'), 'value' => 'style_elegance'],
            ['label' => __('Smart Casual'), 'value' => 'style_smart_casual'],
            ['label' => __('Casual'), 'value' => 'style_casual']
        ];

        return $this->_options;
    }
}
