<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Administrateur</title>
    <base href="<?php echo BASE_URL; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="js/dashboard.js" defer></script>
    <style>
      /* Details row container styling */
      .details-row {
          display: none; /* Hidden by default; toggled by JS */
          background: #f8f9fa;
          padding: 20px;
          border: 1px solid #e0e0e0;
          border-radius: 8px;
          margin: 10px auto;
          animation: fadeIn 0.5s ease-in;
      }
      .details-row td {
          text-align: center;
      }
      .details-list {
          width: 500px;  
          list-style: none;
          padding: 0;
          margin: 0 auto;
          max-width: 600px;
          text-align: left;
          align-items: center;
      }
      .details-list li {
          display: flex;
          align-items: center;
          margin-bottom: 10px;
          border-bottom: 1px dotted #d0d7de;
          padding-bottom: 5px;
      }
      .details-list li:last-child {
          border-bottom: none;
          margin-bottom: 0;
      }
      .details-list li strong {
          width: 200px; /* Fixed width for labels */
          color: #007bff;
          font-weight: bold;
          margin-right: 5px;
      }
      .details-list li a {
          color: #007bff;
          text-decoration: underline;
          transition: color 0.3s;
      }
      .details-list li a:hover {
          color: #0056b3;
      }
      @keyframes fadeIn {
          from { opacity: 0; transform: translateY(-10px); }
          to { opacity: 1; transform: translateY(0); }
      }
    </style>
</head>
<body class="dashboard-container">
    <header>
        <h1>Tableau de Bord Administrateur</h1>
        <nav>
            <a href="logout">Déconnexion</a>
        </nav>
    </header>
    
    <section class="token-section">
        <h2>Génération du lien de recrutement</h2>
        <br>
        <div class="token-container">
            <button id="generate-token-btn">Générer un nouveau lien</button>
            <div id="token-result" class="token-result">
                <input type="text" id="token-link" readonly placeholder="Votre lien apparaîtra ici">
                <button id="copy-token-btn">Copier</button>
            </div>
        </div>
    </section>
    
    <section class="table-section">
        <h2>Candidatures Soumises</h2>
        <!-- Search container -->
        <div class="search-container">
            <input type="text" id="table-search" placeholder="Rechercher par nom, prénom, téléphone ...">
        </div>
        <?php if (!empty($applications)): ?>
        <table class="pro-table">
            <thead>
                <tr>
                    <th>N° Sécurité Sociale</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php foreach ($applications as $app): ?>
                <!-- Summary Row -->
                <tr class="summary-row">
                    <td><?php echo htmlspecialchars($app['num_securite']); ?></td>
                    <td><?php echo htmlspecialchars($app['nom']); ?></td>
                    <td><?php echo htmlspecialchars($app['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($app['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                    <td><?php echo htmlspecialchars($app['adresse']); ?></td>
                    <td>
                        <button class="toggle-details" data-id="<?php echo $app['id']; ?>">Afficher plus</button>
                    </td>
                </tr>
                <!-- Hidden Details Row -->
                <tr class="details-row" id="details-<?php echo $app['id']; ?>">
                    <td colspan="7">
                        <ul class="details-list">
                            <li><strong>Date de naissance :</strong> <?php echo htmlspecialchars($app['date_de_naissance']); ?></li>
                            <li><strong>Lieu de naissance :</strong> <?php echo htmlspecialchars($app['lieu_de_naissance']); ?></li>
                            <li><strong>Ville de naissance :</strong> <?php echo htmlspecialchars($app['ville_de_naissance']); ?></li>
                            <li><strong>Pays :</strong> <?php echo htmlspecialchars($app['pays']); ?></li>
                            <li><strong>Nationalité :</strong> <?php echo htmlspecialchars($app['nationalite']); ?></li>
                            <li><strong>Numéro du titre de séjour :</strong> <?php echo htmlspecialchars($app['num_titre_sejour']); ?></li>
                            <li><strong>Date d'expiration du titre de séjour :</strong> <?php echo htmlspecialchars($app['date_expiration_titre_sejour']); ?></li>
                            <li><strong>N° permis :</strong> <?php echo htmlspecialchars($app['num_permis']); ?></li>
                            <li><strong>Date exp. permis :</strong> <?php echo htmlspecialchars($app['date_expiration_permis']); ?></li>
                            <li><strong>Code postal :</strong> <?php echo htmlspecialchars($app['code_postal']); ?></li>
                            <li><strong>Ville résidence :</strong> <?php echo htmlspecialchars($app['ville_residence']); ?></li>
                            <li><strong>Pointeur chasseur :</strong> <?php echo htmlspecialchars($app['pointeur_chasseur']); ?></li>
                            <li><strong>Identifiant France Travail :</strong> <?php echo htmlspecialchars($app['identifiant_france_travail']); ?></li>
                            <li><strong>Code postal agence :</strong> <?php echo htmlspecialchars($app['code_postal_agence']); ?></li>
                            <li>
                                <strong>CV (Fichier PDF) :</strong>
                                <?php if(!empty($app['fichier_pdf'])): ?>
                                    <a href="view_pdf.php?file=<?php echo urlencode($app['fichier_pdf']); ?>" target="_blank">Voir CV</a>
                                <?php endif; ?>
                            </li>
                            <li>
                                <strong>Passeport :</strong>
                                <?php if(!empty($app['passport'])): ?>
                                    <a href="view_pdf.php?file=<?php echo urlencode($app['passport']); ?>" target="_blank">Voir Passeport</a>
                                <?php endif; ?>
                            </li>
                            <li>
                                <strong>Pièce d'identité :</strong>
                                <?php if(!empty($app['p_id'])): ?>
                                    <a href="view_pdf.php?file=<?php echo urlencode($app['p_id']); ?>" target="_blank">Voir Pièce d'identité</a>
                                <?php endif; ?>
                            </li>
                            <li>
                                <strong>Documents Photo :</strong>
                                <?php if(!empty($app['documents_photo'])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($app['documents_photo']); ?>" target="_blank">Voir Documents</a>
                                <?php endif; ?>
                            </li>
                            <li><strong>Date embauche :</strong> <?php echo htmlspecialchars($app['date_embauche']); ?></li>
                            <li><strong>Date du contrat :</strong> <?php echo htmlspecialchars($app['date_contrat']); ?></li>
                            <li><strong>Date de création :</strong> <?php echo htmlspecialchars($app['created_at']); ?></li>
                        </ul>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <button id="prev-page" disabled>Précédent</button>
            <span id="page-info">Page 1</span>
            <button id="next-page">Suivant</button>
        </div>
        <?php else: ?>
        <p>Aucune candidature soumise pour le moment.</p>
        <?php endif; ?>
    </section>
   
</body>
</html>
