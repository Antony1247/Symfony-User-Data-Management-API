<?php

namespace App\Controller;

use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Entity\User;
use App\Service\UserCsvParser;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class ApiController extends AbstractController
{
    private UserCsvParser $csvParser;
    // private BackupService $backupService;
    private EmailService $emailService;
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $messageBus;

    public function __construct(
        UserCsvParser $csvParser,
        MessageBusInterface $messageBus,
        EmailService $emailService,
        EntityManagerInterface $entityManager
    ) {
        $this->csvParser = $csvParser;
        $this->emailService = $emailService;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    #[Route('/api/upload', methods: ['POST','GET'])]
    public function uploadData(Request $request): JsonResponse
    {
        $file = $request->files->get('file');
        if (!$file || $file->getClientOriginalExtension() !== 'csv') {
            return new JsonResponse(['error' => 'Invalid file format'], 400);
        }

        $filePath = $file->getPathname();
        $users = $this->csvParser->parseAndSaveUsers($filePath);

        foreach ($users as $user) {
            // $this->emailService->sendUserNotificationEmail($user);
            $this->messageBus->dispatch(new SendEmailMessage($user->getEmail(), $user->getName()));
        }

        return new JsonResponse(['message' => 'Users uploaded and emails sent successfully']);
    }

    #[Route('/api/users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $data = array_map(function ($user) {
            return [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'address' => $user->getAddress(),
                'role' => $user->getRole(),
            ];
        }, $users);

        return new JsonResponse($data);
    }






    #[Route('/api/backup', name: 'backup_database', methods: ['GET'])]
    public function backupDatabase(): JsonResponse
    {
        $backupFile = '/Users/antonyjalappat/Downloads/backup.sql';
        $command = "mysqldump -u root --host=127.0.0.1 user_api > $backupFile 2>&1";
        exec($command, $output, $returnVar);
    
        if ($returnVar !== 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'Failed to create database backup', 'output' => implode("\n", $output)], 500);
        }
    
        return new JsonResponse(['status' => 'success', 'message' => 'Database backup created']);
    }
    
    #[Route('/api/restore', name: 'restore_database', methods: ['POST'])]
    public function restoreDatabase(): JsonResponse
    {
        $backupFile = '/Users/antonyjalappat/Downloads/backup.sql';
        $command = "mysql -u root --host=127.0.0.1 user_api < $backupFile 2>&1";
        exec($command, $output, $returnVar);
    
        if ($returnVar !== 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'Failed to restore database from backup', 'output' => implode("\n", $output)], 500);
        }
    
        return new JsonResponse(['status' => 'success', 'message' => 'Database restored from backup']);
    }








    #[Route('/manage', name: 'manage_view', methods: ['GET'])]
    public function manageView(): Response
    {
        return $this->render('actions.html.twig');
    }
    #[Route('/users', name: 'user_list')]
    public function userList(EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();
    
        $data = array_map(function ($user) {
            return [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
            ];
        }, $users);
    
        return $this->render('users.html.twig', ['users' => $data]);
    }
}
