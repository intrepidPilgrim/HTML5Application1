/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

kkDBModule.controller('billingController',['$scope', 'queryDB','$window','$timeout', function($scope, queryDB, $window, $timeout) {
     
    $scope.date = new Date();
    $scope.selected= undefined;
    $scope.billing = {};
    
    $scope.billing = {
        "customer_name":"Enter New Customer Name",
        "date_billing":"Select New Date",
        "customer_add":"Enter New Customer Address",
        "subject_billing":"Enter Billing Subject"
        
    };
    //onload query database for existing id_billing numbers
    query_id_billing('update.php', "action=id_billing_query", "id_numbers");
    
    $scope.onSelect_id_billing = function($item, $model, $label){
        //on select, query database for existing record with selected id_billing nunmber
        query_id_billing('update.php', "action=id_billing_record_query&id_billing="+ $label, "id_record");
 
    };
    
    $scope.onSelect_customer_name = function($item, $model, $label){

        //disable billingIDblur
        $scope.billingIDblur.trigBool = false;
        query_id_billing("update.php", "action=latest_customer_record&customer_name=" + $scope.billing.customer_name, "latest_customer_record");
        //bind billing/ textboxes to billingData if id_billing exists in billingData
    };
    $scope.billingIDblur = function($event){
        //console.log($event);
        trigBool = true;

        trigBlur = function(bool){
            if(bool){
                if(($scope.id_billing.indexOf($scope.selected))<0){
                   newRecord();
                }else{
                    query_id_billing('update.php', "action=id_billing_record_query&id_billing="+ $scope.selected, "id_record");
                }
            }
        };
        

        //$timeout(function(){
            
            //on id_billing blur, check if input value is 
            
                trigBlur(trigBool);
                 
                 
        //},1000);
        //bind billing textboxes to billingData if id_billing exists in billingData
    };
    
    $scope.billingUpdate = function(id_billing, col_billing, data_billing){
        console.log("action=update_record&id_billing="+id_billing +"&"+col_billing+"="+data_billing);
        query_id_billing('update.php', "action=update_record&id_billing="+id_billing +"&"+col_billing+"="+data_billing, "update_record");
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
                $scope.id_cust_names = data[1];
            break;
            case 'id_record':
                $scope.billing = data;
            break;
            case 'find_last_record':
                $scope.selected = Number(data["id_billing"]) + 1;
                //insert new record
                var qParams = {};
                //qParams = $scope.billing;
                //qParams["action"] = "new_record";
                //console.log($scope.billing);
                //query_id_billing('update.php', "action=new_record", "new_record");
            break;
            case 'latest_customer_record':
                $scope.selected = data["id_billing"];
                $scope.billing = data;
            break;
            case 'update_record':
                $scope.message = data;
            break;
        }
        
    }
        
    function simpleKeys (original) {
      return Object.keys(original).reduce(function (obj, key) {
        obj[key] = typeof original[key] === 'object' ? '{ ... }' : original[key];
        return obj;
      }, {});
    }
    
    function newRecord(){    
        if(($scope.id_billing.indexOf($scope.selected))<0){
            //find last id_billing number
            query_id_billing('update.php', "action=find_last_record", "find_last_record");
            //$scope.billing = newRecord_Billing;
         }            
    }
    
    function updateOnBlur(){
        
        
    };
}]);
 