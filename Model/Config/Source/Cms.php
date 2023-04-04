<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\PwaCore\Model\Config\Source;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as CmsCollection;

class Cms implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var CmsCollection
     */
    protected $_cms_page_collection;

    /**
     * construct
     *
     * @param CmsCollection $collection
     */
    public function __construct(
        CmsCollection $collection
    ) {
        $this->_cms_page_collection = $collection;
    }

    /**
     * to option array
     *
     * @return mixed
     */
    public function toOptionArray()
    {
        $cms_pages = $this->getCmsPages();
        $returnData = [];
        foreach ($cms_pages as $val=>$label) {
            $returnData[] =  array(
                "value" => $val,
                "label" => $label
            );
        }
        return $returnData;
    }

    /**
     * to array
     *
     * @return mixed
     */
    public function toArray()
    {
        return $this->getCmsPages();
    }

    /**
     * getCmsPages
     *
     * @return mixed
     */
    public function getCmsPages()
    {
        if (!isset($this->_cms_pages)) {
            $this->_cms_pages = array();
            $collection = $this->_cms_page_collection
                ->create()
                ->addFieldToFilter("is_active", 1)
                ->addFieldToFilter("identifier", ["nin"=>["no-route", "enable-cookies"]]);
            foreach ($collection as $cms) {
                $this->_cms_pages[$cms->getIdentifier()] = $cms->getTitle();
            }
        }
        return $this->_cms_pages;
    }
}

