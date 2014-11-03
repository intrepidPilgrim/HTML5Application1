/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


kkDBModule.controller('billingController',['$scope', 'billingData', function($scope, billingData) {
     
    $scope.message = 'This is the billing screen';
    $scope.date = new Date();
    $scope.selected=undefined;
    $scope.id_billing = ['1a','21a','13a'];
    //$scope.customer_name = billingData.billingDatum[1].customer_name;
    //$scope.billingIDChange = function(){
    //    $scope.subject_billing = $scope.selected;
        //bind billing textboxes to billingData if id_billing exists in billingData
    //};
    $scope.onSelect = function($item, $model, $label){
        $scope.subject_billing = $label;
        //bind billing textboxes to billingData if id_billing exists in billingData
    };
    $scope.billingIDblur = function(){
        $scope.subject_billing = "test";
        //bind billing textboxes to billingData if id_billing exists in billingData
    };
    angular.element(document).ready(function () {
       
    });

}]);
 