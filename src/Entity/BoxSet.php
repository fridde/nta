<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class BoxSet
{
    private Collection $Boxes;

    public function getBoxes(): Collection
    {
        return $this->Boxes;
    }

    public function setBoxes(Collection $Boxes): void
    {
        $this->Boxes = $Boxes;
    }

    public function getTopic(): Topic
    {
        /** @var Box $box */
        $box = $this->getBoxes()->first();

        return $box->getTopic();
    }

    public function getNrBoxes(): int
    {
        return $this->getBoxes()->count();
    }


}