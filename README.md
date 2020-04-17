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

| Role      | Method    | URL                                    | Description                                     |
| --------- | --------- | -------------------------------------- | ----------------------------------------------- |
| Anonymous | **`GET`** | `/rest/V1/products/:sku/links`         | Get all product links as object                 |
| Anonymous | **`GET`** | `/rest/V1/product-links/:sku`          | Get all product links as object                 |
| Anonymous | **`GET`** | `/rest/V1/product-links/:sku/:type`    | Get product links by type                       |
| Anonymous | **`GET`** | `/rest/V1/products/new/:limit`         | Get new products `default: [limit: 10]`         |
| Anonymous | **`GET`** | `/rest/V1/products/best-seller/:limit` | Get bestseller products `default: [limit: 10]`  |
| Anonymous | **`GET`** | `/rest/V1/products/most-viewed/:limit` | Get most viewed products `default: [limit: 10]` |
| Anonymous | **`GET`** | `/rest/V1/products/top-rated/:limit`   | Get top rated products `default: [limit: 10]`   |

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

#### Product Options

**Name:** `product_options`

**For class:** `\Magento\Catalog\Api\Data\ProductInterface`

**Example endpoints:**

- `/rest/V1/products/:sku`
- `/rest/V1/products?searchCriteria[...]`

**Example response:**

- Configurable product:

```json
"extension_attributes": {
  "product_options": [
    {
      "id": 42,
      "attribute_id": "145",
      "attribute_code": "size",
      "label": "Size",
      "frontend_label": "Size",
      "store_label": "Size",
      "position": 0,
      "is_use_default": false,
      "product_id": 388,
      "values": [
        {
          "value_index": 167,
          "store_label": "XS"
        },
        {
          "value_index": 168,
          "store_label": "S"
        },
        {
          "value_index": 169,
          "store_label": "M"
        },
        {
          "value_index": 170,
          "store_label": "L"
        },
        {
          "value_index": 171,
          "store_label": "XL"
        }
      ]
    },
    {
      "id": 43,
      "attribute_id": "93",
      "attribute_code": "color",
      "label": "Color",
      "frontend_label": "Color",
      "store_label": "Color",
      "position": 1,
      "is_use_default": false,
      "product_id": 388,
      "values": [
        {
          "value_index": 49,
          "store_label": "Black",
          "products": [
            {
              "sku": "MJ11-L-Green",
              "images": [
                {
                  "id": 637,
                  "media_type": "image",
                  "label": "",
                  "position": "1",
                  "file": "/m/j/mj11-green_main_1.jpg",
                  "url": "http://localhost/magento2/pub/media/catalog/product/m/j/mj11-green_main_1.jpg"
                }
              ],
              "price": {
                "price": 60,
                "currency_price": "60,00 zł",
                "currency_symbol": "zł"
              },
              "attributes": [
                {
                  "store_label": "L",
                  "value_index": "170"
                },
                {
                  "store_label": "Green",
                  "value_index": "53"
                }
              ]
            }
          ]
        }
      ]
    }
  ]
}

```
