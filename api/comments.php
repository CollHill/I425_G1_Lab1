<?php
$url = $_SERVER['REQUEST_URI'];

// checking if slash is first character in route otherwise add it
if(strpos($url, "/") !== 0) {
    $url = "/$url";
}

//Connect to the database.
$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);

////////////////////////////////////////
//                                    //
//      Lab 1 code starts here        //
//                                    //
////////////////////////////////////////

// list one comment (GET)
//        /comments/{id}
//Get a single post
if(preg_match("/comments\/([0-9])*/", $url, $matches) &&  $_SERVER['REQUEST_METHOD']
    == 'GET'){
    $commentId = $matches[1];
    $comment = getComment($dbConn, $commentId);
    echo json_encode($comment);
}

/**
 * Get Comment based on ID
 *
 * @param $db
 * @param $id
 *
 * @return Associative Array
 */
function getComment($db, $id) {
    $statement = "SELECT * FROM comments where id = " . $id;
    $result = $db->query($statement);
    $result_row = $result->fetch_assoc();
    return $result_row;
}

//update part of a comment (PATCH)
//      /comments/{id}?query="string"

//Update a post
if(preg_match("/comments\/([0-9])+/", $url, $matches) && $_SERVER['REQUEST_METHOD']
    == 'PATCH'){
    $input = $_GET;
    $commentId = $matches[1];
    echo $url;
    print_r($matches);
    updateComment($input, $dbConn, $commentId);
    $comment = getComment($dbConn, $commentId);
    echo json_encode($comment);
}
/**
 * Update Post
 *
 * @param $input
 * @param $db
 * @param $commentId
 * @return integer
 */
function updateComment($input, $db, $commentId){
    $fields = getParams($input);

    $statement = "UPDATE comments
                    SET $fields
                    WHERE id = " . $commentId;

    $db->query($statement);
    return $commentId;
}

/**
 * Get fields as parameters to set in record
 *
 * @param $input
 * @return string
 */
function getParams($input) {
    $allowedFields = ['comment', 'post_id', 'user_id'];
    $filterParams = [];
    foreach($input as $param => $value){
        if(in_array($param, $allowedFields)){
            $filterParams[] = "$param='$value'";
        }
    }
    return implode(", ", $filterParams);
}

// delete a comment (DELETE)
//Delete a post
if(preg_match("/comments\/([0-9])+/", $url, $matches) && $_SERVER['REQUEST_METHOD']
    == 'DELETE'){
    $commentId = $matches[1];
    deleteComment($dbConn, $commentId);

    echo json_encode([
        'id'=> $commentId,
        'deleted'=> 'true'
    ]);
}

/**
 * Delete Post record based on ID
 *
 * @param $db
 * @param $id
 */
function deleteComment($db, $id) {
    $statement = "DELETE FROM comments where id = " . $id;
    $db->query($statement);
}