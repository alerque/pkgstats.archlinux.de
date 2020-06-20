<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use SymfonyDatabaseTest\DatabaseTestCase;

class UserRepositoryTest extends DatabaseTestCase
{
    public function testGetSubmissionCountSince(): void
    {
        $userA = (new User())
            ->setPackages(2)
            ->setMirror('https://mirror.archlinux.de')
            ->setCountrycode('DE')
            ->setCpuarch('x86_64')
            ->setArch('x86_64')
            ->setTime(1234)
            ->setIp('localhost');
        $userB = (new User())
            ->setPackages(2)
            ->setMirror('https://mirror.archlinux.de')
            ->setCountrycode('DE')
            ->setCpuarch('x86_64')
            ->setArch('x86_64')
            ->setTime(12)
            ->setIp('localhost');
        $userC = (new User())
            ->setPackages(2)
            ->setMirror('https://mirror.archlinux.de')
            ->setCountrycode('DE')
            ->setCpuarch('x86_64')
            ->setArch('x86_64')
            ->setTime(1234)
            ->setIp('localhorst');
        $entityManager = $this->getEntityManager();
        $entityManager->persist($userA);
        $entityManager->persist($userB);
        $entityManager->persist($userC);
        $entityManager->flush();
        $entityManager->clear();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(User::class);
        $count = $userRepository->getSubmissionCountSince('localhost', 12);

        $this->assertEquals(2, $count);
    }

    public function testGetSubmissionCountSinceDefaultsToZero(): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(User::class);
        $count = $userRepository->getSubmissionCountSince('localhost', 12);

        $this->assertEquals(0, $count);
    }
}
