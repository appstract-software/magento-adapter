<?php

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Appstractsoftware\MagentoAdapter\Model\DataProvider as OrderItemProvider;

/**
 * @inheritdoc
 */
class OrderItems
{

    /**
     * @var ValueFactory
     */
    private $valueFactory;

    /**
     * @var OrderItemProvider
     */
    private $orderItemProvider;

    /**
     * @param ValueFactory $valueFactory
     * @param OrderItemProvider $orderItemProvider
     */
    public function __construct(
        ValueFactory $valueFactory,
        OrderItemProvider $orderItemProvider
    ) {
        $this->valueFactory = $valueFactory;
        $this->orderItemProvider = $orderItemProvider;
    }

    /**
     * @inheritdoc
     */
    public function aroundResolve($subject, callable $proceed, Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        /** @var ContextInterface $context */
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }
        if (!(($value['model'] ?? null) instanceof OrderInterface)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        /** @var OrderInterface $parentOrder */
        $parentOrder = $value['model'];
        $orderItemIds = [];
        foreach ($parentOrder->getItems() as $item) {
            if (!$item->getParentItemId()) {
                $orderItemIds[] = (int)$item->getItemId();
            }
            $this->orderItemProvider->addOrderItemId((int)$item->getItemId());
        }
        $itemsList = [];

        $parentFieldExists = false;

        foreach ($info->fieldNodes as $node) {
            foreach ($node->selectionSet->selections as $selection) {
                if ($selection->name->value == 'parent') {
                    $parentFieldExists = true;
                    break;
                }
            }
        }

        foreach ($orderItemIds as $orderItemId) {
            $itemsList[] = $this->valueFactory->create(
                function () use ($orderItemId, $parentFieldExists) {
                    return $this->orderItemProvider->getOrderItemById((int)$orderItemId, $parentFieldExists);
                }
            );
        }
        return $itemsList;
    }
}
