<?php

namespace App\Controller\Admin\Tool;

use App\Controller\Admin\DashboardController;
use App\Entity\Box;
use App\Kernel;
use App\Utils\PDF;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PageBoundaries;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ToolController extends DashboardController
{

    #[Route('/admin/tool', name: 'tool_index')]
    public function index(): Response
    {
        return new Response();
    }

    #[Route('/admin/box-status')]
    #[Template('admin/tools/box_status.html.twig')]
    public function showBoxStatus(): array
    {
        $boxes = $this->rc->getBoxRepo()->findAll();

        $return = [];
        foreach ($boxes as $box) {
            /* @var Box $box*/
            $status = $box->getLatestStatusUpdate();
            $return[] = [
                'id' => $box->getFormattedId(),
                'status' => $status?->getType()->value
            ];
        }

        return ['boxes' => $return];
    }

    #[Route('/admin/update-box-status')]
    #[Template('admin/tools/update_box_status.html.twig')]
    public function batchUpdateBoxStatus(): void
    {

    }

    #[Route('/admin/create-invoice')]
    public function createInvoices(): Response
    {
        $dir = $this->getParameter('kernel.project_dir');

        $pdf = new PDF(new Fpdi(), $dir);

        return new Response($pdf->createInvoices(), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    #[Route('/admin/create-address-labels')]
    public function createAddressLabels(): Response
    {
        $dir = $this->getParameter('kernel.project_dir');

        $pdf = new PDF(new Fpdi(), $dir);

        return new Response($pdf->createAddressLabels(), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }


}