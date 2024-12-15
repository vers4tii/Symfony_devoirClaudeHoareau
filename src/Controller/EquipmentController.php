<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Repository\CharacterRepository;
use App\Service\EquipmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipment')]
class EquipmentController extends AbstractController
{
    public function __construct(
        private EquipmentService $equipmentService,
        private CharacterRepository $characterRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Affiche l'équipement d'un personnage spécifique
     */
    #[Route('/character/{id}', name: 'app_equipment_by_character', methods: ['GET'])]
    public function showCharacterEquipment(int $id): Response
    {
        $character = $this->characterRepository->find($id);
        
        if (!$character) {
            throw $this->createNotFoundException('Character not found');
        }

        if ($character->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $totalPower = $this->equipmentService->calculateTotalPower($character);

        return $this->render('equipment/character_equipment.html.twig', [
            'character' => $character,
            'equipment' => $character->getEquipment(),
            'totalPower' => $totalPower,
            'equipmentByType' => [
                'weapons' => $this->equipmentService->getCharacterEquipmentByType($character, 'weapon'),
                'armor' => $this->equipmentService->getCharacterEquipmentByType($character, 'armor'),
                'accessories' => $this->equipmentService->getCharacterEquipmentByType($character, 'accessory'),
            ],
        ]);
    }

    /**
     * Ajoute un équipement à un personnage
     */
    #[Route('/add/{characterId}', name: 'app_equipment_add', methods: ['GET', 'POST'])]
    public function addEquipment(Request $request, int $characterId): Response
    {
        $character = $this->characterRepository->find($characterId);
        
        if (!$character || $character->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Character not found');
        }

        $equipment = new Equipment();
        $equipment->setCharacter($character);

        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($equipment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Équipement ajouté avec succès !');
            return $this->redirectToRoute('app_equipment_by_character', ['id' => $characterId]);
        }

        return $this->render('equipment/add.html.twig', [
            'form' => $form,
            'character' => $character,
        ]);
    }

    /**
     * Supprime un équipement
     */
    #[Route('/{id}/delete', name: 'app_equipment_delete', methods: ['POST'])]
    public function delete(Request $request, Equipment $equipment): Response
    {
        if ($equipment->getCharacter()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$equipment->getId(), $request->request->get('_token'))) {
            $characterId = $equipment->getCharacter()->getId();
            $this->entityManager->remove($equipment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Équipement supprimé avec succès !');
            return $this->redirectToRoute('app_equipment_by_character', ['id' => $characterId]);
        }

        throw $this->createAccessDeniedException();
    }
}