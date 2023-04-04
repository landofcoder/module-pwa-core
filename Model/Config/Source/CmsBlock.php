<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\PwaCore\Model\Config\Source;

class CmsBlock implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Cms\Model\Block
     */
    protected  $_blockModel;

    /**
     * @var \Magento\Cms\Model\Block $blockModel
     */
    public function __construct(
        \Magento\Cms\Model\Block $blockModel
    ) {
        $this->_groupModel = $blockModel;
    }

    /**
     * Top option array
     *
     * @return mixed
     */
    public function toOptionArray()
    {
        $cms_blocks = $this->getCmsBlocks();
        $returnData = [];
        $returnData = [['value' => '', 'label' => __('---- Select a Cms Block ----')]];
        foreach ($cms_blocks as $val=>$label) {
            $returnData[] =  array(
                "value" => $val,
                "label" => $label
            );
        }
        return $returnData;
    }

    /**
     * Top array
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->getCmsBlocks();
    }

    /**
     * get cms blocks
     *
     * @return mixed
     */
    public function getCmsBlocks()
    {
        if (!isset($this->_cms_blocks)) {
            $collection = $this->_groupModel->getCollection()
                                ->addFieldToFilter("is_active", 1);
            foreach ($collection as $_block) {
                $this->_cms_blocks[$_block->getIdentifier()] = $_block->getTitle();
            }
        }
        return $this->_cms_blocks;
    }
}

