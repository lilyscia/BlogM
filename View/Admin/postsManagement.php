<?php $this->title = "Mon Blog - Administration" ?>

<?php include 'adminNav.php'?>

<!--  Content  -->
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
    <p class="help-block text-danger"><?= $errorMessage ?></p>
    <p class="help-block text-success"><?= $successMessage ?></p>
      <p>Bienvenue, <?= $this->sanitize($username) ?> ! Ce blog contient <?= $this->sanitize($numberPosts) ?> article(s).</p>
      
        <h2>Gestion des articles</h2>
        
            <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>Id</th>
                <th>Titre</th>
                <th>Date de création</th>
                <th>Date mise à jour</th>
                <th>Auteur</th>
                <th>Opérations</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
              <tr>
                <td><?= $this->sanitize($post->getId()) ?></td>
                <td><?= $this->sanitize($post->getTitle()) ?></td>
                <td><?= $this->sanitize($post->getDateCreation()) ?></td>
                <td><?= $this->sanitize($post->getDateUpdate()) ?></td>
                <td><?= $this->sanitize($post->getUsername()) ?></td>
                <td>
					<a class="btn btn-primary operation" title="Voir" href="post/index/<?= $this->sanitize($post->getId()) ?>" target="_blank">
						<i class="fas fa-search"></i>
					</a>
					<a class="btn btn-success operation" title="Modifier" href="admin/postEdit/<?= $this->sanitize($post->getId()) ?>">
						<i class="fas fa-pen"></i>
					</a>
                	<a class="btn btn-danger operation" title="Supprimer" href="admin/deletePost/<?= $this->sanitize($post->getId()) ?>" onclick="return confirm('Attention cette action supprimera également les commentaires associés à ce post ! Êtes vous sûr ?')">
                		<i class="fas fa-trash-alt"></i>
                	</a>

                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
        </div>
      
    </div>
  </div>
</div>