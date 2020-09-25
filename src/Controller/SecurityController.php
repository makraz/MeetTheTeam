<?php

namespace App\Controller;

use App\Entity\User;
use App\Validator\Constraints\ExistsValueInEntity;
use DateInterval;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * HomeController controller.
 */
class SecurityController extends Controller
{
	/**
	 * @var ValidatorInterface $validator
	 */
	private ValidatorInterface $validator;

	/**
	 * @var SessionInterface $session
	 */
	private SessionInterface $session;

	/**
	 * @var AdapterInterface $cache
	 */
	private AdapterInterface $cache;

	/**
	 * @var MailerInterface $mailer
	 */
	private MailerInterface $mailer;

	/**
	 * Security Controller constructor.
	 *
	 * @param ValidatorInterface $validator
	 * @param SessionInterface $session
	 * @param AdapterInterface $cache
	 * @param MailerInterface $mailer
	 */
	public function __construct(
		ValidatorInterface $validator,
		SessionInterface $session,
		AdapterInterface $cache,
		MailerInterface $mailer
	)
	{
		$this->validator = $validator;
		$this->session = $session;
		$this->cache = $cache;
		$this->mailer = $mailer;
	}

	/**
	 * @Route("/identify", name="app_identify", methods={"POST"})
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws TransportExceptionInterface
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function identifyUser(Request $request)
	{
		$csrfToken = $request->request->get('_csrf_token');
		$email = $request->request->get('username');

		if (!$this->isCsrfTokenValid('authenticate', $csrfToken)) {
			throw new InvalidCsrfTokenException();
		}

		$violations = $this->validator->validate($email, [
			new NotBlank(),
			new EmailConstraint(),
			new ExistsValueInEntity([
				'field'       => 'email',
				'entityClass' => User::class,
			]),
		]);

		if (!is_string($email) || count($violations) !== 0) {
			return $this->redirectToRoute('app_homepage', [
				'username' => $email,
				'error'    => $violations[0]->getMessage(),
			]);
		}

		$password = md5(uniqid()); // Generate a random string
		$cacheKey = md5($email); // email hash as a key

		$item = $this->cache->getItem($cacheKey);
		$item->set($password);
		$item->expiresAfter(new DateInterval('PT20M')); // the item will be cached for 20 minutes
		$this->cache->save($item);

		$this->session->set('identify_user_email', $email);

		$emailMessage = (new TemplatedEmail())
			->from(new Address('noreply@meettheteam.com', 'Meet The Team'))
			->to($email)
			->priority(Email::PRIORITY_HIGH)
			->subject('Your password')
			->htmlTemplate('emails/password.html.twig')
			->textTemplate('emails/password.txt.twig')
			->context([
				'emailAddress' => $email,
				'password'     => $password,
			]);

		$this->mailer->send($emailMessage);

		return $this->redirectToRoute('app_login');
	}

	/**
	 * Homepage.
	 *
	 * @Route("/login", name="app_login", methods={"GET", "POST"})
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		if ($this->isGranted('ROLE_USER')) {
			return $this->redirectToRoute('colleague_index');
		}

		if (!$this->session->has('identify_user_email')) {
			throw new AccessDeniedHttpException();
		}

		// Get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		return $this->render('login.html.twig', [
			'error' => $error,
		]);
	}

	/**
	 * @Route("/logout", name="app_logout")
	 */
	public function logout(): Response
	{
		// controller can be blank: it will never be executed!
	}
}
