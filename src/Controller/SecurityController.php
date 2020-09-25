<?php

namespace App\Controller;

use App\Entity\User;
use App\Validator\Constraints\ExistsValueInEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * HomeController controller.
 */
class SecurityController extends Controller
{
	/**
	 * @var CsrfTokenManagerInterface
	 */
	private CsrfTokenManagerInterface $csrfTokenManager;

	/**
	 * @var ValidatorInterface $validator
	 */
	private ValidatorInterface $validator;

	/**
	 * @var SessionInterface $session
	 */
	private SessionInterface $session;

	/**
	 * Security Controller constructor.
	 *
	 * @param CsrfTokenManagerInterface $csrfTokenManager
	 * @param ValidatorInterface $validator
	 * @param SessionInterface $session
	 */
	public function __construct(CsrfTokenManagerInterface $csrfTokenManager, ValidatorInterface $validator, SessionInterface $session)
	{
		$this->csrfTokenManager = $csrfTokenManager;
		$this->validator = $validator;
		$this->session = $session;
	}

	/**
	 * @Route("/identify", name="app_identify", methods={"POST"})
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function identifyUser(Request $request)
	{
		$csrfToken = $request->request->get('_csrf_token');
		$username = $request->request->get('username');

		$token = new CsrfToken('authenticate', $csrfToken);

		if (!$this->csrfTokenManager->isTokenValid($token)) {
			throw new InvalidCsrfTokenException();
		}

		$violations = $this->validator->validate($username, [
			new NotBlank(),
			new Email(),
			new ExistsValueInEntity([
				'field'       => 'email',
				'entityClass' => User::class,
			]),
		]);

		if (!is_string($username) || count($violations) !== 0) {
			return $this->redirectToRoute('app_homepage', [
				'username' => $username,
				'error'    => $violations[0]->getMessage(),
			]);
		}

		$this->session->set('identify_user_email', $username);

		return $this->redirectToRoute('app_login_page');
	}
}
