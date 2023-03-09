<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog quote object
    $quo = new Quote($db);

    //GET ID
    $quo->id = isset($_GET['id']) ? $_GET['id'] : die();// gets the value of that id

    //GET quote
    $quo->read_single();

    //create array
    $quote_arr = array(
        'id' => $quo->id,
        'quote' => $quo->quote,
        'author' => $quo->author,
        'category' => $quo->category

    );

    //Make JSON
    print_r(json_encode($quote_arr));