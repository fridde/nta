<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class BoxSet
{
    public Collection $Boxes;

    public function getTopic(): Topic
    {
        /** @var Box $box */
        $box = $this->Boxes->first();

        return $box->Topic;
    }

    public function getNrBoxes(): int
    {
        return $this->Boxes->count();
    }


}