<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Box;
use App\Entity\CourseRegistration;
use App\Entity\Inventory;
use App\Entity\InventoryStatusUpdate;
use App\Entity\Item;
use App\Entity\Period;
use App\Entity\Qualification;
use App\Entity\School;
use App\Entity\BoxStatusUpdate;
use App\Entity\Topic;
use App\Entity\User;

use App\Enums\InventoryType;
use App\Enums\Role;
use App\Enums\UpdateType;
use App\Repository\BookingRepository;
use App\Repository\BoxRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;


class AppFixtures extends Fixture
{

    private ObjectManager $om;

    public function __construct(
        private LoggerInterface           $logger,
        private readonly MaterialImporter $materialImporter
    )
    {
    }

    private static array $specialCases = [
        'User' => ['Roles'],
        'Booking' => ['BoxOwner', 'Booker'],
        'Item' => ['OrderInfo', 'Category', 'StaffInfo'],
        'Inventory' => ['InventoryType'],
        'BoxStatusUpdate' => ['Type']
    ];

    private static array $convertToEntity = [
        User::class,
        Box::class,
        Topic::class,
        School::class,
        Period::class,
        Item::class,
        Inventory::class
    ];

    private static array $manyToMany = [
        'boxes_bookings'
    ];

    private static array $convertToDate = [
        'Date', 'LastUpdate', 'Created', 'StartDate', 'EndDate'
    ];

    private array $shortToLong = [];


    public function load(ObjectManager $manager): void
    {


        $this->om = $manager;

        $reader = IOFactory::createReader('Ods');
        $reader->setReadDataOnly(true);
        $workbook = $reader->load(__DIR__ . '/test_data_nta.ods');
        $sheets = $workbook->getAllSheets();

        /** @var EntityManagerInterface $em */
        $em = $this->om;
        $conn = $em->getConnection();
        $conn->setAutoCommit(false);

//        try {
//            // @$conn->executeQuery('TRUNCATE recordings;');
//        } catch (\Exception $e) {
//
//        }

        foreach ($sheets as $sheetObject) {
            $title = $sheetObject->getTitle();

            if (self::isIgnored($title)) {
                continue;
            }
            $rows = $sheetObject->toArray();
            $headers = array_shift($rows);
            $rows = array_map(fn($r) => array_combine($headers, $r), $rows);

            foreach ($rows as $row) {
                //dump(json_encode($row));
                if (in_array($title, self::$manyToMany, true)) {
                    $this->combineManyToMany($row, $title);
                    continue;
                }

                $createMethod = 'create' . $title;
                $entity = $this->$createMethod($row);

                $specialCases = self::$specialCases[$title] ?? [];
                $this->setStandardValues($entity, $row, $specialCases);

                $this->om->persist($entity);

                $metadata = $this->om->getClassMetaData(get_class($entity));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->om->flush();
        }

        $this->materialImporter->run();
    }

    // ##############################################################################

    private function createUser(array $row): User
    {
        $u = new User();

        $roles = $row['Roles'];
        $roles = empty($roles) ? [] : explode(',', $roles);
        foreach ($roles as $role) {
            $u->addRole(Role::from($role));
        }

        return $u;
    }

    private function createBox(array $row): Box
    {
        return new Box();
    }

    private function createTopic(array $row): Topic
    {
        return new Topic();
    }

    private function createSchool(array $row): School
    {
        return new School();
    }

    private function createPeriod(array $row): Period
    {
        return new Period();
    }

    private function createQualification(array $row): Qualification
    {
        return new Qualification();
    }

    private function createBooking(array $row): Booking
    {
        $b = new Booking();
        $userRepo = $this->om->getRepository(User::class);

        $b->setBoxOwner($userRepo->find($row['BoxOwner']));
        if ($row['Booker']) {
            $b->setBooker($userRepo->find($row['Booker']));
        }

        return $b;
    }

    private function createItem(array $row): Item
    {
        $i = new Item();

        $i->setOrderInfo(json_decode($row['OrderInfo'], associative: true));
        $i->setStaffInfo(json_decode($row['StaffInfo'], associative: true));

        return $i;
    }

    private function createInventory(array $row): Inventory
    {
        $i = new Inventory();
        $itemRepo = $this->om->getRepository(Item::class);
        $i->setItem($itemRepo->find($row['Item']));

        $topicRepo = $this->om->getRepository(Topic::class);
        $i->setTopic($topicRepo->find($row['Topic']));

        $i->setInventoryType(InventoryType::from((int)$row['InventoryType']));

        return $i;
    }

    private function createBoxStatusUpdate(array $row): BoxStatusUpdate
    {
        $s = new BoxStatusUpdate();
        $s->setType(UpdateType::from($row['Type']));

        return $s;
    }

    private function createInventoryStatusUpdate(array $row): InventoryStatusUpdate
    {
        return new InventoryStatusUpdate();
    }

    private function createCourseRegistration(array $row): CourseRegistration
    {
        return new CourseRegistration();
    }

    // ##############################################################################

    private function combineManyToMany(array $row, string $table): void
    {
        switch ($table) {
            case 'boxes_bookings':
                /** @var BoxRepository $boxRepo */
                $boxRepo = $this->om->getRepository(Box::class);
                /** @var BookingRepository $bookingRepo */
                $bookingRepo = $this->om->getRepository(Booking::class);
                $box = $boxRepo->find($row['Box']);
                $booking = $bookingRepo->find($row['Booking']);
                if ($booking !== null) {
                    $box->addBooking($booking);
                }

                break;
        }
    }

    private function setStandardValues($object, $row, $exceptions = []): void
    {
        $this->setShortToLongArray();   // =>  ['User' => '\App\Entity\User', ...]

        $shortNames = array_keys($this->shortToLong);


        foreach ($row as $header => $value) {
            if (self::isIgnored($header) || in_array($header, $exceptions, true)) {
                continue;
            }
            if (in_array($header, $shortNames, true)) {

                $repo = $this->om->getRepository($this->shortToLong[$header]);
                if ($value !== null) {
                    $value = $repo->find($value);
                }

            }
            if (in_array($header, self::$convertToDate, true)) {
                $value = empty($value) ? null : new Carbon($value);
            }
            $setterMethod = 'set' . ucfirst($header);
            $object->$setterMethod($value);
        }
    }

    private static function isIgnored(string $fieldOrTitle): bool
    {
        return str_contains($fieldOrTitle, '_ignore');
    }

    private function setShortToLongArray(): void
    {
        if (!empty($this->shortToLong)) {
            return;
        }
        foreach (self::$convertToEntity as $val) {
            $key = (new ReflectionClass($val))->getShortName();
            $this->shortToLong[$key] = $val;
        }
    }


}
