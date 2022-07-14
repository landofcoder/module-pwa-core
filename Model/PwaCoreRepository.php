<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\PwaCore\Model;

use Lof\PwaCore\Api\Data\SuggestionTagInterfaceFactory;
use Lof\PwaCore\Api\Data\SuggestionTagInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory;
use Lof\PwaCore\Api\PwaRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Lof\PwaCore\Helper\Data as PwaCoreHelperData;

class PwaCoreRepository implements PwaRepositoryInterface
{
    protected $dataPwaCoreSuggestionTagFactory;

    private $collectionProcessor;

    protected $dataObjectHelper;

    protected $extensionAttributesJoinProcessor;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $extensibleDataObjectConverter;
    protected $resource;

    protected $deviceTokenCollectionFactory;

    private $storeManager;

    protected $deviceTokenFactory;

    protected $helper;
    protected $_searchQuery;
    protected $_cmsPage;
    protected $_cmsBlock;
    protected $_catalogConfig;
    protected $_productCollection;
    protected $_productStatus;
    protected $_productVisibility;
    protected $_stockFilter;
    protected $_queriesFactory;

    /**
     * @param SuggestionTagInterfaceFactory $dataPwaCoreSuggestionTagFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param PwaCoreHelperData $helper
     * @param ProductSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\CatalogInventory\Helper\Stock $stockFilter
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param \Magento\Search\Model\Query $searchQuery
     * @param \Magento\Cms\Model\Page $cmsPage
     * @param \Magento\Cms\Model\Block $cmsBlock
     * @param \Magento\Search\Model\ResourceModel\Query\CollectionFactory $queriesFactory
     */
    public function __construct(
        SuggestionTagInterfaceFactory $dataPwaCoreSuggestionTagFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        PwaCoreHelperData $helper,
        ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Search\Model\Query $searchQuery,
        \Magento\Cms\Model\Page $cmsPage,
        \Magento\Cms\Model\Block $cmsBlock,
        \Magento\Search\Model\ResourceModel\Query\CollectionFactory $queriesFactory
    ) {
        $this->dataPwaCoreSuggestionTagFactory = $dataPwaCoreSuggestionTagFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->helper = $helper;
        $this->_searchQuery = $searchQuery;
        $this->_cmsPage = $cmsPage;
        $this->_cmsBlock = $cmsBlock;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->_catalogConfig = $catalogConfig;
        $this->_productCollection            = $productCollection;
        $this->_productStatus                = $productStatus;
        $this->_productVisibility            = $productVisibility;
        $this->_stockFilter                  = $stockFilter;
        $this->_queriesFactory   = $queriesFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getSuggestionTags($keyword = null, $storeId = null)
    {
        if ($this->helper->getConfig("searchsuggestion/display_tag")) {
            $tagArray = [];
            $featuredTags = $this->helper->getConfig("searchsuggestion/featured_tags");
            $limit = $this->helper->getConfig("searchsuggestion/tag_limit");
            $limit = $limit ? (int)$limit : 1;
            $query = $this->helper->getSearchKeyword($keyword);
            if ($query) {
                $tagCollection = $this->_queriesFactory->create()
                                ->addFieldToFilter("is_active", 1)
                                ->addFieldToFilter("store_id", ["in" => [0, $storeId]])
                                ->addFieldToFilter("query_text", ["like" => "%".$query."%"])
                                ->setPageSize($limit);

				$tagCollection->getSelect()->order('main_table.num_results DESC');
                foreach ($tagCollection as $item) {
                    $tagItemData = [
                        "term"           => $query,
                        "title"          => $item->getQueryText(),
                        "num_of_results" => $item->getNumResults(),
                        "is_featured"    => 0,
                        "is_hot"    => 0
                    ];
                    $tagItemDataObject = $this->dataPwaCoreSuggestionTagFactory->create();
                    $this->dataObjectHelper->populateWithArray(
                        $tagItemDataObject,
                        $tagItemData,
                        SuggestionTagInterface::class
                    );
                    $tagArray[] = $tagItemDataObject;
                }
            }
            if (!$tagArray && $featuredTags) {
                $featuredTags = trim($featuredTags);
                $featuredTagsArray = explode(",",$featuredTags);
                foreach ($featuredTagsArray as $term) {
                    $tagItemData = [
                        "term"           => trim($term),
                        "title"          => trim($term),
                        "is_featured"    => 1,
                        "is_hot"    => 0,
                        "num_of_results" => 100
                    ];
                    $tagItemDataObject = $this->dataPwaCoreSuggestionTagFactory->create();
                    $this->dataObjectHelper->populateWithArray(
                        $tagItemDataObject,
                        $tagItemData,
                        SuggestionTagInterface::class
                    );
                    $tagArray[] = $tagItemDataObject;
                }
            }
            return $tagArray;
        }else {
            throw new NoSuchEntityException(__('The function is not supported.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedTags($limit = 10, $storeId = null)
    {
        if ($this->helper->getConfig("searchsuggestion/display_tag")) {
            $tagArray = [];
            $featuredTags = $this->helper->getConfig("searchsuggestion/featured_tags");
            $limit = $limit ? (int)$limit : 1;
            if (!$tagArray && $featuredTags) {
                $featuredTags = trim($featuredTags);
                $featuredTagsArray = explode(",",$featuredTags);
                foreach ($featuredTagsArray as $term) {
                    $tagItemData = [
                        "term"           => trim($term),
                        "title"          => trim($term),
                        "is_featured"    => 1,
                        "is_hot"    => 0,
                        "num_of_results" => 100
                    ];
                    $tagItemDataObject = $this->dataPwaCoreSuggestionTagFactory->create();
                    $this->dataObjectHelper->populateWithArray(
                        $tagItemDataObject,
                        $tagItemData,
                        SuggestionTagInterface::class
                    );
                    $tagArray[] = $tagItemDataObject;
                }
            }
            return $tagArray;
        }else {
            throw new NoSuchEntityException(__('The function is not supported.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedProducts(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->getFeaturedProductsCollection();
        if($collection){
            $this->extensionAttributesJoinProcessor->process($collection);
            $this->collectionProcessor->process($criteria, $collection);
            $collection->load();

            $searchResult = $this->searchResultsFactory->create();
            $searchResult->setSearchCriteria($criteria);
            $searchResult->setItems($collection->getItems());
            $searchResult->setTotalCount($collection->getSize());

            return $searchResult;
        }else {
            throw new NoSuchEntityException(__('The function is not supported.'));
        }
    }

    public function getFeaturedProductsCollection($allowAttributes = true)
    {
        if ($this->helper->getConfig("general/featuredproduct") == 1) {
            $collection = $this->_productCollection->create()
                                                    ->addStoreFilter();
            if ($allowAttributes) {
                $attributes = $this->_catalogConfig->getProductAttributes();
                $collection->addAttributeToSelect($attributes)
                            ->addAttributeToSelect("is_featured")
                            ->addAttributeToSelect("visibility")
                            ->addAttributeToFilter("status", ["in"=>$this->_productStatus->getVisibleStatusIds()])
                            ->setVisibility($this->_productVisibility->getVisibleInSiteIds());
            }
            $collection->addAttributeToFilter("is_featured", 1);

            if($this->helper->showOutOfStock() == 0)
                $this->_stockFilter->addInStockFilterToCollection($collection);

            $collection->getSelect()->order("rand()");
            return $collection;
        }
        return false;
    }
}

