<?php

namespace App\Controller;

use App\Entity\CourseRegistration;
use App\Entity\Qualification;
use App\Entity\School;
use App\Entity\User;
use App\Form\CourseRegistrationFormType;
use App\Utils\Coll;
use App\Utils\RepoContainer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseRegistrationFormController extends AbstractController
{
    public function __construct(
        private readonly RepoContainer          $rc,
        private readonly EntityManagerInterface $em,
    )
    {
    }


    #[Route('/boka/kurs', name: 'course_registration_form')]
    #[Template('course_registration_form.html.twig')]
    public function registerForCourse(Request $request): array|RedirectResponse
    {
        /** @var User $user */
//        $user = $this->getUser();
        $user = $this->rc->getUserRepo()->find(3); // debugging: lars wikström, GALA
        $school = $user->getSchool();
        $users = $this->rc->getUserRepo()->hasSchool($school)->getMatching();

        $form = $this->createForm(CourseRegistrationFormType::class, null, [
            'users' => $users->toArray(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('school_page', ['school' => $school]);
        }

        return [
            'form' => $form,
            'qualifications' => $this->getQualificationsAndRegistrations($users)
        ];
    }


    private function getQualificationsAndRegistrations(Coll $users): array
    {
        $return = [];

        $qualifications = $this->rc->getQualificationRepo()->forUsers($users)->getMatching();
        $courseRegistrations = $this->rc->getCourseRegistrationRepo()->forUsers($users)->getMatching();

        foreach ($qualifications as $qualification) {
            /** @var Qualification $qualification */
            $userId = $qualification->getUser()->getId();
            $return[$userId] ??= [];
            $return[$userId][] = $qualification->getTopic()->getId();
        }
        foreach ($courseRegistrations as $courseRegistration) {
            /** @var CourseRegistration $courseRegistration */
            $userId = $courseRegistration->getUser()->getId();
            $return[$userId] ??= [];
            $return[$userId][] = $courseRegistration->getTopic()->getId();
        }

        return $return;
    }
}
