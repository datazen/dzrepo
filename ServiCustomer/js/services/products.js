angular
.module('servi.services.products', [])
.service('Products', function() {
    var products = [
        {"id":1,"category":1,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
        {"id":2,"category":1,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
        {"id":3,"category":1,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
        {"id":4,"category":1,"name":"Smirnoff Vodka","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"},
        {"id":5,"category":1,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
        {"id":6,"category":1,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
        {"id":7,"category":1,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
        {"id":8,"category":1,"name":"Smirnoff Vodka","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"},
        {"id":9,"category":2,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
        {"id":10,"category":2,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
        {"id":11,"category":3,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
        {"id":12,"category":3,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
        {"id":13,"category":3,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
        {"id":14,"category":3,"name":"Smirnoff Triple Distilled","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"},
        {"id":15,"category":4,"name":"Martini Bianco, Rosso, Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails1.png"},
        {"id":16,"category":4,"name":"Martini Royale","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails2.png"},
        {"id":17,"category":4,"name":"Martini Royale Rosato","blurb":"Martini Bianco, Rosso, Rosato","image":"cocktails3.png"},
        {"id":18,"category":4,"name":"Smirnoff Triple Distilled","blurb":"Smirnoff Triple Distilled, Rasberry, Vanilla Vodka","image":"cocktails4.png"}
    ];

    function getAll() {
        return products;
    }

    function get(id) {
        //TODO: replace with Array.prototype.find but with polyfills
        var product = products.filter(function (product) { return product.id === id; });

        if (product.length) {
            return product.shift();
        }

        return null;
    }

    function getFromCategory(category) {
        return products.filter(function (product) {
            return parseInt(product.category, 10) ===
                    parseInt(category, 10);
        });
    }

    return {
        get: get,
        getAll: getAll,
        getFromCategory: getFromCategory
    };
});