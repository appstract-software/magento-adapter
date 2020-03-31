# MagentoAdapter

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg?cacheSeconds=2592000)

This module provides Magento 2 with missing features.

## Getting Started

```bash
$ composer require appstract-software/magento-adapter
$ bin/magento module:enable Appstractsoftware_MagentoAdapter   # enable the module
$ bin/magento setup:upgrade                                    # upgrade Magento database schemas
```

## Features

### Product REST API

| Role      | Method    | URL                                    | Description                                     |
| --------- | --------- | -------------------------------------- | ----------------------------------------------- |
| Anonymous | **`GET`** | `/rest/V1/products/:sku/links`         | Get all product links as object                 |
| Anonymous | **`GET`** | `/rest/V1/product-links/:sku`          | Get all product links as object                 |
| Anonymous | **`GET`** | `/rest/V1/product-links/:sku/:type`    | Get product links by type                       |
| Anonymous | **`GET`** | `/rest/V1/products/new/:limit`         | Get new products `default: [limit: 10]`         |
| Anonymous | **`GET`** | `/rest/V1/products/best-seller/:limit` | Get bestseller products `default: [limit: 10]`  |
| Anonymous | **`GET`** | `/rest/V1/products/most-viewed/:limit` | Get most viewed products `default: [limit: 10]` |
| Anonymous | **`GET`** | `/rest/V1/products/top-rated/:limit`   | Get top rated products `default: [limit: 10]`   |

### Category filters REST API

| Role      | Method    | URL                                       | Description          |
| --------- | --------- | ----------------------------------------- | -------------------- |
| Anonymous | **`GET`** | `/rest/V1/categories/:categoryId/filters` | Get category filters |

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
