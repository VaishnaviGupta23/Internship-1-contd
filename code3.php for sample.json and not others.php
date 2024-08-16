<?php
$host = 'localhost';
$username = 'root';
$password = 'Pass@12345';
$database = 'smalldb';

$listFilename = $argv[1];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);

    $createTableSQL = "CREATE TABLE IF NOT EXISTS provider_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ein VARCHAR(9) NOT NULL,
        npi VARCHAR(10) NOT NULL,
        UNIQUE(ein, npi)
    )";
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

        while (!feof($file)) {
            $chunk = fread($file, 1024 * 1024);
            if ($chunk === false) {
                die("Error reading JSON file: $filename");
            }
            $whitespaces = array(" ","\r\n","\n","\r","\t","-"); 
            $chunk = str_replace($whitespaces,"",$chunk);
            $buffer .= $chunk;
        }

        fclose($file);

        $data = json_decode($buffer, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error decoding JSON data: " . json_last_error_msg());
        }

        if (isset($data['in_network'])) {
            foreach ($data['in_network'] as $inNetwork) {
                if (isset($inNetwork['negotiated_rates'])) {
                    foreach ($inNetwork['negotiated_rates'] as $negotiatedRate) {
                        if (isset($negotiatedRate['provider_groups'])) {
                            foreach ($negotiatedRate['provider_groups'] as $providerGroup) {
                                if (isset($providerGroup['tin']) && isset($providerGroup['npi'])) {
                                    $ein = trim($providerGroup['tin']['value']);
                                    $npiList = isset($providerGroup['npi']) ? $providerGroup['npi'] : [];

                                    if (!empty($ein)) {
                                        $ein = str_pad(preg_replace('/\D/', '', $ein), 9, '0', STR_PAD_LEFT);
                                    }

                                    if (!empty($ein) && !empty($npiList)) {
                                        foreach ($npiList as $npi) {
                                            $npi = trim($npi);

                                            if (!empty($npi)) {
                                                $insertSQL = "INSERT IGNORE INTO provider_data (ein, npi) VALUES (:ein, :npi)";
                                                $stmt = $pdo->prepare($insertSQL);
                                                $stmt->bindParam(':ein', $ein);
                                                $stmt->bindParam(':npi', $npi);
                                                $stmt->execute();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} catch (PDOException $e) {
    die('Database connection error: ' . $e->getMessage());
}
?>
