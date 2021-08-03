<?php
namespace Appstractsoftware\MagentoAdapter\Model\Config\Source;

class ConfiguratorGenderOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Get all options of configurator gender step.
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('-'), 'value' => ''],
            ['label' => __('Kobieta'), 'value' => 'gender_woman'],
            ['label' => __('Mężczyzna'), 'value' => 'gender_man']
        ];

        return $this->_options;
    }
}
