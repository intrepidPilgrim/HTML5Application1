/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

kkDBModule.controller('billingController',['$scope', 'queryDB','$window','$timeout', function($scope, queryDB, $window, $timeout) {
     
    $scope.message = 'This is the billing screen';
    $scope.date = new Date();
    $scope.selected= undefined;
    $scope.billing = {};
    
    newRecord_Billing = {
        "customer_name":"Enter New Customer Name",
        "date_billing":"Select New Date",
        "customer_add":"Enter New Customer Address",
        "subject_billing":"Enter Billing Subject"
        
    };
    //onload query database for existing id_billing numbers
    query_id_billing('update.php', "action=id_billing_query", "id_numbers");
   
        


    //$scope.customer_name = billingData.billingDatum[1].customer_name;
    //$scope.billingIDChange = function(){
    //    $scope.subject_billing = $scope.selected;
        //bind billing textboxes to billingData if id_billing exists in billingData
    //};
    
    $scope.onSelect = function($item, $model, $label){
        //on select, query database for existing record with selected id_billing nunmber
        query_id_billing('update.php', "action=id_billing_record_query&id_billing="+ $label, "id_record");
        //bind billing/ textboxes to billingData if id_billing exists in billingData
    };
    
    $scope.billingIDblur = function(){
        $timeout(function(){
            //on id_billing blur, check if input value is 
            if(($scope.id_billing.indexOf($scope.selected))<0){
                $scope.billing = newRecord_Billing;
                
            }      
        },1000);
        //bind billing textboxes to billingData if id_billing exists in billingData
    };
    
    function query_id_billing(file_path,queryParam,qType){ 
        queryDB.postQuery(file_path, queryParam)
                .success(function(data){
                    successHandler(data,qType);    
                })
                .error(function(err){
                    $scope.id_billing = "No data" + err.message;
                    //console.log(err);
                });
  
    }
    
    function successHandler(data,qtype){
        switch(qtype){
            case 'id_numbers':
                $scope.id_billing = data[0];
            break;
            case 'id_record':
                $scope.billing = data;
            break;
        }
        
    }
        
    function simpleKeys (original) {
      return Object.keys(original).reduce(function (obj, key) {
        obj[key] = typeof original[key] === 'object' ? '{ ... }' : original[key];
        return obj;
      }, {});
    }

}]);
 