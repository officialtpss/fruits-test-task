<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\FavouriteFruit;
use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/api", name="api_")
 */
class FavouriteFruitsController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/favorite-fruits", name="app_favorite_fruits", methods={"GET"})
     */

    public function index(): Response
    {
        $user = $this->getUser();

        $favourites = $this->entityManager->getRepository(FavouriteFruit::class)->findFavByUserId($user);

        $favouriteFruits = $this->entityManager->getRepository(Fruit::class)->findBy(['id' => $favourites]);

        return $this->json(['message' => 'Fruits loaded Successfully', 'data' => ['favouriteFruits' => $favouriteFruits]]);
    }

    /**
     * @Route("/add-to-favorite", name="app_add_to_favorite", methods={"POST"})
     */

    public function addToFav(Request $request): Response
    {
        $decoded = json_decode($request->getContent());

        $fruitId = (int)(isset($decoded->fruitId))?$decoded->fruitId:0;

        if($fruitId == 0){
            return $this->json(['error' => 'Fruit Id Invalid'], 400);
        }

        $user = $this->getUser();
        $fruit = $this->entityManager->getRepository(Fruit::class)->find($fruitId);

        /* check if Fruit is already in favourite table */
        $record = $this->entityManager->getRepository(FavouriteFruit::class)->findByUserId($user,$fruit);

        if(is_object($record)){

            // remove from fav
            $this->entityManager->getRepository(FavouriteFruit::class)->remove($record,true);
            $message = "Removed from Favourite";
        }else{

            // check 10 limit
            $records = $this->entityManager->getRepository(FavouriteFruit::class)->findCountByUser($user);

            if($records < 10){
                // add to fav
                $favFruit = $this->entityManager->getRepository(FavouriteFruit::class)->createNew($user,$fruit);
                $this->entityManager->persist($favFruit);
                $this->entityManager->flush();

                $message = "Added to Favourite";
            }else{
                return $this->json([ 'error' => 'Only 10 Favourites are allowed'], 400);
            }
        }

        return $this->json(['message' => $message]);
    }

}
