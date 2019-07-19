<?php

namespace Xigen\Special\Model\Layer;

/**
 * Resolver class
 */
class Resolver extends \Magento\Catalog\Model\Layer\Resolver
{
    /**
     * @var \Xigen\Special\Model\Layer
     */
    protected $layer;
    
    /**
     * Resolver constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Xigen\Special\Model\Layer $layer
     * @param array $layersPool
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Xigen\Special\Model\Layer $layer,
        array $layersPool
    ) {
        $this->layer = $layer;
        parent::__construct($objectManager, $layersPool);
    }

    public function create($layerType)
    {
    }
}
