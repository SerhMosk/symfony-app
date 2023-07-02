
1. make start
2. make api1
3. composer install 
4. php bin/console doctrine:migrations:migrate

Api: 
    Product: 
        Index:GET http://localhost/api/product
        Show:GET http://localhost/api/product/{id}
        Create:POST http://localhost/api/product { title:'test', price: 100}
        Update:PUT http://localhost/api/product/{id} { title:'test', price: 100}
        Delete:DELETE http://localhost/api/product/{id}
    Order: 
        Index:GET http://localhost/api/order
        Show:GET http://localhost/api/order/{id}
        Create:POST http://localhost/api/order { amount: 1, product_id: 2}
        Update:PUT http://localhost/api/order/{id} { amount: 1, product_id: 2}
        Delete:DELETE http://localhost/api/order/{id}