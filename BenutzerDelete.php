<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angebot Aufheben</title>
    <link rel="stylesheet" href="style.css">
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

<?php
    // Wenn das Formular abgesendet wird
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $KundeDelte = trim($_POST["Delete"]); // Name der VM, die gelöscht werden soll
        
        // Die Serverkapazitäten (werden beim Löschen wiederhergestellt)
        $serverCapacities = [
            1 => ['Cores' => 4, 'RAM' => 32768, 'SSD' => 4000],   // Small Server
            2 => ['Cores' => 8, 'RAM' => 65536, 'SSD' => 8000],    // Medium Server
            3 => ['Cores' => 16, 'RAM' => 131072, 'SSD' => 16000]  // Big Server
        ];
        
        // Kunden- und Verkaufsdateien auslesen und in Arrays umwandeln
        $array = file("server.txt", FILE_IGNORE_NEW_LINES);
        
        // Kapazitäten, die abgezogen wurden
        $removedCpu = $removedRam = $removedSsd = 0;
        $deleted = false;
        
        // Lösche die VM-Daten aus der `server.txt`-Datei und stelle die Serverkapazität wieder her
        foreach ($array as $key => $line) {
            // Durchsplitten der Zeile (nach dem '|' Trennzeichen)
            $data = explode("|", $line);
            $vmName = $data[0];    // Der Name der VM
            $serverType = (int)$data[1];  // Servertyp (1 = Small, 2 = Medium, 3 = Big)
            $cpu = (int)$data[2];  // CPU-Anzahl
            $ram = (int)$data[3];  // RAM (in MB)
            $ssd = (int)$data[4];  // SSD (in GB)
            
            // Wenn der VM-Name mit dem eingegebenen Namen übereinstimmt
            if ($vmName == $KundeDelte) {
                // Ressourcen des Servers wiederherstellen
                $serverCapacities[$serverType]['Cores'] += $cpu;
                $serverCapacities[$serverType]['RAM'] += $ram;
                $serverCapacities[$serverType]['SSD'] += $ssd;
                
                // Lösche den entsprechenden Eintrag aus dem Array
                unset($array[$key]);
                $deleted = true;
                break; // Wenn der Kunde gefunden wurde, keine weiteren Suchen durchführen
            }
        }

        if ($deleted) {
            // Den aktualisierten Array wieder in die Datei schreiben
            file_put_contents("server.txt", implode(PHP_EOL, $array));
            
            echo "<p>Die VM '$KundeDelte' wurde erfolgreich gelöscht. Die Serverkapazitäten wurden wiederhergestellt.</p>";
        } else {
            echo "<p>Die angegebene VM '$KundeDelte' wurde nicht gefunden.</p>";
        }
    }
?>

<div>
    <div class="abschied">
        <h1>Möchten Sie uns wirklich verlassen?</h1>
        <p>Falls Sie unzufrieden mit unserem Angebot sind, werden wir Ihnen umgehend helfen.</p>
        <p>Schreiben Sie uns eine E-Mail: OmniCloud@kundenservice.com</p>
    </div>
    <!-- Form -->
    <div class="abschiedForm">
        <form action="BenutzerDelete.php" method="post">     
            <label for="Delete">Möchten Sie Ihr Abo beenden?</label>
            <input type="text" value="Name eingeben" id="Delete" name="Delete" required>
            <input type="submit" value="Löschen">
        </form>
    </div>
</div>

<footer> 
    <div class="footer">
        <div>
            <a href="Angebote.php">Angebote</a>
            <a href="BenutzerDelete.php">Angebote Aufheben</a>
            <a href="UeberUns.php">Über uns</a>
        </div>
        <p>© 2023 OmniCloud GmbH</p>
    </div>
</footer>
</body>
</html>
