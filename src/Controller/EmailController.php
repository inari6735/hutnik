<?php

namespace App\Controller;

use App\Entity\Email;
use App\Repository\EmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/admin/email', name: 'app_admin_email')]
    public function get_emails(
        EmailRepository $emailRepository
    ): Response
    {
        $emails = $emailRepository->findAll();
        $unregisteredEmails = $emailRepository->getUnregisteredEmails();

        return $this->render('email/emails.html.twig', [
            'emails' => $emails,
            'unregisteredEmails' => $unregisteredEmails
        ]);
    }

    #[Route('/admin/email/add', name: 'app_admin_email_add')]
    public function add_email(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $emailString = trim($request->query->get('email'));
        $withPerson = 0;
        if (str_ends_with($emailString, ' +1')) {
            $emailString = trim(substr($emailString, 0, -3));
            $withPerson = 1;
        }
        $email = new Email();
        $email->setEmail($emailString);
        $email->setWithPerson($withPerson);

        $entityManager->persist($email);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_email');
    }

    #[Route('/admin/email/{emailId}/remove', name: 'app_admin_email_remove')]
    public function remove_email(
        int $emailId,
        EmailRepository $emailRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $email = $emailRepository->findOneBy(['id' => $emailId]);
        $entityManager->remove($email);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_email');
    }
}
