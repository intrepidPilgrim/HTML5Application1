/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


kkDBModule.factory('billingData', ['$http', function($http){
     
    var billingData = {};
    billingData.get_id_billing = function(){ 
        
        return $http({
          method: 'POST',
          url: 'update.php',
          data: "action=id_billing_query",
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });

    };
    return billingData;
}]);