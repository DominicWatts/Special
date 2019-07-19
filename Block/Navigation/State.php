<?php

namespace Xigen\Special\Block\Navigation;

/**
 * State block class
 */
class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    /**
     * State constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Xigen\Special\Model\Layer\Resolver $layerResolver
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Xigen\Special\Model\Layer\Resolver $layerResolver,
        array $data = []
    ) {
        parent::__construct($context, $layerResolver, $data);
    }
}
