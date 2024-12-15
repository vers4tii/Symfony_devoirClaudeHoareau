<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Service\CharacterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/character')]
#[IsGranted('ROLE_USER')]
class CharacterController extends AbstractController
{
    public function __construct(
        private CharacterService $characterService
    ) {}

    /**
     * Liste tous les personnages de l'utilisateur connecté
     */
    #[Route('/', name: 'app_character_index', methods: ['GET'])]
    public function index(): Response
    {
        $characters = $this->characterService->getUserCharacters($this->getUser());

        return $this->render('character/index.html.twig', [
            'characters' => $characters,
        ]);
    }

    /**
     * Crée un nouveau personnage
     */
    #[Route('/new', name: 'app_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterService->createCharacter(
                $character->getName(),
                $character->getArcana(),
                $this->getUser()
            );

            $this->addFlash('success', 'Personnage créé avec succès !');
            return $this->redirectToRoute('app_character_index');
        }

        return $this->render('character/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Affiche les détails d'un personnage
     */
    #[Route('/{id}', name: 'app_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        $this->denyAccessUnlessGranted('view', $character);

        return $this->render('character/show.html.twig', [
            'character' => $character,
        ]);
    }

    /**
     * Modifie un personnage existant
     */
    #[Route('/{id}/edit', name: 'app_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character): Response
    {
        $this->denyAccessUnlessGranted('edit', $character);

        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterService->updateCharacter($character);
            
            $this->addFlash('success', 'Personnage modifié avec succès !');
            return $this->redirectToRoute('app_character_show', ['id' => $character->getId()]);
        }

        return $this->render('character/edit.html.twig', [
            'form' => $form,
            'character' => $character,
        ]);
    }

    /**
     * Supprime un personnage
     */
    #[Route('/{id}/delete', name: 'app_character_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character): Response
    {
        $this->denyAccessUnlessGranted('delete', $character);

        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->request->get('_token'))) {
            $this->characterService->deleteCharacter($character);
            
            $this->addFlash('success', 'Personnage supprimé avec succès !');
            return $this->redirectToRoute('app_character_index');
        }

        throw $this->createAccessDeniedException();
    }

    /**
     * Augmente le niveau d'un personnage
     */
    #[Route('/{id}/level-up', name: 'app_character_level_up', methods: ['POST'])]
    public function levelUp(Request $request, Character $character): Response
    {
        $this->denyAccessUnlessGranted('edit', $character);

        if ($this->isCsrfTokenValid('level-up'.$character->getId(), $request->request->get('_token'))) {
            $this->characterService->levelUp($character);
            
            $this->addFlash('success', 'Niveau augmenté avec succès !');
            return $this->redirectToRoute('app_character_show', ['id' => $character->getId()]);
        }

        throw $this->createAccessDeniedException();
    }
}