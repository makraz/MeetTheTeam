<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * HomeController controller.
 */
class HomepageController extends Controller
{
	/**
	 * Homepage.
	 *
	 * @Route("/", name="app_homepage", methods={"GET"})
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @return Response
	 */
	public function index()
	{
		if ($this->isGranted('ROLE_USER')) {
			return $this->redirectToRoute('colleague_index');
		}

		return $this->render('homepage.html.twig');
	}
}
