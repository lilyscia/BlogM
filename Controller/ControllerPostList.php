<?php

use \Alice\Framework\Controller;
use \Blog\Model\Manager\PostsManager;

class ControllerPostList extends Controller
{
	private $postsManager;

	public function __construct()
	{
		$this->postsManager = new PostsManager();
	}

	// Affiche la liste de tous les posts du blog
	public function index() 
	{
		$posts = $this->postsManager->getList();
		$this->generateView(array('posts' => $posts));
    }
}
