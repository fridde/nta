<?php

namespace App\Utils;

use App\Entity\Booking;
use App\Entity\Box;
use App\Entity\BoxStatusUpdate;
use App\Entity\CourseRegistration;
use App\Entity\Inventory;
use App\Entity\InventoryStatusUpdate;
use App\Entity\Item;
use App\Entity\Period;
use App\Entity\Qualification;
use App\Entity\School;
use App\Entity\Topic;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\BoxRepository;
use App\Repository\BoxStatusUpdateRepository;
use App\Repository\CourseRegistrationRepository;
use App\Repository\InventoryRepository;
use App\Repository\InventoryStatusUpdateRepository;
use App\Repository\ItemRepository;
use App\Repository\PeriodRepository;
use App\Repository\QualificationRepository;
use App\Repository\SchoolRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class RepoContainer
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getBookingRepo(): BookingRepository
    {
        return $this->em->getRepository(Booking::class);
    }

    public function getBoxRepo(): BoxRepository
    {
        return $this->em->getRepository(Box::class);
    }

    public function getBoxstatusUpdateRepo(): BoxstatusUpdateRepository
    {
        return $this->em->getRepository(BoxStatusUpdate::class);
    }

    public function getCourseRegistrationRepo(): CourseRegistrationRepository
    {
        return $this->em->getRepository(CourseRegistration::class);
    }

    public function getInventoryRepo(): InventoryRepository
    {
        return $this->em->getRepository(Inventory::class);
    }

    public function getInventoryStatusUpdateRepo(): InventoryStatusUpdateRepository
    {
        return $this->em->getRepository(InventoryStatusUpdate::class);
    }

    public function getItemRepo(): ItemRepository
    {
        return $this->em->getRepository(Item::class);
    }

    public function getPeriodRepo(): PeriodRepository
    {
        return $this->em->getRepository(Period::class);
    }

    public function getQualificationRepo(): QualificationRepository
    {
        return $this->em->getRepository(Qualification::class);
    }

    public function getSchoolRepo(): SchoolRepository
    {
        return $this->em->getRepository(School::class);
    }

    public function getTopicRepo(): TopicRepository
    {
        return $this->em->getRepository(Topic::class);
    }

    public function getUserRepo(): UserRepository
    {
        return $this->em->getRepository(User::class);
    }

}