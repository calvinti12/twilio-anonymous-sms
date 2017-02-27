<?php

// start the session.
session_start();

// Load Twilio PHP Helper Library
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client; 

// Set Account SID and AuthToken.
$sid = $_ENV['ACCOUNT_SID'];
$token = $_ENV['AUTH_TOKEN'];
$client = new Client($sid, $token);

// Set your personal mobile number here in E.164 format.
$ownerCell = $_ENV['OWNER_CELL'];

// Set your Twilio number here in E.164 format.
$twilioNumber = $_ENV['TWILIO_NUMBER'];



// Send message to owner cell.
if (isset($_REQUEST['From']) && $_REQUEST['From'] != $ownerCell) {
    
    // If first contact by customer, set customer cookie.
    if (!isset($_SESSION['customer'])) {
        $_SESSION['customer'] = 'go';
        $body = "From: " . $_REQUEST['From'] . "\n" . "Message: " . $_REQUEST['Body'] . "\n" .  "Instructions: Include the full number above in the body of your reply to start a conversation with this person.";
    } else {
        $body = "From: " . $_REQUEST['From'] . "\n" . $_REQUEST['Body'];
    }
    
    // Send SMS from customer to owner.
    $message = $client->messages->create(
        $ownerCell, array(
            'from' => $twilioNumber,
            'body' => $body,
        )
    );
}

// Send message to customer cell.`
if ($_REQUEST['From'] == $ownerCell) {
    
    // Look for customer cell number and pull it out of message body.
    $re = '/\+?[1-9]\d{1,14}/';
    preg_match_all($re, $_REQUEST['Body'], $phone);
    
    // If a number is found assign it to a variable.
    if (!empty($phone)) {
        $customerCell = $phone[0][0];
    }
    
    // If $customerCell has a value, reset cookie to that value.
    if (isset($customerCell)) {
        
        // Assign customer cell value to session cookie.
        $_SESSION['customerCell'] = $customerCell;

        // Let owner know they are now in an SMS conversation with a specific number.
        $message = $client->messages->create(
            $ownerCell, 
            array(
                'from' => $twilioNumber,
                'body' => "You are now in an SMS conversaton with $customerCell." . "\n" . "You no longer have to include their number in the body of your messages.",
            )
        );
    }

    // If cookie expired and no number is included in message body, send this message.
    if (!isset($_SESSION['customerCell']) && !isset($customerCell)) {
        $message = $client->messages->create(
            $ownerCell, array(
                'from' => $twilioNumber,
                'body' => "I do not know who to send your message to. Please specify a phone number in your message body using this format +1XXXYYYZZZZ.",
            )
        );
    }

    // Remove phone number from body of first message back to customer.
    $body = str_replace($_SESSION['customerCell'], "", $_REQUEST['Body']);
    
    // Send message to customer cell if session cokie is set.
    if (isset($_SESSION['customerCell'])){
        $message = $client->messages->create(
            $_SESSION['customerCell'], array(
                'from' => $twilioNumber,
                'body' => $body,
        ));
    }
}