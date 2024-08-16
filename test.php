<?php
require 'vendor/autoload.php';
use Mailgun\Mailgun;

$api_key = 'b5e02f777d9e4e9e98b4f2b1e5a3f76f-4b98b89f-3410c223'; 
$domain = 'sandbox010a0d55e24c49f58aae811539071523.mailgun.org';

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
