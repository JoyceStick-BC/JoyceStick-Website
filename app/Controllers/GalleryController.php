<?php

namespace Carbon\Controllers;
use \Slim\Views\Twig as View;

class GalleryController extends Controller {
	public function index($request, $response) {
		return $this->view->render($response, 'gallery.twig');
	}
}