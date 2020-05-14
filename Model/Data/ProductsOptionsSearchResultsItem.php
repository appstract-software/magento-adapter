<?php
namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\ProductsOptionsSearchResultsItemInterface;

class ProductsOptionsSearchResultsItem implements ProductsOptionsSearchResultsItemInterface
{
    /**
     * @inheritDoc
     */
    public function load($sku, $id, $options)
    {
        $this->setSku($sku);
        $this->setId($id);
        $this->setOptions($options);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

}
