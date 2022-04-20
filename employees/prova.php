 
<?php 
    //per i test su xampp:
    // link: localhost:80/Backend%20REST/
    // GET: curl localhost:80/Backend%20REST/
    // POST: curl -X POST -H "Content-Type: application/json" -d "{\"firstName\":\"John\",\"lastName\":\"Smith\",\"gender\":\"M\"}" localhost:80/Backend%20REST/
    // PUT: curl -X PUT -H "Content-Type: application/json" -d "{\"firstName\":\"John\",\"lastName\":\"Smith\",\"gender\":\"M\"}" localhost:80/Backend%20REST/

    //per i test su docker:
    // link: localhost:8080/mio-sito/
    // GET: curl localhost:8080/mio-sito/
    // POST: curl -X POST -H "Content-Type: application/json" -d "{\"firstName\":\"John\",\"lastName\":\"Smith\",\"gender\":\"M\"}" localhost:8080/mio-sito/
    // PUT: curl -X PUT -H "Content-Type: application/json" -d "{\"firstName\":\"John\",\"lastName\":\"Smith\",\"gender\":\"M\"}" localhost:8080/mio-sito/

    if(!isset($_SESSION["person"])){
        $_SESSION["person"] = '{"firstName":"Johnny","lastName":"Smitty","gender":"M"}';
    }

    $person = json_decode($_SESSION["person"], true);

    $method = $_SERVER["REQUEST_METHOD"];
    $json = file_get_contents("php://input");
    $ar = json_decode($json, TRUE);
    
    
    switch($method){
        case "GET":
            //echo json_encode($person);
            echo $method;
            break;

        case "POST":
            /*$_SESSION["person"] = $ar;
            echo "Nuovo nome: " . $_SESSION["person"]["firstName"];
            echo "\nAggiunto con successo";*/
            echo $method;
            break;

        case "PUT":
            /*$_SESSION["person"] = $ar;
            echo "Nuovo nome: " . $_SESSION["person"]["firstName"];
            echo "\nInserito con successo";*/
            echo $method;
            break;

        case "DELETE":
            /*$_SESSION["person"] = null;
            var_dump($ar);
            echo "\nEliminato con successo";*/
            echo $method;
            break;

        default:
            echo "Errore";
            break;
        
    }*/
    
?>