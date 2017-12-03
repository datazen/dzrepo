(function () {
    'use strict';

    angular
        .module('spanish', ['pascalprecht.translate'])
        .config(config);
    

    config.$inject = ['$translateProvider'];
    function config($translateProvider) {

        $translateProvider.translations('es', {
            TITLE: 'Sitio Inicio',
            TEXT_1: 'Esta es la página de inicio del sitio frontend. Reemplazaremos esto con un sitio web y un formulario de suscripción.',
            TEXT_2: 'El proceso de inscripción creará una compañía filial bajo la compañía maestra (HQ)',
            TEXT_3: '¡Esta aplicación es Multi-Site Multi-Lingual y es compatible con tu idioma!'
        });

    }   

})();