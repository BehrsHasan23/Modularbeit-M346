<!DOCTYPE html>
<html lang="en">
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
<header class="header">
    <a href="index.php" class="logo">OmniCloud</a>
    <nav class="navMenu">
        <a href="index.php">Home</a>
        <a href="Angebot.php">Angebote</a>
        <a href="UeberUns.php">Über uns</a>
        <a href="BenutzerDelete.php">Angebot Aufheben</a>
    </nav>
</header>

<div>
    <h1 class="Angebot">Unser Angebot</h1>
    <p class="AngebotText">Sie haben Interesse an einer virtuellen Maschine? Dann sind Sie hier genau richtig. Wir bieten Ihnen eine grosse Auswahl an verschiedenen Spezifikationen. 
        Der Auswahl ist keine Grenze gesetzt. <br>Melden Sie sich gerne im untenstehenden Formular an!  </p>

    <?php
    $serverfull = "";
    $totalPrice = 0;

    // Deklarierung der Server-Kapazitäten
    $SmallServer = ["Cores" => 4, "Ram" => 32768, "SSD" => 4000];
    $MediumServer = ["Cores" => 8, "Ram" => 65536, "SSD" => 8000];
    $BigServer = ["Cores" => 16, "Ram" => 131072, "SSD" => 16000];

    // Nachricht zur Bestellung
    $orderMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedCpu = intval($_POST['cpu']);
        $selectedRam = intval($_POST['ram']);
        $selectedSsd = intval($_POST['ssd']);
        $KundeName = $_POST["KundeName"];

        // Prüfen, welcher Server Platz hat
        switch (true) {
            case ($selectedCpu <= $SmallServer["Cores"] && $selectedRam <= $SmallServer["Ram"] && $selectedSsd <= $SmallServer["SSD"]):
                // Erfolgreiche Bestellung für Small Server
                $SmallServer["Cores"] -= $selectedCpu;
                $SmallServer["Ram"] -= $selectedRam;
                $SmallServer["SSD"] -= $selectedSsd;
                $orderMessage = "Die Bestellung für VM '$KundeName' wurde erfolgreich auf einem kleinen Server platziert.";
                $serverType = "small";
                break;
            case ($selectedCpu <= $MediumServer["Cores"] && $selectedRam <= $MediumServer["Ram"] && $selectedSsd <= $MediumServer["SSD"]):
                // Erfolgreiche Bestellung für Medium Server
                $MediumServer["Cores"] -= $selectedCpu;
                $MediumServer["Ram"] -= $selectedRam;
                $MediumServer["SSD"] -= $selectedSsd;
                $orderMessage = "Die Bestellung für VM '$KundeName' wurde erfolgreich auf einem mittleren Server platziert.";
                $serverType = "medium";
                break;
            case ($selectedCpu <= $BigServer["Cores"] && $selectedRam <= $BigServer["Ram"] && $selectedSsd <= $BigServer["SSD"]):
                // Erfolgreiche Bestellung für Big Server
                $BigServer["Cores"] -= $selectedCpu;
                $BigServer["Ram"] -= $selectedRam;
                $BigServer["SSD"] -= $selectedSsd;
                $orderMessage = "Die Bestellung für VM '$KundeName' wurde erfolgreich auf einem großen Server platziert.";
                $serverType = "big";
                break;
            default:
                $serverfull = "Kein Server hat genug Kapazität. Bitte wählen Sie kleinere Werte oder kontaktieren Sie uns für ein individuelles Angebot.";
                break;
        }

        // Preisberechnung
        $cpuPrices = ["1" => 5, "2" => 10, "4" => 18, "8" => 30, "16" => 45];
        $ramPrices = ["512" => 5, "1024" => 10, "2048" => 20, "4096" => 40, "8192" => 80, "16384" => 160, "32768" => 320];
        $ssdPrices = ["10" => 5, "20" => 10, "40" => 20, "80" => 40, "240" => 120, "500" => 250, "1000" => 500];

        $totalPrice = $cpuPrices[$selectedCpu] + $ramPrices[$selectedRam] + $ssdPrices[$selectedSsd];

        // Speichern der Bestellinformationen in einer Textdatei
        $orderDetails = "Prozessoren (CPU): $selectedCpu Cores\n";
        $orderDetails .= "Arbeitsspeicher (RAM): $selectedRam MB\n";
        $orderDetails .= "Speicherplatz (SSD): $selectedSsd GB\n";
        $orderDetails .= "Server: $serverType\n";
        $orderDetails .= "Monatlicher Preis: $totalPrice CHF\n";
        $orderDetails .= "Provisionierungsdatum: " . date("Y-m-d H:i:s") . "\n";

        // Definieren des Dateinamens
        $fileName = "Bestellungen.txt";

        // Speichern der Bestellung in der Textdatei
        file_put_contents($fileName, $orderDetails, FILE_APPEND);

    }
    ?>


<!-- Forum zum Bestellen der Server-Spezifikationen -->
<form class="form-class-name" action="server.php" method="post">  
        <label for="KundeName">Geben Sie Ihren Namen ein:</label>
        <input type="text" name="KundeName" placeholder="Ihr Name...">
        
        <label for="vmName">VM Name:</label>
        <input type="text" name="vmName" placeholder="Name der VM..." required>  <!-- VM Name hinzufügen und als Pflichtfeld markieren -->
        
        <label for="cpu">CPU (Cores): </label>
        <select id="cpu" name="cpu">
            <option value="1">1 Core - 5 CHF</option>
            <option value="2">2 Cores - 10 CHF</option>
            <option value="4">4 Cores - 18 CHF</option>
            <option value="8">8 Cores - 30 CHF</option>
            <option value="16">16 Cores - 45 CHF</option>
        </select>
        
        <label for="ram">RAM (MB):</label>
        <select id="ram" name="ram">
            <option value="512">512 MB</option>
            <option value="1024">1024 MB</option>
            <option value="2048">2048 MB</option>
            <option value="4096">4096 MB</option>
            <option value="8192">8192 MB</option>
            <option value="16384">16384 MB</option>
            <option value="32768">32768 MB</option>
        </select>
        
        <label for="ssd">SSD (GB):</label>
        <select id="ssd" name="ssd">
            <option value="10">10 GB</option>
            <option value="20">20 GB</option>
            <option value="40">40 GB</option>
            <option value="80">80 GB</option>
            <option value="240">240 GB</option>
            <option value="500">500 GB</option>
            <option value="1000">1000 GB</option>
        </select>
        
        <input type="submit" value="Bestellen">

    </form>  
</div>

<footer>
    <div class="footer">
        <div>
            <a href="Angebote.php">Angebote</a>
            <a href="BenutzerDelete.php">Angebote Aufheben</a>
            <a href="Serverkapazitaeten.php">Server</a>
            <a href="UeberUns.php">Über uns</a>
        </div>
        <p>© 2023 OmniCloud GmbH</p>
    </div>
</footer>
</body>
</html>