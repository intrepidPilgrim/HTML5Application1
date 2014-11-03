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
	
		if($_POST["action"]=="cName_query"){
			$query_cName = $mysqli->query("SELECT * FROM `billing` WHERE `customer_name` = \"$customer_name\" ORDER BY `id_billing` DESC LIMIT 1");  
			if($result_cName= mysqli_fetch_assoc($query_cName)){
				$mysqli->query("BEGIN;");
				$hotTableQuery = $mysqli->query("SELECT * FROM `billing_tables` WHERE `id_billing` = \"".$result_cName["id_billing"]."\"ORDER BY 'sort_order'");
				$hotCellQuery = $mysqli->query("SELECT * FROM `billing_cells` WHERE `id_billing` = \"".$result_cName["id_billing"]."\"ORDER BY 'display_order'");
				$mysqli->query("COMMIT;");
				//create assoc array
				$hotData[0] = array();
				$hotData[1] = array();
				$hotData[2] = array();
				$hotData["result"]="hit";
				while($hotDataRow = mysqli_fetch_assoc($hotTableQuery)){
					array_push($hotData[0], $hotDataRow);
				}
				while($hotDataRow = mysqli_fetch_assoc($hotCellQuery)){
					array_push($hotData[1], $hotDataRow);
				}
				$hotData[2] = $result_cName;
				$mysqli->close();
				echo json_encode($hotData);
			}
			else{
				print("{\"result\":\"none\"}");
			}
		}
		if($_POST["action"]=="INSERT"){
			//check if id already exists in database
			$checkRows = $mysqli->query("SELECT * FROM `billing` WHERE `id_billing` = $id_billing");  
			if($countRows= mysqli_fetch_assoc($checkRows)){
				$countRows["command"]="pop";
				print json_encode($countRows);
			}
			else{
				//MySqli INSERT Query
				$insert_row = $mysqli->query("INSERT INTO `billing`($trimColumns) VALUES ($trimValues)");

				$result["command"] = "new record";
				$result["prev_value"] = "$prev_value";
				print json_encode($result);
			}
			$mysqli->close();
		}
		if($_POST["action"]=="INSERT_hotableData"){

			$mysqli->query("INSERT INTO `billing_tables`($table_columns) VALUES $table_values");
			$mysqli->query("INSERT INTO `billing_cells`($cells_columns) VALUES $cells_values");
			//if (!$mysqli->query("INSERT INTO `billing_cells`($cells_columns) VALUES $cells_values")) {
			//	printf("Errormessage: %s\n", $mysqli->error);
			//}
			//$mysqli->close();
			
			//$query  = "INSERT INTO `billing_tables`($table_columns) VALUES $table_values;";
			//$query .= "INSERT INTO `billing_cells`($cells_columns) VALUES $cells_values";

			/* execute multi query */
			//if ($result=$mysqli->multi_query($query)) {
		
				//printf("{\"result\":\"%s\"}", $result);
				//$mysqli->next_result());
			//} else {
				//printf("{\"error\":\"%s\"}", "$mysqli->error");
			//}
			
			/* close connection */
			$mysqli->close();

		}
		
		if($_POST["action"]=="UPDATE"){
			//MySqli INSERT Query
			//echo "INSERT INTO `kkdb`.`billing`($trimColumns) VALUES ($trimValues)";
			$idBillingVar = $_POST['id_billing'];
			$idColumnVar = str_replace("`id_billing`","",$trimColumns);
			$idColumnVar = trim($idColumnVar, ",");
			$idValuesVar = str_replace("'$idBillingVar'","",$trimValues);
			$idValuesVar = trim($idValuesVar, ",");
			//print "UPDATE `kkdb`.`billing` SET $idColumnVar = $idValuesVar WHERE `billing`.`id_billing` = $idBillingVar";
			
			$insert_row = $mysqli->query("UPDATE `billing` SET $idColumnVar = $idValuesVar WHERE `billing`.`id_billing` = $idBillingVar");
			
		}
		if ($_POST["action"]=="update_billing_tables"){
			$mysqli->query("UPDATE `billing_tables` SET total='$total' WHERE billing_tables.id_billing = '$id_billing' and billing_tables.sort_order = '$sort_order'");
			$mysqli->close();
		}
		if ($_POST["action"]=="update_hot_tb"){
			//check if cell already exists in database
			$checkRows = $mysqli->query("SELECT * FROM `billing_cells` WHERE id_billing = '$id_billing' and display_order = '$display_order' and row_num = $row_num and col_num = $col_num");
			$tbArray = "";
			if($countRows= mysqli_fetch_assoc($checkRows)){
				//$countRows["command"]="updateCell";
				$insert_row = $mysqli->query("UPDATE `billing_cells` SET content='$content' WHERE billing_cells.id_billing = '$id_billing' and billing_cells.display_order = '$display_order' and billing_cells.col_num = '$col_num' and billing_cells.row_num = '$row_num'");	
			}
			
			else{
				//MySqli INSERT Query
				$cellArray = "";
				$tbArray = "";
				$checkCols = $mysqli->query("SELECT * FROM `billing_cells` LIMIT 1");
				//$checkCols = $mysqli->query("SELECT billing_cells.*, billing_tables.* FROM billing_cells INNER JOIN billing_tables ON billing_cells.id_billing=billing_tables.id_billing GROUP BY billing_tables.id_billing LIMIT 1");
				$colsInfo = $checkCols->fetch_fields();
				foreach ($colsInfo as $val){
					$cellArray.= $val->name . ", ";
				}
				$check_tables = $mysqli->query("SELECT * FROM `billing_tables` LIMIT 1");
				$tablesInfo = $check_tables->fetch_fields();
				foreach ($tablesInfo as $val){
					if(strcmp(($val->name),"id")!==0){
						$tbArray.= $val->name . ", ";
					}
				}
				$tbArray = rtrim($tbArray, ", ");
				$cellArray = rtrim($cellArray, ", ");
				$mysqli->query("BEGIN;");
				$insert_row = $mysqli->query("INSERT INTO `billing_cells` ($cellArray) VALUES ($id,'$id_billing','$display_order', '$row_num', '$col_num', '$content_type', '$content');");
				$insert_table = $mysqli->query("INSERT IGNORE INTO `billing_tables` ($tbArray) VALUES ('$id_billing','$display_order', '$col_count');");
				$mysqli->query("COMMIT;");
				$tbArray = rtrim($tbArray, ", ");

			}
			$hotData = array();
			$hotCellQuery = $mysqli->query("SELECT * FROM `billing_cells` WHERE `id_billing` = $id_billing ORDER BY 'display_order'");
			while($hotDataRow = mysqli_fetch_assoc($hotCellQuery)){
					array_push($hotData, $hotDataRow);
			}
			echo json_encode($hotData);
			
		}
		
		if ($_POST["action"]=="update_cell_type"){
			$update_celltype = $mysqli->query("UPDATE `billing_cells` SET content_type='$content_type' WHERE billing_cells.id_billing = '$id_billing' and billing_cells.display_order = '$display_order' and billing_cells.col_num = '$col_num'");
			print "{\"command\":\"pep\" , \"commands\":\"$col_num\"}";
		}
		
		if ($_POST["action"]=="add_new_table"){
			//$insert_hoTable = $mysqli->query("UPDATE `billing_cells` SET content_type='$content_type' WHERE billing_cells.id_billing = '$id_billing' and billing_cells.display_order = '$display_order' and billing_cells.col_num = '$col_num'");
			$i = 0;
			$mysqli->query("BEGIN;");
			$insert_table = $mysqli->query("INSERT IGNORE INTO `billing_tables` (id_billing, sort_order, col_count) VALUES ('$id_billing','$sort_order', '$col_count');");
			while ($i < $col_count){
				$insert_row = $mysqli->query("INSERT INTO `billing_cells` (id_billing, display_order, row_num, col_num, content_type, content) VALUES ('$id_billing','$display_order', '0', '$i', '', '');");
				$i++;
			};
			$mysqli->query("COMMIT;");
			
			print "{\"command\":\"pep\" , \"commands\":\"$display_order\"}";
		}
		if ($_POST["action"]=="query_hoTable"){
				$mysqli->query("BEGIN;");
				$hotTableQuery = $mysqli->query("SELECT * FROM `billing_tables` WHERE `id_billing` = $id_billing ORDER BY 'sort_order'");
				$hotCellQuery = $mysqli->query("SELECT * FROM `billing_cells` WHERE `id_billing` = $id_billing ORDER BY `billing_cells`.`display_order` ASC, `billing_cells`.`row_num` ASC, `billing_cells`.`col_num` ASC");
				$mysqli->query("COMMIT;");
				//create assoc array
				$hotData[0] = array();
				$hotData[1] = array();
				while($hotDataRow = mysqli_fetch_assoc($hotTableQuery)){
					array_push($hotData[0], $hotDataRow);
				}
				while($hotDataRow = mysqli_fetch_assoc($hotCellQuery)){
					array_push($hotData[1], $hotDataRow);
				}
				echo json_encode($hotData);
				
		}
		if ($_POST["action"]=="query_custName"){
		
				$custNames_query = $mysqli->query("SELECT DISTINCT `customer_name` FROM billing");
				$custNames = array();
				while($custNameRow = mysqli_fetch_assoc($custNames_query)){
					array_push($custNames,$custNameRow["customer_name"]);
				}
				echo json_encode($custNames);
		}
		if ($_POST["action"]=="delete_hoTable"){
				$mysqli->query("BEGIN;");
				$mysqli->query("SET @count = 0;");
				$hotTableDel = $mysqli->query("DELETE FROM `billing_tables` WHERE `id_billing` = $id_billing AND `sort_order` = $display_order");
				$hotCellDel = $mysqli->query("DELETE FROM `billing_cells` WHERE `id_billing` = $id_billing AND `display_order` = $display_order");
				$mysqli->query("UPDATE `billing_tables` SET billing_tables.sort_order = (@count:=@count + 1) WHERE billing_tables.id_billing = $id_billing" );
				$mysqli->query("UPDATE `billing_cells` SET billing_cells.display_order = billing_cells.display_order - 1 WHERE billing_cells.id_billing = $id_billing AND billing_cells.display_order > $display_order" );
				$hotTableQuery = $mysqli->query("SELECT * FROM `billing_tables` WHERE `id_billing` = $id_billing ORDER BY 'sort_order'");
				$hotCellQuery = $mysqli->query("SELECT * FROM `billing_cells` WHERE `id_billing` = $id_billing ORDER BY 'display_order'");
				$mysqli->query("COMMIT;");
				//create assoc array
				$hotData[0] = array();
				$hotData[1] = array();
				while($hotDataRow = mysqli_fetch_assoc($hotTableQuery)){
					array_push($hotData[0], $hotDataRow);
				}
				while($hotDataRow = mysqli_fetch_assoc($hotCellQuery)){
					array_push($hotData[1], $hotDataRow);
				}
				echo json_encode($hotData);
				
		}
		
		if ($_POST["action"]=="delete_hoTcol"){
				$mysqli->query("BEGIN;");
				$mysqli->query("SET @count = -1;");
				$hotCellDel = $mysqli->query("DELETE FROM `billing_cells` WHERE `id_billing` = $id_billing AND `display_order` = $display_order AND `col_num` BETWEEN $start_cols AND $end_cols");
				//$mysqli->query("UPDATE `billing_tables` SET billing_tables.sort_order = (@count:=@count + 1) WHERE billing_tables.id_billing = $id_billing" );
				$mysqli->query("UPDATE `billing_cells` SET billing_cells.col_num = (@count:= @count + 1) WHERE billing_cells.id_billing = $id_billing AND billing_cells.display_order = $display_order" );
				$hotTableQuery = $mysqli->query("SELECT * FROM `billing_tables` WHERE `id_billing` = $id_billing ORDER BY 'sort_order'");
				$hotCellQuery = $mysqli->query("SELECT * FROM `billing_cells` WHERE `id_billing` = $id_billing ORDER BY 'display_order'");
				$mysqli->query("COMMIT;");
				//create assoc array
				$hotData[0] = array();
				$hotData[1] = array();
				while($hotDataRow = mysqli_fetch_assoc($hotTableQuery)){
					array_push($hotData[0], $hotDataRow);
				}
				while($hotDataRow = mysqli_fetch_assoc($hotCellQuery)){
					array_push($hotData[1], $hotDataRow);
				}
				echo json_encode($hotData);
				
		}
		if ($_POST["action"]=="delete_hoTrow"){
				$mysqli->query("BEGIN;");
				$mysqli->query("SET @count = -1;");
				$hotCellDel = $mysqli->query("DELETE FROM `billing_cells` WHERE `id_billing` = $id_billing AND `display_order` = $display_order AND `row_num` BETWEEN $start_rows AND $end_rows");
				//$mysqli->query("UPDATE `billing_tables` SET billing_tables.sort_order = (@count:=@count + 1) WHERE billing_tables.id_billing = $id_billing" );
				//$mysqli->query("UPDATE `billing_cells` SET billing_cells.row_num = billing_cells.row_num - ($end_rows - $start_rows) WHERE billing_cells.id_billing = $id_billing AND billing_cells.display_order = $display_order AND `row_num` => $end_rows" );
				$hotTableQuery = $mysqli->query("SELECT * FROM `billing_tables` WHERE `id_billing` = $id_billing ORDER BY 'sort_order'");
				$hotCellQuery = $mysqli->query("SELECT * FROM `billing_cells` WHERE `id_billing` = $id_billing ORDER BY 'display_order'");
				$mysqli->query("COMMIT;");
				//create assoc array
				$hotData[0] = array();
				$hotData[1] = array();
				while($hotDataRow = mysqli_fetch_assoc($hotTableQuery)){
					array_push($hotData[0], $hotDataRow);
				}
				while($hotDataRow = mysqli_fetch_assoc($hotCellQuery)){
					array_push($hotData[1], $hotDataRow);
				}
				echo json_encode($hotData);
				
		}

	
?>