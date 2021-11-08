# Magento2 Custom Catalog Module
Module adds custom catalog functionality allowing
managing custom products via admin UI and webapi.

**Compatibility**: Magento 2.4.3

## Admin UI
New menu item "Custom Catalog" under Catalog>Inventory 
allowing managing products based on different scopes (stores). 
Basic CRUD operations available via grid with filtering. 
Store switcher on top left allows switch between store views and see different data scopes.

## WebApi Endpoints
* **Search products by VPN**

**Endpoint:** V1/product/getByVPN/:vpn

**Method:** GET

**Result:** 
```json
{
    "items": [
        {
            "entity_id": 6,
            "product_id": "product_id1",
            "vpn": "unique-vpn",
            "sku": "sku 1",
            "copy_write_info": "copy write info"
        },
        {
            "entity_id": 8,
            "product_id": "product_id2",
            "vpn": "unique-vpn",
            "sku": "sku 2",
            "copy_write_info": "test"
        }
    ],
    "search_criteria": {
        "filter_groups": [
            {
                "filters": [
                    {
                        "field": "vpn",
                        "value": "unique-vpn",
                        "condition_type": "eq"
                    }
                ]
            }
        ]
    },
    "total_count": 2
}

```

* **Asynchronous product updates** 

Update request is added to rabbitmq queue and processed asynchronously. 

**Endpoint:** V1/product/update

**Method:** PUT

**Body request:** 

Adding new product:
```json
{
    "product": {
      "product_id":"unique-product-id",
      "vpn":"custom-vpn",
      "sku":"sku",
      "copy_write_info": "example copy write info"
    }
}

```
Editing existing product (via `entry_id`)
```json
{
    "product": {
      "entity_id": 123,
      "product_id":"unique-product-id",
      "vpn":"custom-vpn",
      "sku":"sku",
      "copy_write_info": "example copy write info"
    }
}

```


**Result:** Information how data was inserted into queue:
```json
{
  "product": {
    "entity_id": 123,
    "product_id": "unique-product-id",
    "vpn": "custom-vpn",
    "sku": "sku",
    "copy_write_info": "example copy write info"
  }
}

```


    
