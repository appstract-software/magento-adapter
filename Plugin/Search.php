<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Appstractsoftware\MagentoAdapter\Plugin;

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Search\Request\Builder;
use Magento\Framework\Search\SearchEngineInterface;
use Magento\Framework\Search\SearchResponseBuilder;

/**
 * Search API for all requests.
 */
class Search
{
  /**
   * @var Builder
   */
  private $requestBuilder;

  /**
   * @var ScopeResolverInterface
   */
  private $scopeResolver;

  /**
   * @var SearchEngineInterface
   */
  private $searchEngine;

  /**
   * @var SearchResponseBuilder
   */
  private $searchResponseBuilder;

  /**
   * @param Builder $requestBuilder
   * @param ScopeResolverInterface $scopeResolver
   * @param SearchEngineInterface $searchEngine
   * @param SearchResponseBuilder $searchResponseBuilder
   */
  public function __construct(
    Builder $requestBuilder,
    ScopeResolverInterface $scopeResolver,
    SearchEngineInterface $searchEngine,
    SearchResponseBuilder $searchResponseBuilder
  ) {
    $this->requestBuilder = $requestBuilder;
    $this->scopeResolver = $scopeResolver;
    $this->searchEngine = $searchEngine;
    $this->searchResponseBuilder = $searchResponseBuilder;
  }

  /**
   * @inheritdoc
   */
  public function aroundSearch($subject, callable $proceed, SearchCriteriaInterface $searchCriteria)
  {
    $this->requestBuilder->setRequestName($searchCriteria->getRequestName());

    $scope = $this->scopeResolver->getScope()->getId();
    $this->requestBuilder->bindDimension('scope', $scope);

    foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
      foreach ($filterGroup->getFilters() as $filter) {
        $this->addFieldToFilter($filter->getField(), $filter->getValue());
      }
    }

    $this->requestBuilder->setFrom($searchCriteria->getCurrentPage() * $searchCriteria->getPageSize());
    $this->requestBuilder->setSize($searchCriteria->getPageSize());

    /**
     * This added in Backward compatibility purposes.
     * Temporary solution for an existing API of a fulltext search request builder.
     * It must be moved to different API.
     * Scope to split Search request builder API in MC-16461.
     */
    if (method_exists($this->requestBuilder, 'setSort')) {
      $sorts = [];

      foreach ($searchCriteria->getSortOrders() as $sort) {
        // we need to skip sort by this custom field because it's not present in elasticsearch, otherwise we get empty results
        if ($sort->getField() !== 'created_at') {
          $sorts[] = $sort;
        }
      }

      $this->requestBuilder->setSort($sorts);
    }
    $request = $this->requestBuilder->create();
    $searchResponse = $this->searchEngine->search($request);

    return $this->searchResponseBuilder->build($searchResponse)
      ->setSearchCriteria($searchCriteria);
  }

  /**
   * Apply attribute filter to facet collection
   *
   * @param string $field
   * @param string|array|null $condition
   * @return $this
   */
  private function addFieldToFilter($field, $condition = null)
  {
    if (!is_array($condition) || !in_array(key($condition), ['from', 'to'], true)) {
      $this->requestBuilder->bind($field, $condition);
    } else {
      if (!empty($condition['from'])) {
        $this->requestBuilder->bind("{$field}.from", $condition['from']);
      }
      if (!empty($condition['to'])) {
        $this->requestBuilder->bind("{$field}.to", $condition['to']);
      }
    }

    return $this;
  }
}
