<?php
namespace Appstractsoftware\MagentoAdapter\Api\Data;

use \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface;

interface ProductsOptionsSearchResultsItemInterface
{
    /**
     * Load
     *
     * @return $this
     */
    public function load($sku, $id, $options);

    /**
     * Get sku
     *
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     *
     * @return void
     */
    public function setSku($sku);

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     *
     * @return void
     */
    public function setId($id);

    /**
     * Get options
     *
     * @return \Appstractsoftware\MagentoAdapter\Api\Data\ProductOptionInterface[]
     */
    public function getOptions();

    /**
     * Set options
     *
     * @return void
     */
    public function setOptions($options);

}
