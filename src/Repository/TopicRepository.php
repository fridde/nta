<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\ORM\EntityRepository;

class TopicRepository extends EntityRepository
{
    public function getFormattedTopics(): array
    {
        $result = [];
        foreach ($this->findAll() as $topic) {
            /** @var Topic $topic */
            $result[$topic->getId()] = $topic->getName();
        }

        return $result;
    }


}