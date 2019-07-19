<?php

namespace Xigen\Special\Model\ResourceModel\Layer\Filter;

use Magento\Framework\App\Http\Context;
use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Search\Request\IndexScopeResolverInterface;

/**
 * Price Filter
 */
class Price extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price
{
    /**
     * Price constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Xigen\Special\Model\Layer\Resolver $layerResolver
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param null $connectionName
     * @param IndexScopeResolverInterface|null $priceTableResolver
     * @param Context|null $httpContext
     * @param DimensionFactory|null $dimensionFactory
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Xigen\Special\Model\Layer\Resolver $layerResolver,
        \Magento\Customer\Model\Session $session,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $connectionName = null,
        IndexScopeResolverInterface $priceTableResolver = null,
        Context $httpContext = null,
        DimensionFactory $dimensionFactory = null
    ) {
        parent::__construct(
            $context,
            $eventManager,
            $layerResolver,
            $session,
            $storeManager,
            $connectionName,
            $priceTableResolver,
            $httpContext,
            $dimensionFactory
        );
    }
}
