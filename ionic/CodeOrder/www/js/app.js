// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('starter', ['ionic','starter.controllers','angular-oauth2'])

    .run(function($ionicPlatform,$rootScope, OAuth) {
        $ionicPlatform.ready(function() {
            if(window.cordova && window.cordova.plugins.Keyboard) {
                // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
                // for form inputs)
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);

                // Don't remove this line unless you know what you are doing. It stops the viewport
                // from snapping when text inputs are focused. Ionic handles this internally for
                // a much nicer keyboard experience.
                cordova.plugins.Keyboard.disableScroll(true);
            }
            if(window.StatusBar) {
                StatusBar.styleDefault();
            }
        });
        $rootScope.$on('$stateChangeStart',
        function (event, toState, toParams, fromState, fromParams) {
          if(toState.name != 'login'){
            if(OAuth.isAuthenticated()){
                
            }
          }
        })
    })

    .config(function ($stateProvider, $urlRouterProvider, $ionicConfigProvider, OAuthProvider, OAuthTokenProvider) {

        OAuthProvider.configure({
            baseUrl: 'http://localhost:8181',
            clientId: 'testclient',
            clientSecret: 'leonardo',
            grantPath: '/oauth',
            revokePath: '/oauth'
        });

        OAuthTokenProvider.configure({
            name: 'token',
            options: {
                secure: false
            }
        });

        $stateProvider
            .state('tabs', {
                url: '/t',
                abstract: true,
                templateUrl: 'template/tabs.html'
            }).state('tabs.home', {
            url: '/home',
            views: {
                'tabs-home': {
                    templateUrl: 'template/home.html',
                    controller: 'OrdersCtrl'
                }
            }
        }).state('tabs.pedidos', {
            url: '/pedidos',
            views: {
                'tabs-pedidos': {
                    templateUrl: 'template/pedidos.html',
                    controller: 'OrderNewCtrl'
                }
            }
        }).state('tabs.show', {
            url: '/orders/:id',
            views: {
                'tabs-home': {
                    templateUrl: 'template/views.html',
                    controller: 'OrderShowCtrl'
                }
            }
        }).state('login', {
            url: '/login',
            templateUrl: 'template/login.html',
            controller: 'LoginCtrl'
        })
        $urlRouterProvider.otherwise('/login');
    })
