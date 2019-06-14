<?php
namespace Xigen\Special\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Layer class
 */
class Layer extends \Magento\Catalog\Model\Layer
{
    public function __construct(
        \Magento\Catalog\Model\Layer\ContextInterface $context,
        \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory,
        AttributeCollectionFactory $attributeCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $catalogProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct(
            $context,
            $layerStateFactory,
            $attributeCollectionFactory,
            $catalogProduct,
            $storeManager,
            $registry,
            $categoryRepository,
            $data
        );
    }

    public function getProductCollection()
    {
        if (isset($this->_productCollections['xigen_custom'])) {
            $collection = $this->_productCollections['xigen_custom'];
        } else {
            $collection = $this->productCollectionFactory->create();

            $date = new \Zend_Date();

            $collection->addAttributeToFilter(
                'special_from_date',
                [
                    'or' => [
                        0 => [
                            'date' => true,
                            'to' => $date->get('YYYY-MM-dd').' 23:59:59'],
                        1 => [
                            'is' => new \Zend_Db_Expr('null')
                        ],
                    ]
                ],
                'left'
            );

            $collection->addAttributeToFilter(
                'special_to_date',
                [
                    'or' => [
                        0 => [
                            'date' => true,
                            'from' =>  $date->get('YYYY-MM-dd').' 00:00:00'],
                        1 => [
                            'is' => new \Zend_Db_Expr('null')
                        ],
                    ]
                ],
                'left'
            );

            $collection->addAttributeToFilter('special_price', ['gt' => '0.1']);
            $collection->addAttributeToFilter('price', ['gt' => '0.1']);
            $collection->addAttributeToFilter('special_price', ['lt' => new \Zend_Db_Expr('at_price.value')]);

            $this->prepareProductCollection($collection);
            $this->_productCollections['xigen_custom'] = $collection;
        }
        return $collection;
    }
}
