<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // DEBUG - AJOUTEZ CETTE SECTION
        if ($form->isSubmitted()) {
            echo '<pre style="background: #f0f0f0; padding: 20px;">';
            echo "=== DEBUG FORMULAIRE INSCRIPTION ===\n";
            echo "isSubmitted: " . ($form->isSubmitted() ? 'YES' : 'NO') . "\n";
            echo "isValid: " . ($form->isValid() ? 'YES' : 'NO') . "\n\n";

            if (!$form->isValid()) {
                echo "❌ ERREURS DE VALIDATION:\n";
                foreach ($form->getErrors(true) as $error) {
                    echo "- " . $error->getMessage() . "\n";
                }

                echo "\nErreurs par champ:\n";
                echo "- Email: " . count($form->get('email')->getErrors()) . " erreurs\n";
                echo "- Name: " . count($form->get('name')->getErrors()) . " erreurs\n";
                echo "- Password: " . count($form->get('plainPassword')->getErrors()) . " erreurs\n";
            } else {
                echo "✅ FORMULAIRE VALIDE!\n\n";
                echo "Données:\n";
                echo "- Email: " . $user->getEmail() . "\n";
                echo "- Name: " . $user->getName() . "\n";
                echo "- PlainPassword: " . ($form->get('plainPassword')->getData() ? 'SET' : 'NOT SET') . "\n";

                // Essayez de sauvegarder
                try {
                    $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                    $user->setPassword($hashedPassword);
                    $user->setRoles(['ROLE_USER']);

                    $entityManager->persist($user);
                    $entityManager->flush();

                    echo "\n🎉 UTILISATEUR SAUVEGARDÉ AVEC ID: " . $user->getId() . "\n";
                } catch (\Exception $e) {
                    echo "\n💥 ERREUR LORS DE LA SAUVEGARDE: " . $e->getMessage() . "\n";
                }
            }
            echo '</pre>';
            exit;
        }
        // FIN DEBUG

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
