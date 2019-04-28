# Vyhledavani v produktovem katalogu

`GET /pilulka_cz/product/_search`
```json
{
  "query": {
    "bool": {
      "must": [
        {"terms": {"manufacturer.id": [14128]}},
        {"terms": {"brand.id": [945, 1410]}},
        {"terms": {"paramGroups.params.id": [8, 6]}},
        {"terms": {"categories": [803]}},
        {"range": {"price": {"gte": 0, "lte": 1000}}}
      ]
    }
  },
  "aggs": {
    "minimal_price": {
      "min": {"field": "price"}
    },
    "maximal_price": {
      "max": {"field": "price"}
    },
    "by_manufacturer": {
      "terms": {
        "field": "manufacturer.id",
        "order": {
          "_count": "desc"
        },
        "size": 5
      }
    },
    "by_brand": {
      "terms": {
        "field": "brand.id",
        "order": {
          "_count": "desc"
        },
        "size": 5
      }
    },
    "by_category": {
      "terms": {
        "field": "categories",
        "order": {
          "_count": "desc"
        },
        "size": 5
      }
    },
    "by_param_groups": {
      "terms": {
        "field": "paramGroups.id",
        "order": {
          "_count": "desc"
        },
        "size": 5
      }
    },
    "by_params": {
      "terms": {
        "field": "paramGroups.params.id",
        "order": {
          "_count": "desc"
        },
        "size": 5
      }
    }
    
  },
  "_source": ["id", "name"], 
  "size": 1
}
```