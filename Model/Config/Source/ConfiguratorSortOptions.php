<?php
namespace Appstractsoftware\MagentoAdapter\Model\Config\Source;

class ConfiguratorSortOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Get all options of configurator sort step.
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('-'), 'value' => ''],
            ['label' => __('Według popularności'), 'value' => 'popularity'],
            ['label' => __('Od najnowszych'), 'value' => 'latest'],
            ['label' => __('Od najstarszych'), 'value' => 'oldest'],
            ['label' => __('Od najniższej ceny'), 'value' => 'lowest_price'],
            ['label' => __('Od najwyższej ceny'), 'value' => 'highest_price']
        ];

        return $this->_options;
    }
}
