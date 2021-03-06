
	<section id="memberBar">
						<?php if ($group->getPublic() == 1) { ?>
							<p id='status-group'>Ce groupe est public. Il est visible par tous le monde.</p>
						<?php } else { ?>
							<p id='status-group'>Ce groupe est privé. Il n'est visible que par ses membres.</p>
						<?php }	?>
		<a href="index.php?action=adminGroup&amp;id=<?= $group->getId() ?>#member-gestion"><h3 class="center">Membres</h3></a>
		<div>

							<?php if (isset($admins)) { ?>
								<h4 class="center">Admin</h4>
								<?php foreach ($admins as $adminId => $admin) { ?>
									<div><p class="center"><?= $profils[$adminId]->getPseudo()  ?></p></div>
								<?php } 
							} ?>			
							<?php if (isset($authors)) { ?>
								<h4 class="center">Auteur</h4>
								<?php foreach ($authors as $authorId => $author) { ?>
									<div><p class="center"><?= $profils[$authorId]->getPseudo()  ?></p></div>
								<?php } 
							} ?>				
							<?php if (isset($commenters)) { ?>
								<h4 class="center">Commenteur</h4>
								<?php foreach ($commenters as $commenterId => $commenter) { ?>
									<div><p class="center"><?= $profils[$commenterId]->getPseudo()  ?></p></div>
								<?php } 
							} ?>
							<?php if (isset($viewers)) { ?>
								<h4 class="center">Viewer</h4>
								<?php foreach ($viewers as $viewerId => $viewer) { ?>
									<div><p class="center"><?= $profils[$viewerId]->getPseudo()  ?></p></div>
								<?php } 
							} ?>
		<?php if (isset($_SESSION['id'])) {
			if (isset($admins[$_SESSION['id']])) { ?>
	            <p  class="center" id="member-link"><a  href="index.php?action=adminGroup&amp;id=<?= $group->getId() ?>#access-gestion">Gerer le groupe</a></p>
	        <?php } 
	   	} ?>
		</div>

	</section>					

