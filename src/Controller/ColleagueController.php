<?php

namespace App\Controller;

use App\Entity\Colleague;
use App\Form\ColleagueType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/colleagues")
 */
class ColleagueController extends AbstractController
{
	/**
	 * @Route("/", name="colleague_index", methods={"GET"})
	 *
	 * @return Response
	 */
	public function index(): Response
	{
		$colleagues = $this
			->getDoctrine()
			->getRepository(Colleague::class)
			->findBy(['user' => $this->getUser()->getId()]);

		return $this->render('colleague/index.html.twig', [
			'colleagues' => $colleagues,
		]);
	}

	/**
	 * @Route("/new", name="colleague_new", methods={"GET","POST"})
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function new(Request $request): Response
	{
		$colleague = new Colleague();
		$form = $this->createForm(ColleagueType::class, $colleague);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$colleague->setUser($this->getUser());

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($colleague);
			$entityManager->flush();

			return $this->redirectToRoute('colleague_index');
		}

		return $this->render('colleague/new.html.twig', [
			'colleague' => $colleague,
			'form'      => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="colleague_show", methods={"GET"})
	 *
	 * @param Colleague $colleague
	 *
	 * @return Response
	 */
	public function show(Colleague $colleague): Response
	{
		if ($colleague->getUser()->getId() !== $this->getUser()->getId()) {
			throw new AccessDeniedHttpException();
		}
		
		return $this->render('colleague/show.html.twig', [
			'colleague' => $colleague,
		]);
	}

	/**
	 * @Route("/{id}/edit", name="colleague_edit", methods={"GET","POST"})
	 *
	 * @param Request $request
	 * @param Colleague $colleague
	 *
	 * @return Response
	 */
	public function edit(Request $request, Colleague $colleague): Response
	{
		if (!$colleague->getUser() || $colleague->getUser()->getId() !== $this->getUser()->getId()) {
			throw new AccessDeniedHttpException();
		}

		$form = $this->createForm(ColleagueType::class, $colleague);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('colleague_index');
		}

		return $this->render('colleague/edit.html.twig', [
			'colleague' => $colleague,
			'form'      => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="colleague_delete", methods={"DELETE"})
	 *
	 * @param Request $request
	 * @param Colleague $colleague
	 *
	 * @return Response
	 */
	public function delete(Request $request, Colleague $colleague): Response
	{
		if ($colleague->getUser()->getId() !== $this->getUser()->getId()) {
			throw new AccessDeniedHttpException();
		}

		if ($this->isCsrfTokenValid('delete' . $colleague->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($colleague);
			$entityManager->flush();
		}

		return $this->redirectToRoute('colleague_index');
	}
}
