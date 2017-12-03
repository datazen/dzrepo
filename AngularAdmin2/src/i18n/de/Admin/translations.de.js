(function () {
    'use strict';

    angular
        .module('german', ['pascalprecht.translate'])
        .config(config);
    

    config.$inject = ['$translateProvider'];
    function config($translateProvider) {

        $translateProvider.translations('de', {
            TITLE: 'Standort Startseite',
            TEXT_1: 'Dies ist die Startseite für die Frontend-Site. Wir werden dies durch eine Website und ein Anmeldeformular ersetzen',
            TEXT_2: 'Der Anmeldeprozess erstellt eine untergeordnete Firma unter der Master-Firma (HQ)',
            TEXT_3: 'Diese App ist Multi-Site Multi-Lingual und unterstützt Ihre Sprache!'
        });

    }   

})();