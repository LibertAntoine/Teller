<?php

	require_once('model/TextContent.php');
	require_once('model/DBAccess.php');
	require_once('model/Post.php');
	require_once('model/PostManager.php');
	require_once('model/Comment.php');
	require_once('model/CommentManager.php');
	require_once('model/Group.php');
	require_once('model/GroupManager.php');	
	require_once('model/User.php');
	require_once('model/UserManager.php');
	require_once('model/LinkGroupManager.php');
	require_once('model/LinkGroup.php');
	require_once('model/LinkFriendManager.php');
	require_once('model/LinkFriend.php');
	require_once('model/LinkReportingManager.php');
	require_once('model/LinkReporting.php');



class UserCRUD {
	
	public function add($pseudo, $mdp)
	{
		$pass_hache = password_hash($mdp, PASSWORD_DEFAULT);
	    $user = new User(['pseudo' => $pseudo, 'mdp' => $pass_hache]);	

	    $userManager = new UserManager();
	    if ($userManager->exists($pseudo)){
	    	return 'exist';
	    } else { 
	    	return $userManager->add($user);
		}
	}

	public function editPseudo($newPseudo) {
	    if(strlen($newPseudo) < 25 && strlen($newPseudo) > 8) {
	        $userManager = new UserManager();
	        $user = $userManager->get($_SESSION['id']);
	        $user->setPseudo($newPseudo);
	        $userManager->update($user);
	        $_SESSION['pseudo'] = $newPseudo;
	        $message = 'Le nouveau pseudo a bien été enregistré';
	    } else {
        	$message = 'Le pseudo renseigné n\'est pas compris entre 8 et 15 caractères';
    	}
    	require('view/backend/backOffice.php');
	}

	public function editMdp($oldMdp, $newMdp) {
	    $userManager = new userManager();
	    $user = $userManager->get(intval($_SESSION['id']));
	    if ($user->getMdp() === $oldMdp) {
	        if(strlen($newMdp) < 25 && strlen($newMdp) > 8) {
	        $user->setMdp($newMdp);
	        $userManager->update($user);
	        $message = 'Le nouveau mot de passe a bien été enregistré';
	    	} else {
	        	$message = 'Le mot de passe renseigné n\'est pas compris entre 8 et 15 caractères';
	    	}
	    } else {
	        $message = 'L\'ancien mot de passe renseigné n\'est pas le bon';
		}
	    require('view/backend/backOffice.php');
	}

	public function read($info, $mdp = NULL) {
		$userManager = new UserManager();
		if ($userManager->exists($info)) {
			$user = $userManager->get($info);
			if ($mdp !== NULL) {
				$verif = password_verify($mdp, $user->getMdp());
				if ($verif) {
					return $user;
				}
			} else {
				return $user;
			}
			
		}
	}

	public function readAll() {
		$userManager = new UserManager();
		$usersList = $userManager->getAll();
		foreach ($usersList as $user) {
			if ($user->getActif() == 1) {
				$allUsers[$user->getId()] = $user;
			}
		}
		return $allUsers;
	}

	public function readName($userId) {
		$userManager = new UserManager();
		if ($userManager->exists($userId)) {
			$user = $userManager->get($userId);
			$pseudo = $user->getPseudo();
			if ($pseudo != NULL) {
				return $pseudo;
			}
		}	
	}


	public function delete() {
		$userManager = new UserManager();
		$delete1 = $userManager->delete($_SESSION['id']);
		$linkGroupCRUD = new LinkGroupCRUD();
		$delete2 = $linkGroupCRUD->deleteAllGroups();
		$linkFriendCRUD = new LinkFriendCRUD();
		$delete3 = $linkFriendCRUD->deleteAll();
		$this->logOut();
		return 'ok';
	}


	public function logOut() {	
		session_destroy();
	}
}