<?php

namespace App\Controller;

use App\Entity\School;
use App\Repository\BookingRepository;
use App\Repository\CourseRegistrationRepository;
use App\Utils\RepoContainer;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class SchoolPageController extends AbstractController
{

    public function __construct(
        private RepoContainer $rc
    )
    {
    }

    #[Route('/skola/{school}', name: 'school_page')]
    #[Template('school_page.html.twig')]
    public function showSchoolPage(School $school): array
    {
        $users = $this->rc->getUserRepo()->hasSchool($school)->getMatching();

        $qualifications = $this->rc->getQualificationRepo()->forUsers($users)->getMatching();

        $courseRegistrations = $this->rc->getCourseRegistrationRepo()->forUsers($users)->getMatching();
        $courseRegistrations = CourseRegistrationRepository::removeAlreadyQualified($courseRegistrations, $qualifications);
        $courseRegistrations = CourseRegistrationRepository::splitByTopic($courseRegistrations);

        $bookings = $this->rc->getBookingRepo()->getBookingsFromSchool($school);
        $bookings = BookingRepository::splitByPeriod($bookings);

        return [
            'users' => $users,
            'bookings' => $bookings,
            'qualifications' => $qualifications,
            'courseRegistrations' => $courseRegistrations,
            'formatted' => [
                'periods' => $this->rc->getPeriodRepo()->getFormattedPeriods(),
                'topics' => $this->rc->getTopicRepo()->getFormattedTopics()
            ]
        ];
    }
}
