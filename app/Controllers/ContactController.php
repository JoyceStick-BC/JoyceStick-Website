<?php

namespace Carbon\Controllers;
use \Slim\Views\Twig as View;

class ContactController extends Controller {
	public function index($request, $response) {
		return $this->view->render($response, 'contact.twig');
	}
}