(function () {
    'use strict';

    angular
        .module('french', ['pascalprecht.translate'])
        .config(config);

    config.$inject = ['$translateProvider'];
    function config($translateProvider) {

        $translateProvider.translations('fr', {
            TITLE: 'Accueil du site',
            TEXT_1: 'Ceci est la page d\'accueil du site web. Nous remplacerons ceci par un site web et un formulaire d\'inscription.',
            TEXT_2: 'Processus d\'inscription va créer une entreprise enfant sous la société maître (HQ)',
            TEXT_3: 'Cette application est une conception multi-site multilingue d\'une page et prend en charge votre langue!'
        });

    }   

})();