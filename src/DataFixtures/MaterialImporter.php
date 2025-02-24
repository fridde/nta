<?php

namespace App\DataFixtures;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Entity\Topic;
use App\Enums\InventoryType;
use App\Utils\RepoContainer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MaterialImporter
{
    private const string DATA_URL = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRCydR3_LzWvq0xk5yoniKvlPHNZPI1D5O0vGSNy1iZNlQFEZJOLFB7Ei1fe7AijduTlWO3umOAoTHP/pub?gid=1360401760&single=true&output=csv';

    public function __construct(
        private readonly RepoContainer       $rc,
        private readonly HttpClientInterface $httpClient,
        private readonly SerializerInterface $serializer,
        private LoggerInterface              $logger,
    )
    {
    }

    public function getData(): array
    {
        $response = $this->httpClient->request('GET', self::DATA_URL);

        return $this->serializer->decode($response->getContent(), 'csv');
    }

    public function run(): void
    {

        foreach ($this->getData() as $rowNr => $row) {
            if (self::isEmptyRow($row)) {
                continue;
            }

            $item = new Item();
            if (empty($row['id'])) {
                $this->logger->info("Missing id for row " . $rowNr);
            }
            $this->logger->info("Row " . $row['id']);
            $item->setId($row['id']);
            $item->setPlacement($row['Plats']);
            $item->setDetailedLabel($row['Label_intern']);
            $item->setSimpleLabel($row['Label_user']);
            $item->setStaffInfo([$row['kommentar till packaren']]);

            $orderInfo = $this->createOrderInfo($row['artikelnr på NTM'], $row['kommentar till beställare']);
            $item->setOrderInfo($orderInfo);
            $item->setUserInfo($row['kommentar till läraren']);
            $this->rc->getEntityManager()->persist($item);

            $stock = new Inventory(InventoryType::STOCKROOM);
            $stock->setItem($item);
            $stock->setQuantity((int) $row['Antal lagret']);
            $this->rc->getEntityManager()->persist($stock);

            $topicIds = self::getSplitValues($row, 'Låda');
            $ranks = self::getSplitValues($row, 'Rank');
            $boxAmounts = self::getSplitValues($row, 'Antal i lådan');
            $extraAmounts = self::getSplitValues($row, 'Förbrukning');

//            if($row['id'] === 'URM'){
//                $this->logger->info(print_r($extraAmounts, true));
//            }

            foreach ($topicIds as $index => $topicId) {
                $inventory = new Inventory(InventoryType::BOX);


                /** @var Topic $topic */
                $topic = $this->rc->getTopicRepo()->find($topicId);
                $this->logger->info(get_class($topic));
                $inventory->setTopic($topic);

                $inventory->setItem($item);
                $inventory->setQuantity((int) $boxAmounts[$index]);
                $inventory->setListRank($ranks[$index]);
                $this->rc->getEntityManager()->persist($inventory);


                if(!empty($extraAmounts) && (int) $extraAmounts[$index] > 0) {
                    $extraInventory = new Inventory(InventoryType::EXTRA_MATERIAL);

                    $extraInventory->setItem($item);
                    $extraInventory->setQuantity((int) $extraAmounts[$index]);
                    $extraInventory->setTopic($topic);
                    $this->rc->getEntityManager()->persist($extraInventory);
                }
            }
        }
        $this->rc->getEntityManager()->flush();
    }

    public function createOrderInfo(string $ntMateriel, string $orderInfo): array
    {

        $return = ['ntm' => array_map(
            fn(string $v) => mb_strtolower(trim(str_replace('-', '', $v))),
            explode(';', $ntMateriel)
        )];
        if($orderInfo !== ""){
//            $this->logger->debug("ORDER INFO: " . print_r(json_decode($orderInfo, true)));

            $return = array_merge($return, json_decode($orderInfo, true));
        }

        return $return;
    }

    public static function isEmptyRow($row): bool
    {
        return array_all($row, fn($v) => $v === null || $v === "");
    }

    public static function getSplitValues(array $row, string $label): array
    {
        return array_filter(explode(';', $row[$label]), fn($v) => $v !== '');
    }

}