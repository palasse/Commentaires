<?php
namespace App\Controller;

use App\Service\SessionService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class GoogleAuthController extends AbstractController
{
    private $Httpclient;

    public function __construct(HttpClientInterface $Httpclient)
    {
        $this->Httpclient = $Httpclient;
    }


    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect_google")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'openid' // the scopes you want to access
            ]);
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     */
    #[Route("/connect/google/check", name:"connect_google_check")]
    public function connectCheckAction( ClientRegistry $clientRegistry, SessionService $sessionService)
    {
        $session = $sessionService->getSession();
        $client = $clientRegistry->getClient('google');
        $adressAPI = $this->getParameter("app.api_uri");

        if (!$session){
            new Session(new NativeSessionStorage(), new AttributeBag());
        }

        try {
            $user = $client->fetchUser();

            // Récupération Token JWT depuis le serveur
            $response = $this->Httpclient->request('POST', $adressAPI.'/login', [
                'verify_peer' => false,
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'mail' => $user->getEmail(),
                    'token' => $user->toArray()["sub"],
                ],
            ]);
            //Sauvegarde Token
            $session->set('token', $response->getContent());

            return $this->redirectToRoute("app_home_showhome");
        } catch (IdentityProviderException $e) {
            var_dump($e->getMessage()); die;
        }
    }
}
