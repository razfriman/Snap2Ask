var adminApp = angular.module('adminApp', []);


adminApp.controller('UserListCtrl', ['$scope', '$http', function ($scope, $http) {
    $http.get('http://snap2ask.com/git/snap2ask/Code/web/api/index.php/users').success(function (data) {
        $scope.users = data;
    });
}]);