<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * HomeController controller.
 */
class HomepageController extends Controller
{
	/**
	 * @var SessionInterface $session
	 */
	private SessionInterface $session;

	/**
	 * Homepage Controller constructor.
	 *
	 * @param SessionInterface $session
	 */
	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

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
		return $this->render('homepage.html.twig');
	}
}
