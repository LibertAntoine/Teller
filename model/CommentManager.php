<?php

namespace model;

class CommentManager extends DBAccess 
{
  public function add(Comment $comment) {	
		$q = $this->db->prepare("INSERT INTO `projet5_comments` (`articleId`, `userId`, `groupId`, `content`, `creationDate`, `reporting`) VALUES (:articleId, :userId, :groupId, :content, NOW(), '0');");

		$q->bindValue(':userId', $comment->getUserId());
    $q->bindValue(':articleId', $comment->getArticleId());
    $q->bindValue(':groupId', $comment->getGroupId());
    $q->bindValue(':content', $comment->getContent());

		$q->execute();

    $comment->hydrate(['id' => $this->db->lastInsertId()]);
    return $comment;
  }

  public function count() {
    return $this->db->query('SELECT COUNT(*) FROM projet5_comments')->fetchColumn();
  }

  public function delete($commentId) {
    $this->db->exec('DELETE FROM projet5_comments WHERE id = '. $commentId);
  }

  public function deleteList($articleId) {
    $this->db->exec('DELETE FROM projet5_comments WHERE articleId = '. $articleId);
  }

 	public function exists($info) {
   	if (is_int($info)) {
      return (bool) $this->db->query('SELECT COUNT(*) FROM projet5_comments WHERE id = '.$info)->fetchColumn();
	  }
  }

  public function get($info) {
    if (is_int($info)) {
      $q = $this->db->query('SELECT id, userId, groupId, articleId, content, DATE_FORMAT(creationDate, \'%d/%m/%Y à %Hh%imin%ss\') AS creationDate, reporting FROM projet5_comments WHERE id = '.$info);
      $comment = $q->fetch(\PDO::FETCH_ASSOC);
    }
    return new Comment($comment);
  }


  public function getPost($postId) {
    $comments = [];
    
    $q = $this->db->query('SELECT id, userId, groupId, articleId, content, DATE_FORMAT(creationDate, \'%d/%m/%Y à %Hh%imin%ss\') AS creationDate, reporting FROM projet5_comments WHERE articleId ='. $postId .' ORDER BY creationDate');
    
    while ($data = $q->fetch(\PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    
    return $comments;
  }


  public function getList($articleId) {
    $comments = [];
    
    $q = $this->db->prepare('SELECT id, userId, groupId, articleId, content, DATE_FORMAT(creationDate, \'%d/%m/%Y à %Hh%imin%ss\') AS creationDate, reporting FROM projet5_comments WHERE articleId = :articleId ORDER BY creationDate DESC');
    $q->execute([':articleId' => $articleId]);
    
    while ($data = $q->fetch(\PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }
  
  public function getReportingList() {
    $comments = [];
    
    $q = $this->db->prepare('SELECT id, userId, groupId, articleId, content, DATE_FORMAT(creationDate, \'%d/%m/%Y à %Hh%imin%ss\') AS creationDate, reporting FROM projet5_comments WHERE reporting > 0 ORDER BY creationDate DESC');
    $q->execute();
    
    while ($data = $q->fetch(\PDO::FETCH_ASSOC)) {
      $comments[] = new Comment($data);
    }
    return $comments;
  }

  public function removeReporting($commentId) {
    $q = $this->db->prepare('UPDATE projet5_comments SET reporting = 0 WHERE id = :id');
      
    $q->bindValue(':id', $commentId);

    $q->execute();
  }


  public function reporting($commentId) {
    $q = $this->db->prepare('UPDATE projet5_comments SET reporting = reporting + 1 WHERE id = :id');
      
    $q->bindValue(':id', $commentId);

    $q->execute();
  }

  public function update(Comment $comment) {
    $q = $this->db->prepare('UPDATE projet5_comments SET userId = :userId, articleId = :articleId, content = :content, reporting = :reporting WHERE id = :id');
    
    $q->bindValue(':userId', $comment->getUserId());
    $q->bindValue(':articleId', $comment->getArticleId());
    $q->bindValue(':content', $comment->getContent());
    $q->bindValue(':reporting', $comment->getReporting());
    $q->bindValue(':id', $comment->getId());

    $q->execute();
  }
}

