<?php

namespace App\DataFixtures;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Entity\Topic;
use App\Enums\InventoryType;
use App\Utils\RepoContainer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MaterialImporter
{
    private const int MAX_ROW = 339;  // subtract two rows because first row doesn't count and array is 0-indexed.


    public function __construct(
        private readonly RepoContainer       $rc,
        private readonly HttpClientInterface $httpClient,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface     $logger,
        private readonly string              $materiallistaUrl
    )
    {
    }

    public function getData(): array
    {
        $response = $this->httpClient->request('GET', $this->materiallistaUrl);

        return $this->serializer->decode($response->getContent(), 'csv');
    }

    public function run(): void
    {

        foreach ($this->getData() as $rowNr => $row) {
            if ($rowNr > self::MAX_ROW || self::isEmptyRow($row)) {
                continue;
            }
            //dump($rowNr);
            $item = new Item();
            if (empty($row['id'])) {
                $this->logger->info("Missing id for row " . $rowNr);
            }
            $this->logger->info("Row " . $row['id']);
            $item->id = $row['id'];
            //dump($item->id);
            $item->Placement = $row['Plats'];
            $item->DetailedLabel = $row['Label_intern'];
            $item->SimpleLabel = $row['Label_user'];
            $item->StaffInfo = [$row['kommentar till packaren']];

            $orderInfo = $this->createOrderInfo($row['artikelnr på NTM'], $row['kommentar till beställare']);
            $item->OrderInfo = $orderInfo;
            $item->UserInfo = $row['kommentar till läraren'];
            $this->rc->getEntityManager()->persist($item);

            $stock = new Inventory(InventoryType::STOCKROOM);
            $stock->Item = $item;
            $stock->Quantity = (int)$row['Antal lagret'];
            $this->rc->getEntityManager()->persist($stock);

            $topicIds = self::getSplitValues($row, 'Låda');
            $ranks = self::getSplitValues($row, 'Rank');
            $boxAmounts = self::getSplitValues($row, 'Antal i lådan');
            $extraAmounts = self::getSplitValues($row, 'Förbrukning');

            foreach ($topicIds as $index => $topicId) {

                $inventory = new Inventory(InventoryType::BOX);


                /** @var Topic $topic */
                $topic = $this->rc->getTopicRepo()->find($topicId);
                $this->logger->info(get_class($topic));
                $inventory->Topic = $topic;

                $inventory->Item = $item;
                $inventory->Quantity = (int)$boxAmounts[$index];
                $inventory->ListRank = $ranks[$index];
                $this->rc->getEntityManager()->persist($inventory);


                if(!empty($extraAmounts) && (int) $extraAmounts[$index] > 0) {
                    $extraInventory = new Inventory(InventoryType::EXTRA_MATERIAL);

                    $extraInventory->Item = $item;
                    $extraInventory->Quantity = (int)$extraAmounts[$index];
                    $extraInventory->Topic = $topic;
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