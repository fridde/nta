<?php

namespace App\Utils;

class Invoice
{
    public string $Date;

    public string $School;

    public int $NrOfBoxes;
    public array $BoxText;
    public int $BoxPrice;

    public int $NrOfSeminars;
    public array $SeminarText;
    public int $SeminarPrice;

    public int $Total;
}