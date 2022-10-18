<?php


namespace SGTA\V1\Rpc\Auth;

use InvalidArgumentException;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\ResourceEvent;
use PHPUnit\Exception;
use SGTA\V1\Extenction\ExpiredToken;
use SGTA\V1\Extenction\InvalidTokenException;
use SGTA\V1\Extenction\UnauthorizedException;

class RestrictedResourceListener extends AbstractLocalResourceListener
{
    public function dispatch(ResourceEvent $event)
    {
        $this->event = $event;

        if (in_array($event->getName(), $this->publicMethods)){

            return $this->wrapped->dispatch($event);
        }

        try {
            $this->validateToken();

            return $this->wrapped->dispatch($event);

        } catch (ExpiredToken $e) {
            return new ApiProblem(401, $e->getMessage(), null, 'Expired Token', ['reason' => 'TOKEN_EXPIRED']);
        } catch (InvalidTokenException | InvalidArgumentException $e) {
            return new ApiProblem(401, 'Token inválido '.$e->getMessage());
        } catch (UnauthorizedException $e) {
            return new ApiProblem(403, $e->getMessage());
        }catch (Exception $e){
            return new ApiProblem(403, $e->getMessage());
        }
    }
}