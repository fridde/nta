<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Box;
use App\Entity\Period;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\PublicBookingFormType;
use App\Utils\ExtendedCollection;
use App\Utils\RepoContainer;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingFormController extends AbstractController
{
    public function __construct(
        private readonly RepoContainer $rc
    )
    {
    }

    #[Route('/boka/box')]
    #[Template('booking_form.html.twig')]
    public function bookBox(): array
    {
        /** @var User $user */
//        $user = $this->getUser();
        $user = $this->rc->getUserRepo()->find(3); // debugging: lars wikström, GALA
        $school = $user->getSchool();
        $users = $this->rc->getUserRepo()->hasSchool($school)->getMatching()->toArray();
        $futurePeriods = $this->rc->getPeriodRepo()->getFuturePeriods();

        $boxesLeft = $this->calculateNrOfBoxesLeft($this->getBoxesLeft($futurePeriods));

        $booking = new Booking();
        $booking->setPeriod($this->rc->getPeriodRepo()->getCurrentPeriod());

        $form = $this->createForm(PublicBookingFormType::class, $booking, [
            'users' => $users,
            'periods' => $futurePeriods->toArray(),
            'boxes_left' => $boxesLeft,
        ]);

        return ['form' => $form, 'boxes_left' => $boxesLeft];
    }

    private function getBoxesLeft(ExtendedCollection $periods): array
    {
       $topicIds = ExtendedCollection::create($this->rc->getTopicRepo()->findAll())
           //->filter(fn(Topic $t) => $t->needsBoxes())
           ->map(fn(Topic $t) => $t->getId())
           ->toArray();
       $topicTemplate = array_fill_keys($topicIds, []);
       $periodIds = $periods->map(fn(Period $p) => $p->getId())->toArray();
       $return = array_fill_keys($periodIds, $topicTemplate);
       $boxes = $this->rc->getBoxRepo()->findAll();

        foreach ($periods as $period) {
            /** @var Period $period */
            $periodId = $period->getId();
            foreach($boxes as $box) {
                /** @var Box $box */
                if(!$box->hasBooking($period)) {
                    $return[$periodId][$box->getTopic()->getId()][] = $box;
                }
            }
        }
        return $return;
    }

    private function calculateNrOfBoxesLeft(array $boxesLeft): array
    {
        $topicRepo = $this->rc->getTopicRepo();

        foreach ($boxesLeft as $period => $topics) {
            foreach ($topics as $topicId => $boxes) {
                $topic = $topicRepo->find($topicId);
                /** @var Topic $topic */
                $boxesLeft[$period][$topicId] = $topic->needsBoxes() ? count($boxes) : 999;  // some arbitrary high number
            }
        }

        return $boxesLeft;
    }
}
