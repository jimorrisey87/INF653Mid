<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization.X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog quote object
    $quo = new Quote($db);

    //create author and category object
    $aut = new Author($db);
    $cat = new Category($db);

    //Get the raw posted data
    $data = json_decode(file_get_contents("php://input"));

    //if data is not all set, send error message and exit
    if ( !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id))
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }
    
    //Set Data
    $quo->quote = $data->quote;
    $quo->author_id = $data->author_id;
    $quo->category_id = $data->category_id;
    
    $aut->id = $data->author_id;
    $cat->id = $data->category_id;



    //Create post but checking author and category
    //Check category
    $cat->read_single();
    if(!$cat->category){
        echo json_encode(array('message' => 'category_id Not Found'));
        exit ();
    }
    //check author
    $aut->read_single();
    if(!$aut->author){
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }
    
    //create quote
    if($quo->create()){
        echo json_encode(
            array(
                'id' => $quo->id,
                'quote' => $quo->quote,
                'author_id' => $quo->author_id,
                'category_id' => $quo->category_id
        ));
    } else {
        echo json_encode(array('message' => 'No Quotes Found'));
    }