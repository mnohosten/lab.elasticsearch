# Import categories
```bash
split -l 2000 products.json split.products.json
ls split.products.json* | xargs -I '{}' curl -s -H "Content-Type: application/x-ndjson" -XPOST localhost:9200/product/_bulk --data-binary @{}
rm -rf split.products.json*
```

# Import categories
```bash
curl -s -H "Content-Type: application/x-ndjson" -XPOST localhost:9200/category/_bulk --data-binary @categories.json
```
