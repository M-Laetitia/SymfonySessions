<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ValidationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isValidId($id, $entityClassName)
    {
        // Vérifie si l'ID est un entier positif
        if (!is_numeric($id) || intval($id) <= 0) {
            return false;
        }

        // Vérifie si l'ID existe dans la table correspondante
        $repository = $this->entityManager->getRepository($entityClassName);
        $entity = $repository->find($id);

        // bool qui renvoie true si l'entité est trouvée dans la DB  et false si cas échéant.
        return $entity !== null;
    }
}

?>