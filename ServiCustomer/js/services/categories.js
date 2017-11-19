angular
    .module('servi.services.categories', [])
    .service('Categories', function() {
        var categories = [
            { id: "1", "name": "Cocktails", "description": "Click to view our featured house cocktails" },
            { id: "2", "name": "Beers", "description": "Click to view our featured craft beers" },
            { id: "3", "name": "Wines", "description": "Click to view our featured house cocktails" },
            { id: "4", "name": "Beverages", "description": "Click to view our featured cold beverages" }
        ];

        function getAll() {
            return categories;
        }

        function get(id) {
            //TODO: replace with Array.prototype.find but with polyfills
            var category = categories.filter(function (category) { return category.id === id; });

            if (category.length) {
                return category.shift();
            }

            return null;
        }

        function getWithName(name) {
            //TODO: replace with Array.prototype.find but with polyfill
            var category = categories.filter(function (category) { return category.name.toLowerCase() === name.toLowerCase(); });

            if (category.length) {
                return category.shift();
            }

            return null;
        }

        return {
            get: get,
            getAll: getAll,
            getWithName: getWithName
        };
    });