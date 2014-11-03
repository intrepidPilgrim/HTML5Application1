/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


kkDBModule.factory('billingData', function(){
     
    //var fac = {};
     
    //fac.bilingData = 'test'; 
    dumData = {
                "billingDatum":
                [{
                    "id_billing":"0001",
                    "date_billing":"1/1/2014",
                    "customer_name":"Enter Customer Name",
                    "customer_add":"Enter Customer Address",
                    "subject_billing":"Subject",
                    "emp_billing":"Employee Name",
                    "totalwords_billing":"one two three",
                    "total_billing":"222.00",
                    "period_billing":null
                },
        
                {
                    "id_billing":"0002",
                    "date_billing":"1/1/2014",
                    "customer_name":"Enter Customer Name02",
                    "customer_add":"Enter Customer Address02",
                    "subject_billing":"Subject02",
                    "emp_billing":"Employee Name",
                    "totalwords_billing":"one two three",
                    "total_billing":"222.00",
                    "period_billing":null
                }]
            };
     
    return dumData;
 
});