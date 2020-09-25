<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Colleague Controller controller.
 */
class ColleagueController extends Controller
{
	/**
	 * Homepage.
	 *
	 * @Route("/colleagues", name="app_colleagues", methods={"GET"})
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @return Response
	 */
	public function index()
	{
		return $this->render('homepage.html.twig');
	}
}
