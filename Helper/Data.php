<?php

namespace Appstractsoftware\MagentoAdapter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
  const XML_PATH_SECTION = 'appstract';
  const XML_PATH_GROUP = 'appstract_configuration';
  const AUTO_GENERATE_INVOICE = 'auto_generate_invoice';
  const SOURCES_TO_SKIP = 'sources_to_skip';
  const CLIENT_ADMIN_GROUP_ID = 'client_admin_group_id';
  const ELASTICSEARCH_FUZZINESS = 'elasticsearch_fuzziness';
  const ELASTICSEARCH_SKIP_URL_KEY = 'elasticsearch_skip_url_key';
  const ELASTICSEARCH_SKU_BOOST = 'elasticsearch_sku_boost';

  public function getConfigValue($field, $storeId = null)
  {
    return $this->scopeConfig->getValue(
      $field,
      ScopeInterface::SCOPE_STORE,
      $storeId
    );
  }

  public function getConfiguration($field, $storeId = null)
  {
    return $this->getConfigValue(self::XML_PATH_SECTION . '/' . self::XML_PATH_GROUP . '/' . $field, $storeId);
  }

  public function getSourcesToSkip($storeId = null)
  {
    $sources = $this->getConfiguration(self::SOURCES_TO_SKIP, $storeId);
    
    if ($sources == '') {
      return [];
    }

    return explode(',', $sources);
  }

  public function getClientAdminGroupId($storeId = null)
  {
    return $this->getConfiguration(self::CLIENT_ADMIN_GROUP_ID, $storeId);
  }

  public function getElasticsearchFuzziness($storeId = null)
  {
    $fuzzines =  $this->getConfiguration(self::ELASTICSEARCH_FUZZINESS, $storeId);

    if ($fuzzines == '') {
      return 'AUTO';
    }

    return $fuzzines;
  }

  public function getElasticsearchSkipUrlKey($storeId = null)
  {
    return $this->getConfiguration(self::ELASTICSEARCH_SKIP_URL_KEY, $storeId) == '1' ? true : false;
  }

  public function getElasticsearchSkuBoost($storeId = null)
  {
    $boost = $this->getConfiguration(self::ELASTICSEARCH_SKU_BOOST, $storeId);

    if ($boost == '') {
      return false;
    }

    return (int)$boost;
  }
}
