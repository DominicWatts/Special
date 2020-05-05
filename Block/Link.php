<?php

namespace Xigen\Special\Block;

/**
 * Xigen Special header block link class
 */
class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Render block HTML.
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }
        return '<li><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }
}
