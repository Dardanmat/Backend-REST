<?php
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin: *');
    require "connessione.php";

    $size = 20;
    $page = 0;
    $requestedID = null;
    setQueryStrValues();
    $start = $page * $size;
    $getQuery = "SELECT * FROM employees ORDER BY employees.id ASC LIMIT $start,$size";
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
            header("HTTP/1.1 400 BAD REQUEST");
            break;

    }

    function post(){
        require "connessione.php";

        $ar = json_decode(file_get_contents('php://input'), true);

        $postQuery = "INSERT INTO employees (first_name, last_name, birth_date, hire_date, gender, id)
        VALUES (". $ar["first_name"] . "," . $ar["last_name"] . "," . $ar["birth_date"] . "," . $ar["hire_date"] . "," . $ar["gender"] . "," . $ar["id"] . ")";
        $result = $mysqli-> query($postQuery);
    }

    function put(){
        require "connessione.php";
        global $requestedID;

        $ar = json_decode(file_get_contents('php://input'), true);

        $putQuery = "  UPDATE employees 
                        SET first_name = '". $ar["first_name"] ."'
                        ,last_name = '". $ar["last_name"] ."'
                        ,gender = '". $ar["gender"] ."'
                        ,hire_date = '". $ar["hire_date"] ."'
                        ,birth_date = '". $ar["birth_date"] ."'
                        WHERE id=$requestedID";
        $result = $mysqli-> query($putQuery);
    }

    function setQueryStrValues(){
        global $size, $page, $requestedID;
        $qstr = array();
        parse_str($_SERVER['QUERY_STRING'], $qstr);
        if(isset($qstr["size"])) $size = $qstr["size"];
        if(isset($qstr["page"])) $page = $qstr["page"];
        if(isset($qstr["id"])) $requestedID = $qstr["id"];
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

    function delete(){
        require "connessione.php";
        global $requestedID;

        $myQuery = "DELETE FROM employees WHERE id=$requestedID";
        $result = $database->query($myQuery);
    }

    function get(){
        require "connessione.php";
        global $size, $page, $getQuery;

        $url = "http://localhost:8080/employees/";
        $firstPage = $url . "?page=".$page."&size=".$size;
        
        $totalElements = getRecordCount();
        $totPages = ceil($totalElements/$size);

        $tmp = $totPages -1;
        $lastPage = $url . "?page=" . $tmp . "&size=" . $size;

        $array = array(
            "_embedded" => array(
                "employees" => array(
                )
            ),
            "_links" => array(
                "first" => array("href" => $firstPage),
                "last" => array("href" => $lastPage)
            ),
            "page" => array(
                "number" => intval($page),
                "size" => intval($size),
                "totalElements" => $totalElements,
                "totalPages" => $totPages
            )
        );

        if($page < $totPages -1){
            $nextPageNumber = $page + 1;
            $nextPage = $url . "?page=" . $nextPageNumber. "&size=" . $size;
            $array["_links"]["next"]["href"] = $nextPage;
        }

        if($page >= 0){
            $prevPageNumber = $page - 1;
            $prevPage = $url . "?page=" . $prevPageNumber. "&size=" . $size;
            $array["_links"]["prev"]["href"] = $prevPage;
        }
        

        
        if($result = $database->query($getQuery)){
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