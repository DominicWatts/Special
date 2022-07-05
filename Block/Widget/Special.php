<?php

namespace Xigen\Special\Block\Widget;

/**
 * Xigen Special Widget block class
 */
class Special extends \Magento\Catalog\Block\Product\NewProduct implements \Magento\Widget\Block\BlockInterface
{
    /**
     * Default value whether show pager or not
     */
    const DEFAULT_SHOW_PAGER = false;

    /**
     * Default value for products per page
     */
    const DEFAULT_PRODUCTS_PER_PAGE = 5;

    /**
     * Name of request parameter for page number value
     */
    const PAGE_VAR_NAME = 'fp';

    /**
     * Instance of pager block
     * @var \Magento\Catalog\Block\Product\Widget\Html\Pager
     */
    protected $_pager;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * Special constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $data
        );
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    /**
     * Product collection initialize process
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|Object|\Magento\Framework\Data\Collection
     */
    protected function _getProductCollection()
    {
        return $this->_getSpecialProductsCollection();
    }

    /**
     * Prepare collection for recent product list
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|Object|\Magento\Framework\Data\Collection
     */
    protected function _getSpecialProductsCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelect()->orderRand();
        $date = new \Zend_Date();

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter(
                'special_from_date',
                [
                    'or' => [
                        0 => [
                            'date' => true,
                            'to' => $date->get('YYYY-MM-dd') . ' 23:59:59'
                        ],
                        1 => [
                            'is' => new \Zend_Db_Expr('null')
                        ],
                    ]
                ],
                'left'
            )
            ->addAttributeToFilter(
                'special_to_date',
                [
                    'or' => [
                        0 => [
                            'date' => true,
                            'from' => $date->get('YYYY-MM-dd') . ' 00:00:00'
                        ],
                        1 => [
                            'is' => new \Zend_Db_Expr('null')
                        ],
                    ]
                ],
                'left'
            )
            ->addAttributeToFilter('special_price', ['gt' => '0.1'])
            ->addAttributeToFilter('price', ['gt' => '0.1'])
            // ->addAttributeToFilter('special_price', ['lt' => new \Zend_Db_Expr('at_price.value')])
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getCurrentPage());

        return $collection;
    }

    /**
     * Get number of current page based on query value
     * @return int
     */
    public function getCurrentPage()
    {
        return abs((int) $this->getRequest()->getParam($this->getData('page_var_name')));
    }

    /**
     * Get key pieces for caching block content
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                $this->getProductsPerPage(),
                (int) $this->getRequest()->getParam($this->getData('page_var_name'), 1),
                $this->serializer->serialize($this->getRequest()->getParams())
            ]
        );
    }

    /**
     * Retrieve how many products should be displayed
     * @return int
     */
    public function getProductsCount()
    {
        if (!$this->hasData('products_count')) {
            return parent::getProductsCount();
        }
        return $this->getData('products_count');
    }

    /**
     * Retrieve how many products should be displayed
     * @return int
     */
    public function getProductsPerPage()
    {
        if (!$this->hasData('products_per_page')) {
            $this->setData('products_per_page', self::DEFAULT_PRODUCTS_PER_PAGE);
        }
        return $this->getData('products_per_page');
    }

    /**
     * Return flag whether pager need to be shown or not
     * @return bool
     */
    public function showPager()
    {
        if (!$this->hasData('show_pager')) {
            $this->setData('show_pager', self::DEFAULT_SHOW_PAGER);
        }
        return (bool) $this->getData('show_pager');
    }

    /**
     * Retrieve how many products should be displayed on page
     * @return int
     */
    protected function getPageSize()
    {
        return $this->showPager() ? $this->getProductsPerPage() : $this->getProductsCount();
    }

    /**
     * Render pagination HTML
     * @return string
     */
    public function getPagerHtml()
    {
        if ($this->showPager()) {
            if (!$this->_pager) {
                $this->_pager = $this->getLayout()->createBlock(
                    \Magento\Catalog\Block\Product\Widget\Html\Pager::class,
                    'widget.special.product.list.pager'
                );

                $this->_pager->setUseContainer(true)
                    ->setShowAmounts(true)
                    ->setShowPerPage(false)
                    ->setPageVarName($this->getData('page_var_name'))
                    ->setLimit($this->getProductsPerPage())
                    ->setTotalLimit($this->getProductsCount())
                    ->setCollection($this->_getProductCollection());
            }
            if ($this->_pager instanceof \Magento\Framework\View\Element\AbstractBlock) {
                return $this->_pager->toHtml();
            }
        }
        return '';
    }

    /**
     * Return HTML block with price
     * @param \Magento\Catalog\Model\Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['zone'] = isset($arguments['zone'])
            ? $arguments['zone']
            : $renderZone;
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }
}
