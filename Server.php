<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="margin-middle: 0px;">Über uns</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php
// Standard-Serverkapazitäten
$serverCapacities = [
    1 => ['Cores' => 4, 'RAM' => 32768, 'SSD' => 4000],   // Small Server
    2 => ['Cores' => 8, 'RAM' => 65536, 'SSD' => 8000],    // Medium Server
    3 => ['Cores' => 16, 'RAM' => 131072, 'SSD' => 16000]  // Big Server
];

// Funktion zum Laden der Bestellungen aus der Datei
function loadServerOrders($filePath) {
    $orders = [];
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if (count($parts) === 5) {
                $orders[] = [
                    'vmName' => $parts[0],
                    'server' => (int)$parts[1],
                    'cpu' => (int)$parts[2],
                    'ram' => (int)$parts[3],
                    'ssd' => (int)$parts[4]
                ];
            }
        }
    }
    return $orders;
}

// Funktion zum Speichern der Bestellungen in der Datei
function saveServerOrders($filePath, $orders) {
    $lines = [];
    foreach ($orders as $order) {
        $lines[] = "{$order['vmName']}|{$order['server']}|{$order['cpu']}|{$order['ram']}|{$order['ssd']}";
    }
    file_put_contents($filePath, implode("\n", $lines));
}

// Bestellung verarbeiten
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedCpu = intval($_POST['cpu']);
    $selectedRam = intval($_POST['ram']);
    $selectedSsd = intval($_POST['ssd']);
    $customerName = $_POST['KundeName'];

    // VM-Name wird hier aus dem Formular übergeben
    $vmName = $_POST['vmName']; // VM Name von Benutzer
    
    // Bestellungen laden
    $orders = loadServerOrders('Server.txt');
    
    // Prüfen, ob genug Ressourcen auf dem kleinen Server verfügbar sind
    $allocated = false;
    foreach ([1, 2, 3] as $serverType) {  // Zuerst Small, dann Medium, dann Big
        // Ressourcen des Servers
        $server = $serverCapacities[$serverType];
        
        // Berechne die verbleibenden Ressourcen
        $usedCpu = 0;
        $usedRam = 0;
        $usedSsd = 0;
        
        foreach ($orders as $order) {
            if ($order['server'] === $serverType) {
                $usedCpu += $order['cpu'];
                $usedRam += $order['ram'];
                $usedSsd += $order['ssd'];
            }
        }

        // Überprüfen, ob der Server noch genügend Ressourcen hat
        if ($server['Cores'] - $usedCpu >= $selectedCpu &&
            $server['RAM'] - $usedRam >= $selectedRam &&
            $server['SSD'] - $usedSsd >= $selectedSsd) {
            
            // Bestellung für diesen Servertyp
            $orders[] = [
                'vmName' => $vmName, // VM Name
                'server' => $serverType,
                'cpu' => $selectedCpu,
                'ram' => $selectedRam,
                'ssd' => $selectedSsd
            ];
            
            $allocated = true;
            break; // Bestellung erfolgreich, keine weiteren Servertypen nötig
        }
    }

    // Ergebnis
    if ($allocated) {
        // Bestellung speichern
        saveServerOrders('Server.txt', $orders);
        echo "Die Bestellung für VM '$vmName' wurde erfolgreich auf einem Server platziert.";
    } else {
        echo "Leider sind nicht genügend Ressourcen verfügbar.";
    }
}
?>
</body>
</html>