﻿﻿<head>
    <title>Gateways</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/w3.css">
    <!-- Icons von FontAwesome -->
    <script src="https://kit.fontawesome.com/c1909a32ba.js" crossorigin="anonymous"></script>
</head>


<body>
    <div class="topnav" id="Topnav">
        <a href="index.html" >Home</a>
        <a href="#" class="active">Gateways</a>
        <a href="settings.html" >Konto</a>
        <a href="login.html" >Logout</a>
        <a href="javascript:void(0);" class="icon" onclick="Navigation()">
          <i class="fa fa-bars"></i>
        </a>
    </div>

    <article class="w3-container w3-cell-middle w3-margin">
        <h2>Übersicht über die Gateways</h2>

        <button onclick="Filter()" class="w3-button w3-blue"><i class="fas fa-search"></i> Datenbank filtern</button>
        <form id="filter" action="showDevices.php" method="get"> 
            <fieldset>
                <legend>Filtern der Datenbank</legend>
                <label for="alias" > Alias:</label> <br>
                <input type="text" id="alias" class="w3-input w3-border" > 
                <br>
                
                <label for="Verbindungsstatus">Verbindungsstatus:</label> <br>
                <input class="w3-radio" type="radio" name="state" value="1" checked>
                <label>Verbunden</label>
                <input class="w3-radio" type="radio" name="state" value="0">
                <label>Getrennt</label>
                <input class="w3-radio" type="radio" name="state" value="-1">
                <label>beliebig</label>
                <br>
                <br>

                <label for="Zertifikat">Zertifikat:</label> <br>
                <select class="w3-select" name="zertifikat">
                    <option value="" disabled selected>Zertifiakt auswählen</option>
                    <option value="1">beliebig</option>
                    <option value="2">Zertifikat x</option>
                    <option value="3">Zertifikat y</option>
                </select> 
                <br>
                <label><i class="fas fa-calendar-alt"></i> Ablaufdatum bis: </label>
                <input type="text" id="ablaufdatum" class="w3-input w3-border" > 
                <br>

                <label><i class="fas fa-network-wired"></i> VPN:</label>
                <select class="w3-select" name="vpn_network">
                    <option value="" disabled selected>VPN auswählen</option>
                    <option value="1">beliebig</option>
                    <option value="2">VPN a</option>
                    <option value="3">VPN b</option>
                </select> 
                <br>
                <br>

                <label>ID: </label>
                <input type="text" id="id" class="w3-input w3-border" > 
                <br>

                <label><i class="fas"></i> Port:</label>
                <input type="text" id="port" class="w3-input w3-border" > 
                <br>

                <label>IP: </label>
                <input type="text" id="ip" class="w3-input w3-border" > 
                <br>
                <input class="w3-button w3-blue" type="submit" value="Filtern">
            </fieldset>

            </br>

            <button class="w3-button w3-blue"><i class="far fa-save"></i> Filtereinstellung speichern</button>
        </form>


        <div class="w3-responsive">
        <table class="w3-table-all">
            <!-- <tr class="w3-blue"><th>id</th><th>Status</th><th>Alias</th><th>Konfiguration</th><th>Zertifikat</th></tr> -->
            <?php
                    $show_config_cols = false;
					if(isset($_POST['test'])){
					    if($_POST['test']){
                            $show_config_cols = false;
					    } else {
					        $show_config_cols = true;
					    }					    
                    }
                    
					
					if ($show_config_cols) {						
						echo '<tr class="w3-blue"><th>Status</th><th>Alias</th><th>
						<form action="" method="post"><input type="hidden" name="test" value="exist"><input type="submit" value="Config"></form>
						</th><th>VPN-Netzwerk</th><th>Port</th><th>Zertifikat</th></tr>';
					} else {
						echo '<tr class="w3-blue"><th>Status</th><th>Alias</th><th>
						<form action="" method="post"><input type="hidden" name="test" value=""><input type="submit" value="Config"></form>
						</th><th>Zertifikat</th></tr>';
					}

                    $mysqli = new mysqli("sql100.epizy.com", "epiz_25157497", "8ISjRQHttm", "epiz_25157497_demommst");
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
					
					if(isset($_GET['vpn_network'])){
                        if($_GET['vpn_network'] != ''){
							if($filter_active){
                                $where_select = $where_select. " AND ";
                            }
                            $filter_active = true;
                            $where_select = $where_select."vpn_network LIKE'".$_GET['vpn_network']."'";
                        }
                    }
					
					
					
                    $query = "SELECT * FROM epiz_25157497_demommst.gateways";
                    if($filter_active){
                        $query = $query.' WHERE '.$where_select;
                    }
                    #echo $query;
                    if ($result = $mysqli->query($query)) {

                        /* fetch object array */
                        while ($entry = $result->fetch_assoc()) {
                            $state ="gw-disconnected";
                            if ($entry['state'] == 1){
                                $state = "gw-connected";
                            }
                            echo '<tr><td><div class="'.$state.'"></div></td>
                                        <td>'.$entry['alias'].'</td>
                                        <td><button class="w3-button w3-border" onclick="konfig_Window()" ><i class="fas fa-cogs"></i>
                                        </button></td>';
                           
						    if ($show_config_cols) {
								echo '<td>'.$entry['vpn_network'].'</td>';
								echo '<td>'.$entry['config-port'].'</td>';
							}
                            
                            if($entry['cert-date']>=time()){
                               echo '<td><button class="w3-button w3-border"  onclick="cert_date_Window()"><i class="fas fa-calendar-alt"></i> '. date('Y-m-d h:m', $entry['cert-date']) .'</button></td>';
                            }else{
                                echo '<td><button class="w3-button w3-border w3-red"  onclick="cert_date_Window()"><i class="fas fa-calendar-times"></i> abgelaufen </button></td>';
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
    
    <script>
       function konfig_Window() {
           document.getElementById('konfig').style.display='block'
       }
       
       function cert_date_Window() {
           document.getElementById('cert-date').style.display='block'
       }
    </script>
    
    <!-- Konfig-Fenster -->
<div id="konfig" class="w3-modal">
    <div class="w3-modal-content">
      <div class="w3-container">
        <span onclick="document.getElementById('konfig').style.display='none'" class="w3-button w3-display-topright">&times;</span>
        <h3>Konfiguration Bearbeiten</h3>
        <fieldset class="fs-centered">
            <form>
                <label for="Alias" > Alias:</label> <br>
                <input type="text" id="alias" class="w3-input w3-border" > 
                <br>

                <label><i class="fas fa-network-wired"></i> VPN:</label>
                <select class="w3-select" name="vpn">
                    <option value="" disabled selected>VPN auswählen</option>
                    <option value="1">beliebig</option>
                    <option value="2">VPN a</option>
                    <option value="3">VPN b</option>
                </select> 
                <br>
                <br>

                <label>Port:</label>
                <input type="text" id="port" class="w3-input w3-border" > 
                <br>

            </form> 

        </fieldset>
        <div class= "w3-container w3-margin-top">
            <button onclick="document.getElementById('konfig').style.display='none'" class="w3-button w3-right w3-blue">Abbrechen</button>
            <button onclick="document.getElementById('konfig').style.display='none'" class="w3-button w3-right w3-blue">Übernehmen</button>         
        </div>
      </div>
    </div>
  </div>
  
      <!-- Zertifikatsablaufdatum-Fenster -->
<div id="cert-date" class="w3-modal">
    <div class="w3-modal-content">
      <div class="w3-container">
        <span onclick="document.getElementById('cert-date').style.display='none'" class="w3-button w3-display-topright">&times;</span>
        <h3>Konfiguration Bearbeiten</h3>
        <fieldset class="fs-centered">
            <form>
                <label for="Zertifikat">Zertifikat:</label> <br>
                <select class="w3-select" name="zertifikat">
                    <option value="" disabled selected>Zertifiakt auswählen</option>
                    <option value="1">beliebig</option>
                    <option value="2">Zertifikat x</option>
                    <option value="3">Zertifikat y</option>
                </select> 
                <br>
                <label><i class="fas fa-calendar-alt"></i> Ablaufdatum bis: </label>
                <input type="text" id="ablaufdatum" class="w3-input w3-border" > 
                <br>
                <br>
            </form> 

        </fieldset>
        <div class= "w3-container w3-margin-top">
            <button onclick="document.getElementById('cert-date').style.display='none'" class="w3-button w3-right w3-blue">Abbrechen</button>
            <button onclick="document.getElementById('cert-date').style.display='none'" class="w3-button w3-right w3-blue">Übernehmen</button>         
        </div>
      </div>
    </div>
  </div>
  <br>
  <br>
    
    <footer class="w3-white">
        MMST Projekt 2019 - Gruppe 2.2
    </footer>

    <script>
        function Filter() {
          var x = document.getElementById("filter");
          if (x.style.display === "none") {
            x.style.display = "block";
          } else {
            x.style.display = "none";
          }
        }

        function Navigation() {
          var x = document.getElementById("Topnav");
          if (x.className === "topnav") {
            x.className += " responsive";
          } else {
            x.className = "topnav";
          }
        }
    </script>
</body>