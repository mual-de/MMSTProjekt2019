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
            <a href="showDevices.php" class="w3-bar-item w3-button w3-mobile">Gateways</a>
            <a href="#" class="w3-bar-item w3-button w3-mobile">Einstellungen</a>
            <a href="login.html" class="w3-bar-item w3-button w3-mobile w3-right">Login</a>
      </div>
    </nav>
    <article class="w3-container w3-cell-middle w3-margin">
        <h2>Übersicht über die Gateways</h2>
        <form action="showDevices.php" method="get">
            <fieldset>
                <legend>Filtern der Datenbank</legend>
                Alias: <input type="text" name="alias"><br>
                Status: <input type="number" min="-1" max ="1" default="-1" name="state"><br>
				VPN-Netzwerk: <input type="text" name="vpn_network"><br>
                <input type="submit" value="Filtern">

            </fieldset>
        </form>
        <div class="w3-responsive">
        <table class="w3-table-all w3-hoverable">
            <!-- <tr class="w3-blue"><th>id</th><th>Status</th><th>Alias</th><th>Konfiguration</th><th>Zertifikat</th></tr> -->
            <?php
					$show_config_cols = false;
					if ($show_config_cols) {						
						<tr class="w3-blue"><th>id</th><th>Status</th><th>Alias</th><th>Konfiguration</th><th>VPN-Netzwerk</th><th>Zertifikat</th></tr>
					} else {
						<tr class="w3-blue"><th>id</th><th>Status</th><th>Alias</th><th>Konfiguration</th><th>Zertifikat</th></tr>
					}

                    $mysqli = new mysqli("localhost", "id12194802_tuddemo", "demo_tud_2020", "id12194802_mmst");
                    if ($mysqli->connect_errno) {
                        die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
                    }

                    $filter_active = false;					
                    $where_select = "";
                    if(isset($_GET['alias'])){
                        if($_GET['alias'] != ''){
                            $filter_active = true;
                            $where_select = "alias LIKE'".$_GET['alias']."'";
                        }
                    }
                    if(isset($_GET['state'])){
                        if($_GET['state'] != "-1" && $_GET['state'] != ""){
                            if($filter_active){
                                $where_select = $where_select. " AND ";
                            }
                            $filter_active = true;
                            $where_select = $where_select." state='".$_GET['state']."'";
                        }
                    }
                    $query = "SELECT * FROM id12194802_mmst.gateways";
                    if($filter_active){
                        $query = $query.' WHERE '.$where_select;
                    }
                    echo $query;
                    if ($result = $mysqli->query($query)) {

                        /* fetch object array */
                        while ($entry = $result->fetch_assoc()) {
                            $state ="gw-disconnected";
                            if ($entry['state'] == 1){
                                $state = "gw-connected";
                            }
                            echo '<tr><td>'.$entry['id'].'</td><td><div class="'.$state.'"></div></td><td>'.$entry['alias'].'</td><td><button class="w3-button w3-border" >konfigurieren</button></td>';
                           
						    if ($show_config_cols) {
								echo '<td>'.$entry['config-port'].'</td>'
							}
                            
                            if($entry['cert-date']>=time()){
                               echo '<td><button class="w3-button w3-border"><i class="fas fa-calendar-alt"></i> '. date('Y-m-d h:m', $entry['cert-date']) .'</button></td>';
                            }else{
                                echo '<td><button class="w3-button w3-border"><i class="fas fa-calendar-times"></i> abgelaufen </button></td>';
                            }
                            
                            echo '</tr>';
                        }
                    
                        /* free result set */
                        $result->close();
                    }

                
            ?>
        </table>
    </div>
    </article>
    <footer >
        MMST Projekt 2019 - Gruppe 2.2
    </footer>
</body>