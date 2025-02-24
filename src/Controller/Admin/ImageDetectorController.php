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

    #[Route('/item/rename-photos')]
    public function renamePhotos(): Response
    {
        $return = [];

        $photoTimesData = $this->getPhotoTimesData();
        usort($photoTimesData, fn($a, $b) => Carbon::parse($a["Timestamp"])->isAfter(Carbon::parse($b["Timestamp"])) ? 1 : -1);

        $lastKey = count($photoTimesData) - 1;
        $dirname = $this->kernel->getProjectDir() . '/data/item_images';
        $photos = Finder::create()->files()->in($dirname)->sortByName();


        foreach ($photoTimesData as $key => $photoTimeData) {
            $givenTime =  Carbon::parse($photoTimeData["Timestamp"]);
            foreach($photos as $photo) {
                $base = $photo->getBasename('.jpg');
                $parts = explode("_", $base);
                if(count($parts) < 3) {
                    continue;
                }
                $fileTime = Carbon::createFromFormat('Ymd His', implode(' ', [$parts[1], $parts[2]]));
                $isAfter = $fileTime->isAfter($givenTime);
                $isBefore = true;
                if($key < $lastKey) {
                    $isBefore = $fileTime->isBefore(Carbon::parse($photoTimesData[$key+1]["Timestamp"]));
                }
                if($isAfter && $isBefore) {
                    $random = substr(md5(microtime()), 0, 3);
                    $newFileName = strtolower($photoTimeData['Artikel']) . '_' . $random . '.jpg';
                    $this->fileSystem->rename($photo->getPathname(), $dirname . '/' . $newFileName);
                }
            }
        }

        return new Response(json_encode($return));
    }

    #[Route('/item/recalculate')]
    #[Template('admin/image_model_recalculation.html.twig')]
    public function showRecalcButtons(): array
    {

        
        return [];
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