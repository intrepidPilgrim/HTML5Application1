/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


kkDBModule.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/billing', {
        templateUrl: 'views/billing.html',
        controller: 'billingController'
    }).
      when('/reports', {
        templateUrl: 'views/reports.html',
        controller: 'reportsController'
      }).
      otherwise({
        redirectTo: '/'
      });
}]);