<!DOCTYPE html>
<html>
<head>
  <title>Carte interactive Europe</title>
  <style>
    svg { width: 1000px; height: auto; }
    path { fill: lightgray; stroke: black; cursor: pointer; transition: fill 0.2s; }
    path:hover { fill: orange; }

    #buttons button {
        margin: 5px;
        padding: 5px 10px;
        cursor: pointer;
    }

    #buttons button.active {
        background-color: orange;
        color: white;
    }
  </style>
</head>
<body>
  <h1>Carte interactive de l'Europe</h1>

  <!-- Inclure le SVG -->
  <?php echo file_get_contents('SVG/europe.svg'); ?>

  <!-- Boutons et info -->
  <div id="info">
      <div id="buttons">
          <button id="btn1" class="active">Mode 1</button>
          <button id="btn2">Mode 2</button>
      </div>
      <div id="territoireData"></div>
  </div>

  <script>
    let currentMode = 1; // 1 = classique, 2 = multi
    let selectedCountry = null; // pour mode 1
    let selectedCountries = new Set(); // pour mode 2

    const btn1 = document.getElementById('btn1');
    const btn2 = document.getElementById('btn2');

    // Changer de mode
    btn1.addEventListener('click', () => {
        currentMode = 1;
        btn1.classList.add('active');
        btn2.classList.remove('active');
        selectedCountries.clear();
        resetColors();
        if(selectedCountry) loadTerritory(selectedCountry, 1);
        else document.getElementById('territoireData').innerHTML = '';
    });

    btn2.addEventListener('click', () => {
        currentMode = 2;
        btn2.classList.add('active');
        btn1.classList.remove('active');
        selectedCountry = null;
        resetColors();
        selectedCountries.clear();
        document.getElementById('territoireData').innerHTML = '';
    });

    function resetColors() {
        document.querySelectorAll('svg path').forEach(p => {
            p.style.fill = 'lightgray';
        });
    }

    // Charger données d’un seul pays (mode 1)
    function loadTerritory(nom, mode) {
        fetch('./pageMap/territoire.php?nom=' + nom + '&mode=' + mode)
            .then(res => res.text())
            .then(data => {
                document.getElementById('territoireData').innerHTML = data;
            });
    }

    // Charger données de plusieurs pays (mode 2)
    function loadMultiTerritories() {
        if(selectedCountries.size === 0) {
            document.getElementById('territoireData').innerHTML = '';
            return;
        }

        const params = Array.from(selectedCountries).map(nom => 'nom[]=' + nom).join('&');

        fetch('./pageMap/territoire.php?mode=2&' + params)
            .then(res => res.text())
            .then(data => {
                document.getElementById('territoireData').innerHTML = data;
            });
    }

    // Rendre les pays cliquables
    document.querySelectorAll('svg path').forEach(function(p){
      p.addEventListener('click', function(){
          const nom = this.id;

          if(currentMode === 1){
              selectedCountry = nom;
              resetColors();
              this.style.fill = 'orange';
              loadTerritory(nom, 1);
          } else {
              if(selectedCountries.has(nom)){
                  selectedCountries.delete(nom);
                  this.style.fill = 'lightgray';
              } else {
                  selectedCountries.add(nom);
                  this.style.fill = 'orange';
              }
              loadMultiTerritories();
          }
      });
    });
  </script>
</body>
</html>
