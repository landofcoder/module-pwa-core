# Magento 2 Module Lof PwaCore

    ``landofcoder/module-pwa-core``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Magento 2 Pwa Core Settings

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Lof`
 - Enable the module by running `php bin/magento module:enable Lof_PwaCore`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require landofcoder/module-pwa-core`
 - enable the module by running `php bin/magento module:enable Lof_PwaCore`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - lofpwa_footer_block (lofpwa/general/footer_block)
 - lofpwa_footer_top_block (lofpwa/general/footer_top_block)
 - lofpwa_topbar_block (lofpwa/general/topbar_block)
 - lofpwa_contact_us_top_block (lofpwa/general/contact_us_top_block)
 - lofpwa_contact_us_right_block (lofpwa/general/contact_us_right_block)
 - lofpwa_sizechart_block (lofpwa/general/sizechart_block)
 - lofpwa_show_recent_view (lofpwa/general/show_recent_view)
 - lofpwa_show_related_search (lofpwa/general/show_related_search)
 - lofpwa_show_featured_tags (lofpwa/general/show_featured_tags)

## Specifications

 - Helper
	- Lof\PwaCore\Helper\Data

## Attributes


## Example Graph Ql Query

1. Get Search Suggestion Tags by Keyword

```
{
    pwaSuggestionTags(keyword:"ian"){
    term
    title
    num_of_results
    is_featured
    is_hot
    }
}
```

2. Get Random Featured PwaCore Products

```
{
    pwaFeaturedProducts(
    filters: {}
    ){
    total_count
    items{
        id
        name
        sku
        thumbnail {
        url
        label
        position
        }
        price_range {
        minimum_price {
            regular_price {
            value
            currency
            }
            final_price {
            value
            currency
            }
        }
        maximum_price {
            regular_price {
            value
            currency
            }
            final_price {
            value
            currency
            }
        }
        }
    }
    }
}
```

3. Pwa core module settings query

```
query{
  storeConfig {
    lofpwa_footer_block
    lofpwa_topbar_block
    lofpwa_contact_us_top_block
    lofpwa_contact_us_right_block
    lofpwa_sizechart_block
    lofpwa_show_recent_view
    lofpwa_show_related_search
    lofpwa_show_hot_searchs
  }
}
```
