<?php

namespace App\Utils;

use App\Entity\Period;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PageBoundaries;

class PDF
{
    const float CHARACTER_RATIO = 1.284;   // height of character / width of character

    const string TESTSTRING = 'The quick broWn fox jumps Over the lazy Dog';

    private float $widthOfLetterAt10;
    private float $heigthOfLetterAt10;

    public function __construct
    (
        private readonly Fpdi   $Pdf,
        private readonly string $Dir
    )
    {
        $this->Pdf->SetAutoPageBreak(false);
        $this->Pdf->setFont('Courier', '', 10);
        $this->widthOfLetterAt10 = $this->Pdf->GetStringWidth(self::TESTSTRING) / (float)mb_strlen(self::TESTSTRING);
        $this->heigthOfLetterAt10 = self::CHARACTER_RATIO * $this->widthOfLetterAt10;
    }

    public function createInvoices(): string
    {
        $this->Pdf->setSourceFile($this->Dir . '/templates/print/invoice.pdf');
        $pageId = $this->Pdf->importPage(1, PageBoundaries::MEDIA_BOX);

        $invoices = [$this->createMockInvoice()];

        foreach ($invoices as $invoice) {
            /** @var Invoice $invoice */
            $this->Pdf->AddPage();
            $this->Pdf->useTemplate($pageId);

            $this->writeDate($invoice->Date);
            $this->writeSchool($invoice->School);
            $this->writeNrOfBoxes($invoice->NrOfBoxes);
            $this->writeBoxText($invoice->BoxText);
            $this->writeBoxPrice($invoice->BoxPrice);
            $this->writeNrOfSeminars($invoice->NrOfSeminars);
            $this->writeSeminarText($invoice->SeminarText);
            $this->writeSeminarPrice($invoice->SeminarPrice);
            $this->writeTotal($invoice->Total);

        }
        return $this->Pdf->Output();

    }

    public function createAddressLabels(): string
    {
        //$this->Pdf->setSourceFile($this->Dir . '/templates/print/labels.pdf');
        //$pageId = $this->Pdf->importPage(1, PageBoundaries::MEDIA_BOX);

        $measurements = [
            'name' => [47.0, 8.0],
            'school' => [47.0, 7.0],
            'box_id' => [18.5, 4.5]
        ];

        $labels = $this->createMockLabels();

        foreach ($labels as $i => $label) {
            $index = $i % 24;
            if($index === 0) {
                $this->Pdf->AddPage();
            }
            /* ############# NAME  ##########  */
            [$nameX, $nameY] = $this->calculatePositionForLabel($index, 'name');
            [$wName, $hName] = $measurements['name'];

            $fontSize = $this->calculateFontSize($label['name'], $wName, $hName);
            $this->Pdf->SetFont('Courier', '', $fontSize);
            $this->Pdf->SetXY($nameX, $nameY);
            $this->Pdf->Cell($wName, $hName, self::convert($label['name']), align: 'C');

            /* ############# SCHOOL  ##########  */
            [$schoolX, $schoolY] = $this->calculatePositionForLabel($index, 'school');
            [$wSchool, $hSchool] = $measurements['school'];

            $fontSize = $this->calculateFontSize($label['school'], $wSchool, $hSchool);
            $this->Pdf->SetFont('Courier', 'B', $fontSize);
            $this->Pdf->SetXY($schoolX, $schoolY);
            $this->Pdf->Cell($wSchool, $hSchool, self::convert($label['school']), align: 'C');

            /* ############# BOX_ID  ##########  */
            [$boxX, $boxY] = $this->calculatePositionForLabel($index, 'box_id');
            [$wBox, $hBox] = $measurements['box_id'];

            $fontSize = $this->calculateFontSize($label['box_id'], $wBox, $hBox);
            $this->Pdf->SetFont('Courier', '', $fontSize);
            $this->Pdf->SetXY($boxX, $boxY);
            $this->Pdf->Cell($wName, $hName, self::convert($label['box_id']), align: 'L');
        }
        return $this->Pdf->Output();
    }

    private function calculatePositionForLabel(int $index, string $label): array
    {

        $col = $index % 3;
        $row = (int)($index / 3);

        $widthOfLabel = 70.2;
        $heightOfLabel = 36.2;

        $yOffset = 4.0;

        $placements = [
            'name' => [11.5, 5.5],
            'school' => [11.5, 15],
            'box_id' => [40.0, 26.0]
        ];

        return [
            $placements[$label][0] + ($col * $widthOfLabel),
            $yOffset + $placements[$label][1] + ($row * $heightOfLabel)
        ];
    }

    private function writeDate(string $date): void
    {
        $this->Pdf->SetXY(90, 62);
        $this->Pdf->SetFontSize(11);
        $this->Pdf->Cell(20, 3, $date);
    }

    private function writeSchool(string $school): void
    {
        $this->Pdf->SetXY(106, 84);
        $this->Pdf->SetFontSize(16);
        $this->Pdf->Cell(60, 5, self::convert($school));
    }

    private function writeNrOfBoxes(int $nrOfBoxes): void
    {
        $this->Pdf->SetXY(61, 120);
        $this->Pdf->SetFontSize(13);
        $this->Pdf->Cell(10, 3, $nrOfBoxes . ' st');
    }

    private function writeBoxText(array $boxText): void
    {
        $this->writeMultiRowText($boxText, 28, 124);
    }

    private function writeBoxPrice(string $boxPrice): void
    {
        $this->Pdf->SetXY(154, 123);
        $this->Pdf->SetFontSize(13);
        $this->Pdf->Cell(25, 3, $boxPrice . ' kr');

        $this->Pdf->SetXY(142, 175);
        $this->Pdf->SetFontSize(10);
        $this->Pdf->Cell(20, 3, $boxPrice . ' kr');
    }

    private function writeNrOfSeminars(int $nrOfSeminars): void
    {
        $this->Pdf->SetXY(79, 135);
        $this->Pdf->SetFontSize(13);
        $this->Pdf->Cell(10, 3, $nrOfSeminars . ' st');
    }

    private function writeSeminarText(array $seminarText): void
    {
        $this->writeMultiRowText($seminarText, 28, 139);
    }

    private function writeMultiRowText(array $multiRowText, float $xPos, float $yPos): void
    {
        $multiRowText = $this->splitIntoTwoBalancedArrays($multiRowText);
        $multiRowText = array_filter($multiRowText, fn($v) => count($v) > 0);
        $rowCount = count($multiRowText);

        if ($rowCount === 0) {
            return;
        }
        $multiRowText = array_map(fn($v) => implode(", ", $v), $multiRowText);
        if ($rowCount === 2) {
            $multiRowText[0] .= ',';
        }
        $height = 3.5;
        $width = 114.0;
        $fontSize = $this->calculateFontSize($multiRowText[0], $width, $height);
        $this->Pdf->SetFontSize($fontSize);

        $this->Pdf->SetXY($xPos, $yPos);
        $this->Pdf->Cell($width, $height, self::convert($multiRowText[0]));

        if (count($multiRowText) === 2) {
            $this->Pdf->SetXY($xPos, $yPos + $height);
            $this->Pdf->Cell($width, $height, self::convert($multiRowText[1]));
        }
    }

    private function writeSeminarPrice(int $seminarPrice): void
    {
        $this->Pdf->SetXY(154, 137);
        $this->Pdf->SetFontSize(13);
        $this->Pdf->Cell(25, 3, $seminarPrice . ' kr');

        $this->Pdf->SetXY(142, 179);
        $this->Pdf->SetFontSize(10);
        $this->Pdf->Cell(20, 3, $seminarPrice . ' kr');
    }

    private function writeTotal(int $total): void
    {
        $this->Pdf->SetXY(150, 154.5);
        $this->Pdf->SetFontSize(13);
        $this->Pdf->Cell(25, 3, $total);
    }

    private static function convert(string $text): string
    {
        return mb_convert_encoding($text, 'windows-1252', 'UTF-8');
    }

    private function calculateFontSize(string $text, float $maxWidth, float $maxHeight): int
    {
        $length = mb_strlen($text);
        if($length === 0){
            return 10;   // default size to avoid division by zero
        }

        $maxX = 10.0 * (($maxWidth / $this->widthOfLetterAt10) / $length);
        $maxY = 10.0 * ($maxHeight / $this->heigthOfLetterAt10);

        return (int)min($maxX, $maxY);
    }

    private function splitIntoTwoBalancedArrays(array $array): array
    {
        $return = [[], []];

        foreach ($array as $key => $item) {
            $index = $key % 2;
            $return[$index][] = $item;
        }

        return $return;
    }

    private function createMockInvoice(): Invoice
    {
        $i = new Invoice();

        $i->Date = '2024-11-14';

        $i->School = 'Råbergsskolan i samband med nya Steningehöjden';
        $i->NrOfBoxes = 2;
        $i->BoxText = ['Ingela Junered Rosén (1)', 'Ronja Cengiz Edstrand (1)'];
        $i->BoxPrice = 2500;

        $i->NrOfSeminars = 5;
        $i->SeminarText = ['Ingela Gustafsson', 'Jessica Jakobsson-Krånglin', 'Denise Tengerström', 'Reem Saba', 'Ronja Hourieh', 'Hans Evertsson', 'Adam Albertsson-Houdini', 'Sam Weiß', 'Rubiks Kubsson'];
        $i->SeminarPrice = 5000;

        $i->Total = 7500;

        return $i;
    }

    private function createMockLabels(): array
    {
        return [
            [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ], [
                'name' => 'Hans Bergsson',
                'school' => 'Steningehöjdens skola',
                'box_id' => 'KF:01:A'
            ], [
                'name' => 'Samuel Håkansson',
                'school' => 'Eddaskolan',
                'box_id' => 'BV:01'
            ], [
                'name' => 'Ingrid Julisson-Merlin av Sandeberg',
                'school' => 'Råbergsskolan',
                'box_id' => 'KF:11:A'
            ],
        ];
    }
}