<?php

namespace Xigen\Special\Model\ResourceModel\Layer\Filter;

use Magento\Framework\App\Http\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Search\Request\IndexScopeResolverInterface;

/**
 * Price Filter
 */
class Price extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price
{
    /**
     * Core event manager proxy
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager = null;

    /**
     * @var \Magento\Catalog\Model\Layer
     */
    private $layer;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var IndexScopeResolverInterface|null
     */
    private $priceTableResolver;

    /**
     * @var Context
     */
    private $httpContext;

    /**
     * @var DimensionFactory|null
     */
    private $dimensionFactory;

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
        $this->layer = $layerResolver->get();
        $this->session = $session;
        $this->storeManager = $storeManager;
        $this->_eventManager = $eventManager;
        $this->priceTableResolver = $priceTableResolver
            ?? ObjectManager::getInstance()->get(IndexScopeResolverInterface::class);
        $this->httpContext = $httpContext ?? ObjectManager::getInstance()->get(Context::class);
        $this->dimensionFactory = $dimensionFactory ?? ObjectManager::getInstance()->get(DimensionFactory::class);
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
