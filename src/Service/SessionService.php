<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class SessionService
{
    public function __construct(
        private RequestStack $requestStack, 
        private SessionRepository $sessionRepo ,
        private PaginatorInterface $paginator, 
    ) {

    }

    public function getPaginatorArticles(?session $session = null) {
        $request = $this->requestStock->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 2;
        $sessionQuery = $this->sessionRepo->findForPagination($session);

        return $this->paginator->paginate($sessionQuery, $page, $limit);

    }
}

?>