<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire de recrutement</title>
  <base href="<?php echo BASE_URL; ?>">
  <link rel="stylesheet" href="css/form.css">
  <script src="js/form.js" defer></script>
  <style>
    .error-message {
      color: #fff;
      background-color: #e74c3c;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>
<body class="container animate">
  <h1>Formulaire de recrutement</h1>
  
  <!-- Affichage des erreurs -->
  <?php if(isset($error) && !empty($error)): ?>
    <div class="error-message">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <?php $token = $_GET['token'] ?? ''; ?>
  <form action="process-recruitment" method="post" enctype="multipart/form-data" class="cool-form">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

    <!-- Nom & Prénom -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="nom" required>
        <label>Nom</label>
      </div>
      <div class="form-group">
        <input type="text" name="prenom" required>
        <label>Prénom</label>
      </div>
    </div>

    <!-- Date & Lieu de naissance -->
    <div class="form-row">
      <div class="form-group">
        <input type="date" name="date_de_naissance" required>
        <label>Date de naissance</label>
      </div>
      <div class="form-group">
        <input type="text" name="lieu_de_naissance" required>
        <label>Lieu de naissance</label>
      </div>
    </div>

    <!-- Ville de naissance & Pays -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="ville_de_naissance" required>
        <label>Ville de naissance</label>
      </div>
      <div class="form-group">
        <input type="text" name="pays" required>
        <label>Pays</label>
      </div>
    </div>

    <!-- Checkbox: Je suis français -->
    <div class="form-row">
      <div class="form-group full check-container">
        <input type="checkbox" id="is_français" name="is_français" value="1" checked>
        <label for="is_français">Je suis français</label>
      </div>
    </div>

    <!-- Section pour non français -->
    <div class="form-row" id="non_francais_section" style="display: none;">
      <div class="form-group">
        <input type="text" name="num_titre_sejour">
        <label>Numéro du titre de séjour</label>
      </div>
      <div class="form-group">
        <input type="date" name="date_expiration_titre_sejour">
        <label>Date d'expiration du titre de séjour</label>
      </div>
      <div class="form-group">
        <!-- For non French, we require nationalité input -->
        <input type="text" name="nationalite" required>
        <label>Nationalité</label>
      </div>
      <div class="form-group">
        <input type="file" name="passeport" required>
        <label>Passeport</label>
      </div>
    </div>

    <!-- Sécurité sociale, identifiant France Travail et code postal agence -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="num_securite" required>
        <label>N° de sécurité sociale</label>
      </div>
      <div class="form-group">
        <input type="text" name="identifiant_france_travail" required>
        <label>N° identifiant France Travail</label>
      </div>
      <div class="form-group">
        <input type="text" name="code_postal_agence" required>
        <label>Code postal de l'agence à laquelle vous êtes rattaché</label>
      </div>
    </div>

    <!-- Permis -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="num_permis" required>
        <label>N° de permis</label>
      </div>
      <div class="form-group">
        <input type="date" name="date_expiration_permis" required>
        <label>Date d'expiration du permis</label>
      </div>
    </div>

    <!-- Ville de résidence et Adresse -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="ville_residence" required>
        <label>Ville de résidence</label>
      </div>
      <div class="form-group">
        <input type="text" name="adresse" required>
        <label>Adresse</label>
      </div>
    </div>

    <!-- Code postal & Pointeur -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="code_postal" required>
        <label>Code postal</label>
      </div>
      <div class="form-group">
        <input type="text" name="pointeur_chasseur" required>
        <label>Pointeur chaussure</label>
      </div>
    </div>

    <!-- Téléphone et Fax -->
    <div class="form-row">
      <div class="form-group">
        <input type="text" name="telephone" required>
        <label>Téléphone</label>
      </div>
      <div class="form-group">
        <input type="text" name="fax">
        <label>Fax (optionnel)</label>
      </div>
    </div>

    <!-- Email & CV -->
    <div class="form-row">
      <div class="form-group">
        <input type="email" name="email" required>
        <label>Email</label>
      </div>
      <div class="form-group">
        <input type="file" name="fichier_pdf" required>
        <label>Document CV (PDF)</label>
      </div>
    </div>

    <!-- Pièce d'identité -->
    <div class="form-row">
      <div class="form-group">
        <input type="file" name="p_id" required>
        <label>Pièce d'identité (PDF)</label>
      </div>
    </div>

    <!-- Optionnel: Section salarié -->
    <div class="form-row">
      <div class="form-group full check-container">
        <input type="checkbox" id="isSalarie" name="isSalarie" value="1">
        <label for="isSalarie">Je suis salarié</label>
      </div>
    </div>
    <div id="sectionSalarie" class="section-salarie">
      <div class="form-row">
        <div class="form-group">
          <input type="date" name="date_embauche">
          <label>Date d'embauche</label>
        </div>
        <div class="form-group">
          <input type="date" name="date_contrat">
          <label>Date de contrat</label>
        </div>
        <div class="form-group">
          <input type="file" name="documents_photo">
          <label>Documents Photo (PDF)</label>
        </div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group full">
        <button type="submit">Envoyer la candidature</button>
      </div>
    </div>
  </form>

  <script>
    // Toggle non-français section
    document.addEventListener('DOMContentLoaded', function() {
      var isFrancaisCheckbox = document.getElementById('is_français');
      var nonFrancaisSection = document.getElementById('non_francais_section');
      function toggleNonFrancais() {
        // If checkbox is checked (is French), hide the non-French section
        if (isFrancaisCheckbox.checked) {
          nonFrancaisSection.style.display = 'none';
        } else {
          nonFrancaisSection.style.display = 'flex';
        }
      }
      isFrancaisCheckbox.addEventListener('change', toggleNonFrancais);
      toggleNonFrancais(); // Set initial state
    });
  </script>
</body>
</html>
