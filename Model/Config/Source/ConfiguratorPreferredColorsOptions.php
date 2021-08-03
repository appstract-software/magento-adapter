<?php
namespace Appstractsoftware\MagentoAdapter\Model\Config\Source;

class ConfiguratorPreferredColorsOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Get all options of configurator preferred colors step.
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Kolorowe'), 'value' => 'color_colors'],
            ['label' => __('Jednorodne'), 'value' => 'color_smooth'],
            ['label' => __('Szarości/Czernie'), 'value' => 'color_white_black']
        ];

        return $this->_options;
    }
}
