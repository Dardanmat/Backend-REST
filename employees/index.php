<?php
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin: *');
    require "connessione.php";

    $size = 20;
    $page = 0;
    setQueryStrValues();
    $start = $page * $size;
    $myQuery = "SELECT * FROM employees ORDER BY employees.id ASC LIMIT $start,$size";
    $method = $_SERVER["REQUEST_METHOD"];

    
    switch($method){
        case 'GET':
            get();
            break;

        case 'POST':
            post();
            break;

        case 'PUT':
            put();
            break;

        case 'DELETE':
            delete();
            break;

        default:
            break;

    }

    function setQueryStrValues(){
        global $size, $page;
        $qstr = array();
        parse_str($_SERVER['QUERY_STRING'], $qstr);
        if(isset($qstr["size"])) $size = $qstr["size"];
        if(isset($qstr["page"])) $page = $qstr["page"];
    }

    function getRecordCount(){
        require "connessione.php";
        $q = "SELECT COUNT(*) AS tot FROM employees";

        if($result = $database->query($q)){
            while($row = $result -> fetch_assoc()){
                
                return intval($row["tot"]);
            }
        }
    }

    function get(){
        require "connessione.php";
        global $size, $page, $myQuery;

        $firstPage = "http://localhost:9000/employees?page=".$page."&size=".$size;
        
        
        $totalElements = getRecordCount();
        $totPages = ceil($totalElements/$size);

        $tmp = $totPages -1;
        $lastPage = $firstPage . "?page=" . $tmp . "&size=" . $size;

        $array = array(
            "_embedded" => array(
                "employees" => array(
                )
            ),
            "_links" => array(
                "first" => array("href" => $firstPage),
                "last" => array("href" => $lastPage)
                //"next" => array("href");
                //"prev" => array("href");
            ),
            "page" => array(
                "number" => $page,
                "size" => $size,
                "totalElements" => $totalElements,
                "totalPages" => $totPages
            )
        );
        
        if($result = $database->query($myQuery)){
            $i = 0;
            while($row = $result -> fetch_assoc()){
                
                $array["_embedded"]["employees"][$i]["id"] = $row["id"];
                $array["_embedded"]["employees"][$i]["first_name"] = $row["first_name"];
                $array["_embedded"]["employees"][$i]["last_name"] = $row["last_name"];
                $array["_embedded"]["employees"][$i]["gender"] = $row["gender"];
                $array["_embedded"]["employees"][$i]["birth_date"] = $row["birth_date"];
                $array["_embedded"]["employees"][$i]["hire_date"] = $row["hire_date"];
                $i++;
            }
            echo json_encode($array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
    }

?>