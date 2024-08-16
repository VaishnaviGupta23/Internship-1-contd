<!DOCTYPE html>
<html>
<head>
    <title>Unique URLs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
	body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }    
		</style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4">Unique URLs</h1>

        <?php
        include 'proj2/code2.php';

        if (isset($uniqueUrls) && is_array($uniqueUrls) && count($uniqueUrls) > 0) {
            echo '<table class="table table-bordered">';
            echo '<tbody>';
            foreach ($uniqueUrls as $entry) {
                $id = $entry['id'];
                $url = $entry['url'];
                echo '<tr><td>' . $id . '</td><td><a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($url) . '</a></td></tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No unique URLs found.</p>';
        }
        ?>

    </div>

</body>
</html>
