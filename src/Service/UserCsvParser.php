<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserCsvParser
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function parseAndSaveUsers(string $filePath): array
    {
        $users = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Skip header row
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Validate required fields
                if (empty($data[1]) || empty($data[2])) {
                    continue; // Skip this row if email or username is empty
                }

                // Check for existing user
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data[1]]);
                if ($existingUser) {
                    continue; // Skip this row if the user already exists
                }

                $user = new User();
                $user->setName($data[0]);
                $user->setEmail($data[1]);
                $user->setUsername($data[2]);
                $user->setAddress($data[3]);
                $user->setRole($data[4]);
                $this->entityManager->persist($user);
                $users[] = $user;
            }
            fclose($handle);
            $this->entityManager->flush();
        }
        return $users;
}
}
