<?php 

/**
 * Copyright © Dzinehub, Inc. All rights reserved.
 */

namespace Dzinehub\Banners\Block\Adminhtml;

/**
 * Adminhtml Notification Block
 */
class Banner extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
	protected function _construct()
	{
		$this->_blockGroup = 'Dzinehub_Banners';
        $this->_controller = 'adminhtml_index';
        $this->_headerText = __('Banner');
        $this->_addButtonLabel = __('Add New Banner');
        parent::_construct();
	}

}