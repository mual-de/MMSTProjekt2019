<head>
    <title>Gateways</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/w3.css">
    <!-- Icons von FontAwesome -->
    <script src="https://kit.fontawesome.com/c1909a32ba.js" crossorigin="anonymous"></script>
</head>


<body>
    <nav>
        <div class="w3-bar w3-blue">
            <a href="index.html" class="w3-bar-item w3-button w3-mobile">Home</a>
            <a href="showDevices.html" class="w3-bar-item w3-button w3-mobile">Gateways</a>
            <a href="#" class="w3-bar-item w3-button w3-mobile">Einstellungen</a>
            <a href="login.html" class="w3-bar-item w3-button w3-mobile w3-right">Login</a>
      </div>
    </nav>
    <article class="w3-container w3-cell-middle w3-margin">
        <h2>Übersicht über die Gateways</h2>
        <div class="w3-responsive">
        <table class="w3-table-all w3-hoverable">
            <tr class="w3-blue"><th>id</th><th>Status</th><th>Alias</th><th>Konfiguration</th><th>Zertifikat</th></tr>
            <?php
                    $mysqli = new mysqli("localhost", "id12194802_tuddemo", "demo_tud_2020", "id12194802_mmst");
                    if ($mysqli->connect_errno) {
                        die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
                    }
                    $query = "SELECT * FROM id12194802_mmst.gateways";
                    if ($result = $mysqli->query($query)) {

                        /* fetch object array */
                        while ($entry = $result->fetch_assoc()) {
                            echo $entry['id'];
                            $state ="gw-disconnected";
                            if ($entry['state'] == 1){
                                $state = "gw-connected";
                            }
                            echo '<tr><td>'.$entry['id'].'</td><td><div class="'.$state.'"></div></td><td>'.$entry['alias'].'</td><td><button class="w3-button w3-border" >konfigurieren</button></td>';
                           
                            
                            if($entry['cert-date']<time()){
                               echo '<td><button class="w3-button w3-border"><i class="fas fa-calendar-alt"></i> '. date('Y-m-d hh:mm', $entry['cert-date']) .'</button></td>';
                            }else{
                                echo '<td><button class="w3-button w3-border"><i class="fas fa-calendar-times"></i> abgelaufen </button></td>';
                            }
                            
                            echo '</tr>';
                        }
                    
                        /* free result set */
                        $result->close();
                    }

                
            ?>
            <tr><td>1</td><td><div class="gw-connected"></div></td><td>Demo Gateway 1</td><td><button class="w3-button" style="border: black 0.5px solid">konfigurieren</button></td><td><button class="w3-button" style="border: black 0.5px solid">Erneuern</button></td><td>2020-01-20</td></tr>
            <tr><td>2</td><td><div class="gw-disconnected"></div></td><td>Demo Gateway 2</td><td><button class="w3-button" style="border: black 0.5px solid">konfigurieren</button></td><td><button class="w3-button" style="border: black 0.5px solid">Erneuern</button></td><td>2020-01-20</td></tr>
        </table>
    </div>
    </article>
    <footer >
        MMST Projekt 2019 - Gruppe 2.2
    </footer>
</body>