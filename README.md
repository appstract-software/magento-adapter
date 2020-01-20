# MagentoAdapter

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg?cacheSeconds=2592000)

This module provides Magento 2 with missing features.

## Getting Started

```bash
$ composer require "appstract-software/magento-adapter: 1.0.0" # NOT AVAILABLE YET
$ bin/magento module:enable Appstractsoftware_MagentoAdapter   # enable the module
$ bin/magento setup:upgrade                                    # upgrade Magento database schemas
```

## Features

### Wishlist REST API

| Role     | Method       | URL                                                   | Description                                         |
| -------- | ------------ | ----------------------------------------------------- | --------------------------------------------------- |
| Customer | **`POST`**   | `/rest/V1/wishlist/me/product`                        | Add product to my wishlist                          |
| Admin    | **`POST`**   | `/rest/V1/wishlist/:id/product`                       | Add product to wishlist by id                       |
| Admin    | **`POST`**   | `/rest/V1/wishlist/customer/:customerId/product`      | Add product to wishlist by customer id.             |
| Customer | **`GET`**    | `/rest/V1/wishlist/me`                                | Get my wishlist                                     |
| Admin    | **`GET`**    | `/rest/V1/wishlist/:id`                               | Get wishlist by id                                  |
| Admin    | **`GET`**    | `/rest/V1/wishlist/customer/:customerId`              | Get wishlist by customer id                         |
| Admin    | **`GET`**    | `/rest/V1/wishlist/sharing/:sharingCode`              | Get wishlist by sharing code                        |
| Admin    | **`DELETE`** | `/rest/V1/wishlist/:id`                               | Delete wishlist by id                               |
| Admin    | **`DELETE`** | `/rest/V1/wishlist/:id/item/:itemId`                  | Delete item by item id from wishlist by id          |
| Admin    | **`DELETE`** | `/rest/V1/wishlist/customer/:customerId/item/:itemId` | Delete item by item id from wishlist by customer id |
| Customer | **`DELETE`** | `/rest/V1/wishlist/me/item/:itemId`                   | Delete item by item id from my wishlist             |
