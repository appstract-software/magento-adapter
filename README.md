# MagentoAdapter

![Version](https://img.shields.io/badge/version-1.1.1-blue.svg?cacheSeconds=2592000)

This module provides Magento 2 with missing features.

## Getting Started

[Magento 2 docs](https://devdocs.magento.com/cloud/howtos/install-components.html#install-an-extension)

```bash
$ composer require appstract-software/magento-adapter
$ bin/magento module:enable Appstractsoftware_MagentoAdapter   # enable the module
$ bin/magento setup:upgrade                                    # upgrade Magento database schemas
```

## Features

### Custom REST API Endpoints

#### Product REST API

| Role                      | Method    | URL                                      | Description                                     |
| ------------------------- | --------- | ---------------------------------------- | ----------------------------------------------- |
| Anonymous                 | **`GET`** | `/rest/V1/products/:sku/links`           | Get all product links as object                 |
| Anonymous                 | **`GET`** | `/rest/V1/product-links/:sku`            | Get all product links as object                 |
| Anonymous                 | **`GET`** | `/rest/V1/product-links/:sku/:type`      | Get product links by type                       |
| Anonymous                 | **`GET`** | `/rest/V1/product-options/products/:sku` | Get product options                             |
| Anonymous                 | **`GET`** | `/rest/V1/product-options/category`      | Get product options in category (search)        |
| Anonymous                 | **`GET`** | `/rest/V1/products/new/:limit`           | Get new products `default: [limit: 10]`         |
| Anonymous                 | **`GET`** | `/rest/V1/products/best-seller/:limit`   | Get bestseller products `default: [limit: 10]`  |
| Anonymous                 | **`GET`** | `/rest/V1/products/most-viewed/:limit`   | Get most viewed products `default: [limit: 10]` |
| Anonymous                 | **`GET`** | `/rest/V1/products/top-rated/:limit`     | Get top rated products `default: [limit: 10]`   |
| Magento_Catalog::products | **`GET`** | `/rest/V1/products/search`               | Filter products (with configurable type)        |
| Anonymous                 | **`GET`** | `/rest/V1/products/search-query`         | Search products using search engine proxy       |

#### Category filters REST API

| Role      | Method    | URL                                       | Description          |
| --------- | --------- | ----------------------------------------- | -------------------- |
| Anonymous | **`GET`** | `/rest/V1/categories/:categoryId/filters` | Get category filters |

#### Wishlist REST API

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

#### Orders REST API

| Role                                           | Method     | URL                                       | Description          |
| ---------------------------------------------- | ---------- | ----------------------------------------- | -------------------- |
| Anonymous                                      | **`GET`**  | `/rest/V1/orders/status/:id`              | Get order status     |
| Appstractsoftware_MagentoAdapter::order_status | **`POST`** | `/rest/V1/orders/status/:id`              | Set order status     |

#### Payments REST API

| Role                      | Method     | URL                                        | Description                                     |
| ------------------------- | ---------- | ------------------------------------------ | ----------------------------------------------- |
| Anonymous                 | **`POST`** | `/rest/V1/payu/create-order`               | Create PayU order                               |
| Anonymous                 | **`GET`**  | `/rest/V1/payu/order-status/:id`           | Get PayU order details                          |
| Anonymous                 | **`POST`** | `/rest/V1/przelewy24/register-transaction` | Register new Przelewy24 transaction             |

---

### Extension Attributes

#### Product price

**Name:** `product_price`

**For class:** `\Magento\Catalog\Api\Data\ProductInterface`

**Example endpoints:**

- `/rest/V1/products/:sku`
- `/rest/V1/products?searchCriteria[...]`

**Example response:**

- Product without special price:

```json
"extension_attributes": {
    "product_price": {
        "price": 60,
        "currency_price": "60,00 zł",
        "currency_symbol": "zł"
    }
}
```

- Product with special price:

```json
"extension_attributes": {
    "product_price": {
        "price": 32.53,
        "special_price": 32,
        "currency_price": "32,53 zł",
        "currency_special_price": "32,00 zł",
        "currency_symbol": "zł"
    },
}
```
