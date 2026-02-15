<!DOCTYPE html>
<html>
<head>
  <title>Carte interactive Europe</title>
  <style>
    svg { width: 1000px; height: auto; }
    path { fill: lightgray; stroke: black; cursor: pointer; }
    path:hover { fill: orange; }
  </style>
</head>
<body>
  <h1>Carte interactive de l'Europe</h1>

  <!-- Inclure le SVG -->
  <?php
    echo file_get_contents('SVG/europe.svg'); // chemin relatif depuis pageMap
  ?>

  <div id="info"></div>

  <script>
    // Rendre tous les pays cliquables
    document.querySelectorAll('svg path').forEach(function(p){
      p.addEventListener('click', function(){
        // Appel AJAX vers territoire.php dans le même dossier
        fetch('./pageMap/territoire.php?nom=' + this.id)
          .then(response => response.text())
          .then(data => {
            document.getElementById('info').innerHTML = data;
          });
      });
    });
  </script>
</body>
</html>
