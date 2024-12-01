<?php 

namespace App\DataFixtures;

use App\Entity\Box;
use App\Entity\School;
use App\Entity\Topic;
use App\Entity\User;

use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class AppFixtures extends Fixture 
{
    private ObjectManager $om;

    public function __construct(private LoggerInterface $logger)
    {

    }

    private static array $specialCases = [
        'CalendarEvent' => ['_Location'],
        'User' => ['Roles'],
        'Visit' => ['Colleagues']
    ];

    private static array $convertToEntity = [
        //User::class,
        Box::class,
        Topic::class,
        School::class
    ];

    private static array $manyToMany = [        
    ];

    private static array $convertToDate = [
        'Date', 'LastUpdate', 'Created'
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

    }

    private function createUser(array $row): User
    {
        $u = new User();
//        $roles = $row['Roles'] ?? [];
//        if (!empty($roles)) {
//            //$u->addRoles(explode(',', $roles));
//        }

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

    private function combineManyToMany(array $row, string $table): void
    {
        switch ($table) {
            case 'XYZ':

                break;
        }
    }

    private function setStandardValues($object, $row, $exceptions = []): void
    {
        $this->setShortToLongArray();

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
                $value = empty($value) ? null : new \DateTime($value);
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
