<?php
require 'vendor/autoload.php';
use Mailgun\Mailgun;

$api_key = 'sample'; 
$domain = 'sample';

// Instantiate the client using the create method
$mgClient = Mailgun::create($api_key);

try {
    $result = $mgClient->messages()->send($domain, [
        'from'    => 'vaishnavi <mailgun@sandbox010a0d55e24c49f58aae811539071523.mailgun.org>',
        'to'      => 'vaishnavi <vaishnavigupta1423@gmail.com>',
        'subject' => 'Hello',
        'text'    => 'Testing some Mailgun awesomeness!',
    ]);

    echo "Email sent successfully!";
} catch (\Exception $e) {
    echo "Error sending email: " . $e->getMessage();
}
?>
Send.php:
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mailgun Email Sender</title>
</head>
<body>
    <h2>Send an Email</h2>
     <a href="test.php"><button>Click Me</button></a>
</body>
</html>
