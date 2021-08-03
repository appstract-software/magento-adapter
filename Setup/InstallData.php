<?php
namespace Appstractsoftware\MagentoAdapter\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    const AGREED_TO_PROMOTIONS = ['attribute_code' => 'agreed_to_promotions', 'label' => 'Zgoda na marketing'];

    /**
     * Configurator custom attributes
     */
    const CONFIGURATOR_GENDER = ['attribute_code' => 'selected_gender', 'label' => 'Wybrana płeć'];
    const CONFIGURATOR_SORT_FIELD = ['attribute_code' => 'sort_field', 'label' => 'Preferowane sortowanie'];
    const CONFIGURATOR_PREFERRED_COLORS = ['attribute_code' => 'preferred_colors', 'label' => 'Preferowane kolory'];
    const CONFIGURATOR_PREFERRED_STYLES = ['attribute_code' => 'preferred_styles', 'label' => 'Preferowane style'];
    const CONFIGURATOR_SIZES = [
        ['attribute_code' => 'size_collar', 'label' => 'Rozmiar kołnierzyka'],
        ['attribute_code' => 'size_chest',  'label' => 'Obwód w klatce'],
        ['attribute_code' => 'size_waist',  'label' => 'Obwód pasa'],
        ['attribute_code' => 'size_height', 'label' => 'Wzrost'],
        ['attribute_code' => 'size_insole', 'label' => 'Rozmiar wkładki']
    ];

    /**
     * Configurator custom attributes starting postion and sort_order
     */
    private $attributePosition = 300;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        /**
         *  Attribute: agreed_to_promotions
         */
        $customerSetup->addAttribute(Customer::ENTITY, self::AGREED_TO_PROMOTIONS['attribute_code'],
            [
                'type'         => 'int',
				'label'        => self::AGREED_TO_PROMOTIONS['label'],
				'input'        => 'boolean',
				'required'     => false,
                'default'      => 0,
                'visible'      => true,
                'user_defined' => true,
                'sort_order'   => $this->attributePosition,
                'position'     => $this->attributePosition++,
                'system'       => false,
            ]
        );

        $agreedToPromotions = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::AGREED_TO_PROMOTIONS['attribute_code'])
            ->addData([
                'attribute_set_id'   => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms'      => ['adminhtml_customer'],
            ]);
    
        $agreedToPromotions->save();

        /**
         *  Attribute: selected_gender
         */
        $customerSetup->addAttribute(Customer::ENTITY, self::CONFIGURATOR_GENDER['attribute_code'],
            [
                'backend'      => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'type'         => 'varchar',
				'label'        => self::CONFIGURATOR_GENDER['label'],
				'input'        => 'select',
                'source'       => 'Appstractsoftware\MagentoAdapter\Model\Config\Source\ConfiguratorGenderOptions',
				'required'     => false,
                'default'      => '',
                'visible'      => true,
                'user_defined' => true,
                'sort_order'   => $this->attributePosition,
                'position'     => $this->attributePosition++,
                'system'       => false,
            ]
        );

        $configuratorGender = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::CONFIGURATOR_GENDER['attribute_code'])
            ->addData([
                'attribute_set_id'   => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms'      => ['adminhtml_customer'],
            ]);
    
        $configuratorGender->save();

        /**
         *  Attribute: 'size_collar', 'size_chest', 'size_waist', 'size_height', 'size_insole'
         */
        foreach (self::CONFIGURATOR_SIZES as &$size) {
            $customerSetup->addAttribute(Customer::ENTITY, $size['attribute_code'],
                [
                    'type'         => 'int',
                    'label'        => $size['label'],
                    'input'        => 'text',
                    'required'     => false,
                    'default'      => '',
                    'visible'      => true,
                    'user_defined' => true,
                    'sort_order'   => $this->attributePosition,
                    'position'     => $this->attributePosition++,
                    'system'       => false,
                ]
            );

            $configuratorStep = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, $size['attribute_code'])
                ->addData([
                    'attribute_set_id'   => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms'      => ['adminhtml_customer'],
                ]);
    
            $configuratorStep->save();
        }

        /**
         *  Attribute: sort_fields
         */
        $customerSetup->addAttribute(Customer::ENTITY, self::CONFIGURATOR_SORT_FIELD['attribute_code'],
            [
                'backend'      => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'type'         => 'varchar',
				'label'        => self::CONFIGURATOR_SORT_FIELD['label'],
				'input'        => 'select',
                'source'       => 'Appstractsoftware\MagentoAdapter\Model\Config\Source\ConfiguratorSortOptions',
				'required'     => false,
                'default'      => '',
                'visible'      => true,
                'user_defined' => true,
                'sort_order'   => $this->attributePosition,
                'position'     => $this->attributePosition++,
                'system'       => false,
            ]
        );

        $configuratorSortField = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::CONFIGURATOR_SORT_FIELD['attribute_code'])
            ->addData([
                'attribute_set_id'   => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms'      => ['adminhtml_customer'],
            ]);
    
        $configuratorSortField->save();

        /**
         *  Attribute: preferred_colors
         */
        $customerSetup->addAttribute(Customer::ENTITY, self::CONFIGURATOR_PREFERRED_COLORS['attribute_code'],
            [
                'backend'      => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'type'         => 'varchar',
				'label'        => self::CONFIGURATOR_PREFERRED_COLORS['label'],
				'input'        => 'multiselect',
                'source'       => 'Appstractsoftware\MagentoAdapter\Model\Config\Source\ConfiguratorPreferredColorsOptions',
				'required'     => false,
                'default'      => '',
                'visible'      => true,
                'user_defined' => true,
                'sort_order'   => $this->attributePosition,
                'position'     => $this->attributePosition++,
                'system'       => false,
            ]
        );

        $configuratorPreferredColors= $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::CONFIGURATOR_PREFERRED_COLORS['attribute_code'])
            ->addData([
                'attribute_set_id'   => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms'      => ['adminhtml_customer'],
            ]);
    
        $configuratorPreferredColors->save();

        /**
         *  Attribute: preferred_styles
         */
        $customerSetup->addAttribute(Customer::ENTITY, self::CONFIGURATOR_PREFERRED_STYLES['attribute_code'],
            [
                'backend'      => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'type'         => 'varchar',
				'label'        => self::CONFIGURATOR_PREFERRED_STYLES['label'],
				'input'        => 'multiselect',
                'source'       => 'Appstractsoftware\MagentoAdapter\Model\Config\Source\ConfiguratorPreferredStylesOptions',
				'required'     => false,
                'default'      => '',
                'visible'      => true,
                'user_defined' => true,
                'sort_order'   => $this->attributePosition,
                'position'     => $this->attributePosition++,
                'system'       => false,
            ]
        );
        
        $configuratorPreferredStyles= $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::CONFIGURATOR_PREFERRED_STYLES['attribute_code'])
            ->addData([
                'attribute_set_id'   => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms'      => ['adminhtml_customer'],
            ]);
    
        $configuratorPreferredStyles->save();

        $setup->endSetup();
    }
}
