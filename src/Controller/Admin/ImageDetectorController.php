<?php

namespace App\Controller\Admin;

use Carbon\Carbon;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageDetectorController extends AbstractController
{
    private const string PHOTO_DATA_URL= 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRCydR3_LzWvq0xk5yoniKvlPHNZPI1D5O0vGSNy1iZNlQFEZJOLFB7Ei1fe7AijduTlWO3umOAoTHP/pub?gid=109147830&single=true&output=csv';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly SerializerInterface $serializer,
        private readonly KernelInterface $kernel,
        private readonly FileSystem $fileSystem,
    )
    {

    }


    #[Route('/item/recognize')]
    #[Template('admin/image_model_recalculation.html.twig')]
    public function recognizeItem(): array
    {

        $namesString = $this->fileSystem->readFile($this->kernel->getProjectDir() . '/public/image_model/class_names.txt');
        $classNames = explode(",", $namesString);
        
        return ['class_names' => $classNames];
    }

    #[Route('/api/get-photo-dates')]
    public function getPhotoTimesData(): JsonResponse
    {
        $response = $this->httpClient->request('GET', self::PHOTO_DATA_URL);

        $photoTimesData = $this->serializer->decode($response->getContent(), 'csv');
        usort($photoTimesData, fn($a, $b) => Carbon::parse($a["Timestamp"])->isAfter(Carbon::parse($b["Timestamp"])) ? 1 : -1);

        return new JsonResponse($photoTimesData);
    }
}