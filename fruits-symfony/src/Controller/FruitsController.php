<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Fruit;
use App\Entity\FavouriteFruit;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/api", name="api_")
 */
class FruitsController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/fruits", name="fruits", methods={"POST"})
     */
    public function index(Request $request): Response
    {

        // get filters
        $filters = json_decode($request->getContent());

        $page = $filters->page;
        $limit = $filters->limit;

        // calculate offest
        $offset = 0;
        if ($page != 0 && $page != 1) {
            $offset = ($page - 1) * $limit;
        }

        $user = $this->getUser();
        $fruits =  $this->entityManager->getRepository(Fruit::class)->findByNameFamilyFilter($user,$filters, $limit, $offset);
        $favourites = $this->entityManager->getRepository(FavouriteFruit::class)->findFavByUserId($user);
        $total = $this->entityManager->getRepository(Fruit::class)->findByNameFamilyFilterCount($filters);

        return $this->json(['message' => 'Fruits loaded Successfully', 'data' => ['page' => $page, 'limit' => $limit, 'total' => $total, 'fruits' => $fruits, 'favourites' => $favourites]]);

    }
    
}
