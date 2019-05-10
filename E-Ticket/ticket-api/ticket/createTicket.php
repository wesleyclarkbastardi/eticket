<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

// get database connection
include_once '../config/database.php';
 
// instantiate ticket object
include_once '../objects/ticket.php';
 
$database = new Database();
$db = $database->getConnection();
 
$ticket = new Ticket($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty, skipped referrals and special requests since it isn't required
if(
    !empty($data->firstName) &&
    !empty($data->lastName) &&
    !empty($data->email) &&
    !empty($data->ticketQuantity) &&
    !empty($data->ticketType) &&
    !empty($data->purchaseAgreementSigned)
){
 
    // set ticket property values

    $ticket->firstName = $data->firstName;
    $ticket->lastName = $data->lastName;
    $ticket->email = $data->email;
    $ticket->ticketQuantity = $data->ticketQuantity;
    $ticket->ticketType = $data->ticketType;
    $ticket->referrals = $data->referrals;
    $ticket->specialRequests = $data->specialRequests;
    $ticket->purchaseAgreementSigned = $data->purchaseAgreementSigned;
 
    // create the ticket
    if($ticket->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Ticket was created."));

        //email the user
        function sanitize_email($field) {
            $field = filter_var($field, FILTER_SANITIZE_EMAIL);
            if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                return false;
            }
        }
        
        $to_email = $ticket->email;
        $subject = 'Your recent e-ticket purchase';
        $message = 'Thank you for shopping with us. Your tickets will arrive soon (not really...this was not a real ticket request).';
        $headers = 'From: noreply @ nocompany.com';
        //check if the email address is invalid $secure_check
        $secure_check = sanitize_email($to_email);
        if ($secure_check) {
            mail($to_email, $subject, $message, $headers);
            echo "This email is sent using PHP Mail";
        } 
    }
 
    // if unable to create the ticket, tell the user
    else {
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to save ticket request."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create ticket because the form is incomplete."));
}
?>