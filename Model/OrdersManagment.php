<?php

namespace Appstractsoftware\MagentoAdapter\Model;

use \Appstractsoftware\MagentoAdapter\Api\OrdersManagmentInterface;

class OrdersManagment implements OrdersManagmentInterface
{

  /**
   * @var \Magento\Sales\Api\OrderRepositoryInterface
   */
  public $_orderRepository;

  /**
   * @var \Magento\Framework\Api\SearchCriteriaBuilder
   */
  public $_searchCriteriaBuilder;

  /**
   * @var \Magento\Framework\Api\FilterBuilder
   */
  public $_filterBuilder;

  /**
   * @var \Magento\Framework\Webapi\Rest\Request
   */
  public $_request;

  public function __construct(
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    \Magento\Framework\Api\FilterBuilder $filterBuilder,
    \Magento\Framework\Webapi\Rest\Request $request
  ) {
    $this->_orderRepository = $orderRepository;
    $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->_filterBuilder = $filterBuilder;
    $this->_request = $request;
  }

  /**
   * @param int $customerId
   * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
   */
  public function getListForCustomer($customerId)
  {
    $filters = [
      $this->_filterBuilder->setField('customer_id')->setValue($customerId)->create()
    ];

    $searchCriteria = $this->_searchCriteriaBuilder->addFilters($filters)->create();
    $searchCriteria->setCurrentPage($this->_request->getParam('page', 1));

    $searchCriteria->setPageSize($this->_request->getParam('show', 20));

    return $this->_orderRepository->getList($searchCriteria);
  }
}
