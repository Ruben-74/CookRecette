<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController //\d+ (nombres) : + (repeté plusieurs fois)
{

    #[Route('/recettes', name: 'recipe.index')] //Une methode retourne tjr une reponse 
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response //objet request permet d'interagir avec la requete et obtenir des infos avec dd
    {
        $recipes = $repository->findDurationLowerThan(20);


        //Creer un objet 
        // $recipe = new Recipe();
        // $recipe->setTitle('barbe a papa')
        //     ->setSlug('barbe-papa')
        //     ->setContent('Mettez du sucre')
        //     ->setDuration(2)
        //     ->setCreatedAt(new \DateTimeImmutable())
        //     ->setUpdatedAt(new \DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();

        //Supprimer de la bdd
        // $em->remove($recipe[0]);
        // $em->flush();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }


    //localhost:8000/recette/pate-bolo-32
    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        //recherche par id 
        $recipe = $repository->find($id);

        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }

        //recherche par slug
        //$recipe = $repository->findOneBy(['slug' => $slug]);

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
