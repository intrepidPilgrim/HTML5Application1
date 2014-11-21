/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


kkDBModule.factory('queryDB', ['$http', function($http){
     
    var queryData = {};
    queryData.postQuery = function(url, data){ 
        
        return $http({
          method: 'POST',
          url: url,
          data: data,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });

    };
    return queryData;
}]);