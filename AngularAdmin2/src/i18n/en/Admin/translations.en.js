(function () {
    'use strict';

    angular
        .module('english', ['pascalprecht.translate'])
        .config(config);
    

    config.$inject = ['$translateProvider'];
    function config($translateProvider) {

        $translateProvider.translations('en', {
            TITLE: 'Site Home',
            TEXT_1: 'This is the home page for the frontend site. We will replace this with a website and signup form.',
            TEXT_2: 'Signup process will create a child company under the master company (HQ)',
            TEXT_3: 'This app is One-Page Multi-Lingual Multi-Site design and supports your language!'
        });

    }   

})();