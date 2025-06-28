<!DOCTYPE html>
<html lang="de">
<style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f4faff;
    margin: 0;
    padding: 0;
    }

    header {
        background-color: #487faf;
        color: white;
        padding: 10px 0;
        text-align: center;
        margin-bottom: 500px; /* Abstand zwischen Header und Boxen */
    }

    .micha {
        color: white;
    }

    .logo {
        font-size: 32px;
        font-weight: bold;
    }

    .navMenu a {
        margin: 0 15px;
        text-decoration: none;
        color: white;
        font-weight: bold;
    }

    .navMenu a:hover {
        text-decoration: underline;
    }

    /* Container für die Serverboxen */
    .server-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 30px; /* Hier wird der Abstand zwischen Header und Boxen hinzugefügt */
        padding-top: 20px; /* Optional, für etwas mehr Abstand */
    }

    .server {
        background-color: #e0f7fa; /* Sehr helles Blau */
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        width: 30%;
        text-align: center;
        margin: 10px;
        transition: transform 0.3s ease;
    }

    .server:hover {
        transform: scale(1.05); /* Leichter Hover-Effekt */
    }

    h1 {
        text-align: center;
        color: #487faf;
        font-size: 36px;
    }

    h2 {
        color: #487faf;
        font-size: 24px;
    }

    p {
        font-size: 18px;
        color: #333;
    }

    /* Footer Styling */
    footer {
        background-color: #487faf;
        color: white;
        padding: 20px;
        text-align: center;
    }

    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VM Angebote</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<br>
<header class="header">
    <a href="index.php" class="logo">OmniCloud</a>
    <nav class="navMenu">
        <a href="index.php">Home</a>
        <a href="Angebot.php">Angebote</a>
        <a href="UeberUns.php">Über uns</a>
        <a href="BenutzerDelete.php">Angebot Aufheben</a>
    </nav>
</header>
<br><br><br>
<?php
// Standard Serverkapazitäten
$serverCapacities = [
    1 => ['Cores' => 4, 'RAM' => 32768, 'SSD' => 4000],   // Small Server
    2 => ['Cores' => 8, 'RAM' => 65536, 'SSD' => 8000],    // Medium Server
    3 => ['Cores' => 16, 'RAM' => 131072, 'SSD' => 16000]  // Big Server
];

// Funktion zum Laden der abgezogenen Kapazitäten aus der "Server.txt" Datei
function loadServerCapacities($filePath) {
    global $serverCapacities;
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $data = explode("|", $line);
            $serverType = (int)$data[1];  // Servertyp (1 = Small, 2 = Medium, 3 = Big)
            $cpu = (int)$data[2];  // CPU
            $ram = (int)$data[3];  // RAM
            $ssd = (int)$data[4];  // SSD
            $serverCapacities[$serverType]['Cores'] -= $cpu; // Abziehen der CPU
            $serverCapacities[$serverType]['RAM'] -= $ram;   // Abziehen des RAM
            $serverCapacities[$serverType]['SSD'] -= $ssd;   // Abziehen des SSD
        }
    }
}

// Lade die abgezogenen Kapazitäten aus der "Server.txt" Datei
loadServerCapacities("Server.txt");
?>

<div class="server-container">
    <div class="server">
        <h2>Small Server</h2>
        <p>CPU: <?php echo $serverCapacities[1]['Cores']; ?> Cores</p>
        <p>RAM: <?php echo $serverCapacities[1]['RAM']; ?> MB (ca. <?php echo $serverCapacities[1]['RAM'] / 1024; ?> GB)</p>
        <p>SSD: <?php echo $serverCapacities[1]['SSD']; ?> GB</p>
    </div>
    
    <div class="server">
        <h2>Medium Server</h2>
        <p>CPU: <?php echo $serverCapacities[2]['Cores']; ?> Cores</p>
        <p>RAM: <?php echo $serverCapacities[2]['RAM']; ?> MB (ca. <?php echo $serverCapacities[2]['RAM'] / 1024; ?> GB)</p>
        <p>SSD: <?php echo $serverCapacities[2]['SSD']; ?> GB</p>
    </div>
    
    <div class="server">
        <h2>Big Server</h2>
        <p>CPU: <?php echo $serverCapacities[3]['Cores']; ?> Cores</p>
        <p>RAM: <?php echo $serverCapacities[3]['RAM']; ?> MB (ca. <?php echo $serverCapacities[3]['RAM'] / 1024; ?> GB)</p>
        <p>SSD: <?php echo $serverCapacities[3]['SSD']; ?> GB</p>
    </div>
</div>

<footer>
    <div class="footer">
        <div>
            <a href="Angebote.php">Angebote</a>
            <a href="BenutzerDelete.php">Angebote Aufheben</a>
            <a href="UeberUns.php">Über uns</a>
        </div>
        <p class="micha">© 2024 OmniCloud GmbH</p>
    </div>
</footer>

</body>
</html>
