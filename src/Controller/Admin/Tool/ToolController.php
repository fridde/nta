<?php

namespace App\Controller\Admin\Tool;

use App\Controller\Admin\DashboardController;
use App\Entity\Box;
use App\Enums\UpdateType;
use App\Kernel;
use App\Utils\Coll;
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

    #[Route('/admin/box-status', name: 'tools_box_status')]
    #[Template('admin/tools/box_status.html.twig')]
    public function showBoxStatus(): array
    {
        $boxes = Coll::create($this->rc->getBoxRepo()->findAll());

        return [
            'boxes' => $boxes->map(fn(Box $b) => [
                'id' => $b->getFormattedId(),
                'status' => $b->getLatestStatusUpdate()?->getType()->value
            ])];
    }

    #[Route('/admin/update-box-status', name: 'tools_update_box_status')]
    #[Template('admin/tools/update_box_status.html.twig')]
    public function batchUpdateBoxStatus(): array
    {
        return ['update_types' => UpdateType::cases()];
    }

    #[Route('/admin/create-box-inventory', name: 'tools_create_box_inventory')]
    #[Template('admin/tools/create_box_inventory.html.twig')]
    public function createBoxInventory(): array
    {
        $rows = [[
            'desc' => 'Gem, metall',
            'amount' => 200,
            'comment' => 'Återlämnas',
            'counted' => false
        ]];

        return [
            'topics' => $this->rc->getTopicRepo()->findAll(),
            'rows' => $rows,
        ];
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