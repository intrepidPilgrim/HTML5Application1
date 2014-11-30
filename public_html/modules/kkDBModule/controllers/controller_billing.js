/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

kkDBModule.controller('billingController',['$scope', 'queryDB','$window','$timeout', function($scope, queryDB, $window, $timeout) {
     
    $scope.date = new Date();
    $scope.prev_val = "";
    $scope.selected= undefined;
    $scope.billing = {};
        
    trigBool = true;
    
    $scope.billing = {
        "customer":"Enter New Customer Name",
        "date":"Select New Date",
        "customer":"Enter New Customer Address",
        "subject":"Enter Billing Subject"
        
    };
    //onload query database for existing id_billing numbers
    query_id_billing('update.php', "action=id_billing_query", "id_numbers");
    
    
    $scope.onSelect_id_billing = function($item, $model, $label){
        //on select, query database for existing record with selected id_billing nunmber
        trigBool = false;
        query_id_billing('update.php', "action=id_billing_record_query&id="+ $label, "id_record");
    }; 
    $scope.onSelect_customer_name = function($item, $model, $label){
        //disable billingIDblur
        console.log($scope.billing.customer_name);
        trigBool = false;
        query_id_billing("update.php", "action=latest_customer_record&customer=" + encodeURIComponent($scope.billing.customer), "latest_customer_record");
        //bind billing/ textboxes to billingData if id_billing exists in billingData
    };
    
    $scope.billingIDblur = function($event){
        //console.log($event);
        trigBlur = function(bool){
            if(bool){
                if($event.target.id === "id"){
                    if(($scope.ids.indexOf($scope.selected))<0){
                       newRecord();
                    }else{
                        query_id_billing('update.php', "action=id_billing_record_query&id="+ $scope.selected, "id_record");
                    }
                }else if($event.target.id === "customer"){
                    if((($scope.customers.indexOf($scope.billing.customer))< 0)& (($scope.prev_value) !== ($scope.billing.customer))){
                       $scope.billingUpdate($scope.selected, 'customer', $scope.billing.customer);
                       
                    }else{
                        if($scope.prev_value !== $scope.billing.customer){
                            //$window.alert("jump to this client's latest record?");
                            console.log($scope.prev_value);
                            //query_id_billing("update.php", "action=latest_customer_record&customer=" + encodeURIComponent($scope.billing.customer), "latest_customer_record");
                        }
                    };
                };
            }
        };
        
        //fix: should not trigger if blur comes from on_select event
        $timeout(function(){
                trigBlur(trigBool);
                trigBool = true;     
        },500);
    };
    
    $scope.billingUpdate = function(id, col_billing, data_billing){
        console.log("action=update_record&id="+id +"&"+col_billing+"="+data_billing);
        query_id_billing('update.php', "action=update_record&id="+ id +"&"+col_billing+"="+data_billing, "update_record");
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
                $scope.ids = data[0];
                $scope.customers = data[1];
            break;
            case 'id_record':
                $scope.billing = data[0];
                $scope.grid_data = data[1];
                console.log($scope.grid_data);
               
            break;
            case 'find_last_record':
                $scope.selected = Number(data["id"]) + 1;
                //insert new record
                var qParams = {};
                //qParams = $scope.billing;
                //qParams["action"] = "new_record";
                //console.log($scope.billing);
                //query_id_billing('update.php', "action=new_record", "new_record");
            break;
            case 'latest_customer_record':
                $scope.selected = data["id"];
                $scope.billing = data;
                console.log(data);
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
        if(($scope.ids.indexOf($scope.selected))<0){
            //find last id_billing number
            query_id_billing('update.php', "action=find_last_record", "find_last_record");
            //$scope.billing = newRecord_Billing;
         }            
    }
    
    function updateOnBlur(){
        
        
    };
    function getGridData(id_billing){
        grid_data = $scope.billing;
        return grid_data;
    };
    $scope.cust_focus = function cust_focus(){
        $scope.prev_value = $scope.billing.customer;
        console.log($scope.prev_value);
    };
}]);
 