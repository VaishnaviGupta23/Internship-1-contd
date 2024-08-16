<?php
$host = 'localhost';
$username = 'root';
$password = 'Pass@12345';
$database = 'smalldb';

$listFilename = $argv[1];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);

    $createTableSQL="CREATE TABLE IF NOT EXISTS unique_urls (id INT AUTO_INCREMENT PRIMARY KEY,url TEXT NOT NULL, UNIQUE (url(255)))";
    $pdo->exec($createTableSQL);

    $fileList = file($listFilename, FILE_IGNORE_NEW_LINES);
    if ($fileList === false) {
        die("Error reading $listFilename");
    }

    foreach ($fileList as $filename) {
        $file = fopen($filename, 'r');
        if ($file === false) {
            die("Error opening JSON file: $filename");
        }

        $buffer = '';
        $insideInNetworkFiles = false;
        $uniqueUrls = [];
        $incompleteInNetworkFiles = '';

        while (!feof($file)) {
            $chunk = fread($file, 1024 * 1024); 
            if ($chunk === false) {
                die("Error reading JSON file: $filename");
            }
            $buffer .= $chunk;

            while (strpos($buffer, 'in_network_files') !== false) {
                $insideInNetworkFiles = true;
                $startPosition = strpos($buffer, 'in_network_files');

                $closingBracketPosition = strpos($buffer, ']', $startPosition);

                if ($closingBracketPosition !== false) {
                    $section = substr($buffer, $startPosition, $closingBracketPosition - $startPosition + 1);

                    $matches = [];
                    preg_match_all('/"location"\s*:\s*"(.*?)"/', $section, $matches);
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $url) {
                            if (!in_array($url, array_column($uniqueUrls, 'url'))) {
                                $insertSQL = "INSERT INTO unique_urls (url) VALUES (:url)";
                                $stmt = $pdo->prepare($insertSQL);
                                $stmt->bindParam(':url', $url);
                                $stmt->execute();

                                $id = $pdo->lastInsertId();

                                $uniqueUrls[] = ['id' => $id, 'url' => $url];
                            }
                        }
                    }

                    $buffer = substr($buffer, $closingBracketPosition + 1);
                    $insideInNetworkFiles = false;
                } else {
                    $incompleteInNetworkFiles = substr($buffer, $startPosition);
                    $buffer = '';
                    $insideInNetworkFiles = false;
                }
            }
        }

        fclose($file);
    }
} catch (PDOException $e) {
    die('Database connection error: ' . $e->getMessage());
}
?>
