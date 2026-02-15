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
    body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #f5f7fa, #e4ebf5);
    margin: 0;
    padding: 20px;
    color: #333;
}

/* ===== TITRE ===== */
h1 {
    text-align: center;
    font-size: 38px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 30px;
    text-transform: uppercase;
    background: linear-gradient(90deg, #ff7b00, #ffb347);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.1);
}

/* ===== BLOC INFO ===== */
#info {
    margin-top: 30px;
    padding: 20px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    max-width: 600px;
}

/* ===== TEXTE DONNÉES ===== */
#territoireData p {
    font-size: 15px;          /* plus petit */
    margin: 6px 0;            /* moins d’espace vertical */
    padding: 6px 10px;        /* moins de padding */
    border-left: 4px solid orange;
    background-color: #f9f9f9;
    border-radius: 4px;
}


#territoireData p:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.08);
}

/* Valeurs en gras */
#territoireData p strong {
    color: #ff7b00;
}

/* ===== BOUTONS PLUS MODERNES ===== */
#buttons button {
    border: none;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.2s ease;
}

#buttons button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

#buttons button.active {
    background: linear-gradient(90deg, #ff7b00, #ffb347);
}
#buttons {
    display: flex;
    justify-content: center; /* centre horizontalement */
    align-items: center;
    margin-bottom: 15px;
}
#info {
    margin: 40px auto;   /* centre horizontalement */
    padding: 20px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    max-width: 700px;    /* largeur contrôlée */
    width: 90%;          /* responsive */
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
          <button id="btn1" class="active">Sélection unique</button>
          <button id="btn2">Sélection multiple</button>
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
