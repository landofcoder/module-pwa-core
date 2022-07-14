<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_PwaCore
 * @copyright  Copyright (c) 2021 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */
declare(strict_types=1);

namespace Lof\PwaCore\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected $_storeManager;
    protected $_directoryList;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->_storeManager = $storeManager;
        $this->_directoryList  = $directoryList;
        parent::__construct($context);
    }

    /**
     * Return brand config value by key and store
     *
     * @param string $key
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
     public function getConfig($key, $store = null)
    {
        return $this->getConfigData('lofpwa/'.$key, $store);
    }

    /**
     * get config data
     *
     * @param string $path
     * @param mixed $store
     * @return mixed
     */
    public function getConfigData($path, $store = null)
    {
        $store = $this->_storeManager->getStore($store);
        $result =  $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        return $result;
    }

    /**
     * Get search keyword
     *
     * @param string $searchQuery
     * @return string
     */
    public function getSearchKeyword($searchQuery = "")
    {
        if($searchQuery){
            $min_query_length = (int)$this->getConfigData("catalog/search/min_query_length");
            $query          = is_array($searchQuery) ? "" : trim($searchQuery);
            if(strlen($searchQuery) >= $min_query_length){
                $maxQueryLength = (int)$this->getConfigData("catalog/search/max_query_length");
                $query          = substr($query, 0, $maxQueryLength);
            }else {
                $query = "";
            }
            return $query;
        }
        return "";
    }

    /**
     * show out of stock
     *
     * @return int
     */
    public function showOutOfStock()
    {
        return (int)$this->getConfigData("cataloginventory/options/show_out_of_stock");
    }
}

