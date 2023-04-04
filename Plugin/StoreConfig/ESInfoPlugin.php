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

namespace Lof\PwaCore\Plugin\StoreConfig;
use Smile\ElasticsuiteCore\Helper\IndexSettings;
use Magento\Store\Model\StoreManagerInterface;

class ESInfoPlugin
{

    CONST IDENTIFIERS = [ 
        'es_index_name_catalog_category' => 'catalog_category',
        'es_index_name_catalog_product' => 'catalog_product',
        'es_index_name_marketplace_seller' => 'marketplace_seller',
        'es_index_name_thesaurus' => 'thesaurus'
    ];

    /**
     * @var IndexSettings
     */
    protected $indexSetting;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;


    /**
     * @param IndexSettings
     * @param StoreManagerInterface
     */
    public function __construct(
        IndexSettings $indexSetting,
        StoreManagerInterface $storeManager
    ) 
    {
        $this->indexSetting = $indexSetting;
        $this->_storeManager = $storeManager;
    }


    public function afterGetStoreConfigData(\Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider $subject, $result)
    {
        $store = $this->_storeManager->getStore();
        
        foreach (self::IDENTIFIERS as $key_config => $identifier) {
            $name = $this->indexSetting->createIndexNameFromIdentifier($identifier, $store);
            $result[$key_config] = $name;
        }
        return $result;
    }

}

