<?php


namespace SGTA\V1\Rpc\Auth;


use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\View\Helper\Placeholder\Container;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Plain;
use PHPUnit\Exception;
use Psr\Container\ContainerInterface;
use SGTA\V1\Extenction\ExpiredToken;
use SGTA\V1\Extenction\InvalidTokenException;
use SGTA\V1\Extenction\UnauthorizedException;
use SGTA\V1\Rest\Utilizador\UtilizadorEntity;


abstract class AbstractLocalResourceListener extends AbstractResourceListener implements TokenRestrictedInterface
{
    protected AbstractResourceListener $wrapped;
    protected Container $session;
    protected array $allowedUserTypes;
    protected ContainerInterface $serviceLocator;
    protected array $publicMethods;
    protected ?Plain $token;
    private Configuration $config;

    public function __construct(
        AbstractResourceListener $wrapped,
        ContainerInterface $serviceLocator,
        Configuration $config,
        array $publicMethods = []
    ) {
        $this->wrapped = $wrapped;
        $this->serviceLocator = $serviceLocator;
        $this->publicMethods = $publicMethods;

        $this->session = new Container();
        $this->allowedUserTypes = [];
        $this->token = null;
        $this->config = $config;

        $this->initRepositories();
    }

    public function extractBearerToken(string $authorizationHeader): Plain
    {

        try {
            $authToken = explode(' ', $authorizationHeader);
            if ($authToken[0] !== "Bearer") {
                throw new InvalidArgumentException();
            }

            if (!$authToken[1]) {
                throw new InvalidArgumentException();
            }

            $parsedToken = $this->config->parser()->parse($authToken[1]);

            if ($parsedToken instanceof Plain) {
                return $parsedToken;
            }

            throw new InvalidArgumentException('Token inválido.');
        } catch (Exception) {
            throw new InvalidArgumentException();
        }
    }

    public function isTokenValid(Token $token): bool
    {
        $constraints = $this->config->validationConstraints();
        if (!$this->config->validator()->validate($token, ...$constraints)) {
            return false;
        }

        if ($token->isExpired(new \DateTimeImmutable()) || $this->isTokenFromCurrentYear($token)) {
            throw new ExpiredToken('Token expirado.');
        }

        return true;
    }

    public function isAuthorized(Token $token): bool
    {
        $utypeClaim = $token->claims()->get('utype');
        return !(!$utypeClaim || !in_array($utypeClaim, $this->allowedUserTypes, true));
    }

    public function addUserType(string $userType): void
    {
        $this->allowedUserTypes[] = strtoupper($userType);
    }

    protected function storeTokenData(Token $token): void
    {
        $claims = $token->claims();
        $this->event->setParam('userId', $claims->get('uid'));
        $utype = $claims->get('utype');
        $this->event->setParam('userType', strtoupper($utype));


        $this->session->userId = $claims->get('uid');
        $this->session->userType = strtoupper($claims->get('utype'));
        $this->session->source = $claims->get('client');
    }


    protected function validateToken(): void
    {
        try{
            $authorization = $this->getEvent()->getRequest()->getHeader('Authorization');

            if (!$authorization) {

                throw new InvalidTokenException('Autenticação necessária.');
            }


            $token = $this->extractBearerToken($authorization->getFieldValue());

            $this->token = $token;

            $this->storeTokenData($token);

            if (!$this->isTokenValid($token)) {
                throw new InvalidTokenException('Token inválido.');
            }

            /*if (!$this->isAuthorized($token)) {
                throw new UnauthorizedException('Não autorizado.');
            }
            */


        }catch (Exception $ex){
            echo $ex->getMessage();
        }



    }

    private function initRepositories(): void
    {
        $em = $this->serviceLocator->get(EntityManager::class);
        $this->userRep = $em->getRepository(UtilizadorEntity::class);
    }

    private function isTokenFromCurrentYear(Token $token): bool
    {
        /** @var \DateTimeInterface $iat */
        $iat = $token->claims()->get('iat');

        $yearOfIssue = (int)$iat->format('Y');
        $currentYear = (int)date('Y');

        return $yearOfIssue !== $currentYear;
    }

}