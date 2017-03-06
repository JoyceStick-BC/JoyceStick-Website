<?php

namespace Carbon\Controllers;
use \Slim\Views\Twig as View;

class BlogController extends Controller {
	public function getBlog($request, $response) {
		return $this->view->render($response, '/blog/home.twig');
	}
}