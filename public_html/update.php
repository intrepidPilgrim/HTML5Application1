<?phpinclude_once("config.php");	                //query id_billing from billing, limit 50                if($_POST["action"]=="id_billing_query"){                    $mysqli->query("BEGIN");                    $query_id_billing = $mysqli->query("SELECT id_billing FROM `billing` LIMIT 100");                    $query_customers = $mysqli->query("SELECT DISTINCT `customer_name` FROM billing LIMIT 100");                    $mysqli->query("COMMIT");                                        $query_output[0] = array();                    $query_output[1] = array();                                        while($row_id_billing = mysqli_fetch_assoc($query_id_billing)){                        array_push($query_output[0], $row_id_billing["id_billing"]);                    }                    while($row_customers = mysqli_fetch_assoc($query_customers)){                        array_push($query_output[1], $row_customers["customer_name"]);                    }                                        echo(json_encode($query_output));                }elseif($_POST["action"]=="id_billing_record_query"){                    $query_id_billing = $mysqli->query("SELECT * FROM `billing` WHERE id_billing=" . $_POST["id_billing"]);                    $query_output = array();                    $row_id_billing = mysqli_fetch_assoc($query_id_billing);                    echo(json_encode($row_id_billing));                                }elseif($_POST["action"]=="find_last_record"){                    $query_id_billing = $mysqli->query("SELECT id_billing FROM `billing` ORDER BY id_billing DESC LIMIT 1");                    $row_id_billing = mysqli_fetch_assoc($query_id_billing);                    echo(json_encode($row_id_billing));                }elseif($_POST["action"]=="new_record"){                                 }elseif($_POST["action"]=="latest_customer_record"){                    $query_id_billing = $mysqli->query("SELECT * FROM `billing` WHERE customer_name = '". $_POST["customer_name"] ."'ORDER BY id_billing DESC LIMIT 1");                    $row_id_billing = mysqli_fetch_assoc($query_id_billing);                    echo(json_encode($row_id_billing));                }else{                    echo("Invalid Request");                }                ?>