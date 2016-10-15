angular.module('starter.controllers',[])
    .controller('LoginCtrl',['$scope', '$http','$state', 'OAuth', 'OAuthToken',
        function ($scope, $http, $state, OAuth, OAuthToken){
            $scope.login = function(data) {
                OAuth.getAccessToken(data).then(function(){
                    $state.go('tabs.home');
                }, function (data) {
                    $scope.error_login = "Usuário ou senha inválidos";
                });
            }
        }
    ])
    .controller('OrdersCtrl',['$scope','$http','$state',
        function ($scope, $http, $state) {
            $scope.getOrders = function () {
                $http.get('http://localhost:8181/orders').then(
                    function (data) {
                        $scope.orders = data.data._embedded.orders;
                        console.log($scope.orders);
                    }
                )
            };
            $scope.show=function (order) {
                $state.go('tabs.show', {id: order.id})
            };

            $scope.doRefresh = function () {
                $scope.getOrders();
                $scope.$broadcast('scroll.refreshComplete');
            };
            $scope.getOrders();
        }])
    .controller('OrderShowCtrl', ['$scope', '$http', '$stateParams',
        function ($scope, $http, $stateParams) {
            $scope.getOrder = function () {
                $http.get('http://localhost:8181/orders/' + $stateParams.id).then(
                    function (data) {
                        $scope.order = data.data;

                    }
                )
            }
            $scope.getOrder();
        }])
    .controller('OrderNewCtrl',['$scope','$http','$state',
        function ($scope, $http, $state) {
            $scope.clients = [];
            $scope.ptypes = [];
            $scope.products = [];
            $scope.statusList = ['Pedente','Processando','Entregue'];
            $scope.resetOrder = function () {
                $scope.order = {
                    client_id: '',
                    ptype_id: '',
                    item: []
                };
            };
            $scope.getClient = function () {
                $http.get('http://localhost:8181/clients').then(
                    function (data) {
                        $scope.clients = data.data._embedded.clients;
                    }
                )
            };
            $scope.getPtypes = function () {
                $http.get('http://localhost:8181/ptypes').then(
                    function (data) {
                        $scope.ptypes = data.data._embedded.ptypes;
                    }
                )
            };
            $scope.getProducts = function () {
                $http.get('http://localhost:8181/products').then(
                    function (data) {
                        $scope.products = data.data._embedded.products;
                    }
                )
            };

            $scope.setPrice = function (index) {
              var product_id = $scope.order.item[index].product_id;
                for(var i in $scope.products){
                    if($scope.products.hasOwnProperty(i) && $scope.products[i].id == product_id){
                        $scope.order.item[index].price = $scope.products[i].price;
                        break;
                    }
                }
            };
            $scope.addItem = function () {
                $scope.order.item.push({
                    product_id: '',
                    quantity: '',
                    price: 0,
                    total:0
                });
            };
            $scope.calculateTotalRow = function (index) {
                $scope.order.item[index].total = $scope.order.item[index].quantity * $scope.order.item[index].price;
            }
            $scope.save = function () {
                $http.post('http://localhost:8181/orders',$scope.order).then(
                    function (data) {
                        $scope.resetOrder();
                        $state.go('tabs.orders');
                    }
                )
            }
            $scope.resetOrder();
            $scope.getClient();
            $scope.getPtypes();
            $scope.getProducts();
        }
    ]);