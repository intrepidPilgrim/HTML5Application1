<?php
include_once("config.php");

	
	// Prepare form variables for database
	foreach($_POST as $column => $value){
		${$column} = $value;
		if(($column != "action")&&($column != "prev_value")){
			$columns=$columns.",`$column`";
			$values=$values.",'".$mysqli->real_escape_string($value)."'";
			$colVal = $colVal.$column."='".$value."',";
		}
	}
		$trimColumns=trim($columns, ",");
		$trimValues=trim($values, ",");	
		$colVal = trim($colVal, ",");		
		
		if ($_POST["action"]=="query_total"){
			$res=$mysqli->query("SELECT sum(total) as 'total' FROM billing_tables");
		
			$row = mysqli_fetch_assoc($res);
			$sum = $row['total'];
			print "{\"total\":\"$sum\"}";
		}
		if ($_POST["action"]=="query_customers"){
			$custArr = array();
			$customerQuery=$mysqli->query("SELECT billing.customer_name, SUM(billing_tables.total) AS total FROM billing LEFT JOIN billing_tables ON billing.id_billing = billing_tables.id_billing Group BY billing.customer_name");
		
			while($row = mysqli_fetch_assoc($customerQuery)){
				array_push($custArr, $row);
			}
			echo json_encode($custArr);
		}
?>