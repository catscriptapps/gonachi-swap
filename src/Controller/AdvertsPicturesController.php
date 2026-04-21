<?php
// /src/Controller/AdvertsPicturesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\AdvertPic;
use App\Models\Advert;
use App\Utils\IdEncoder;
use Src\Service\ImageUploadService;
use RuntimeException;

class AdvertsPicturesController
{
    /**
     * Get all pictures for a specific advert
     * URL Pattern: api/advert-pictures?id={encoded_id}
     */
    public function index($id): void
    {
        if (!$id) {
            json_response(['success' => false, 'messages' => ['Missing Advert ID']], 400);
            return;
        }

        try {
            // Decode the ID (supports both raw numeric and encoded strings)
            $rawAdId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            $pictures = AdvertPic::where('advert_id', $rawAdId)
                ->orderBy('entry_id', 'asc')
                ->get();

            json_response([
                'success' => true,
                'pictures' => $pictures->toArray()
            ]);
        } catch (\Throwable $e) {
            json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
        }
    }

    /**
     * Store multiple images for an advert
     * Follows strict Gonachi Convention for directory pathing and DB persistence.
     */
    public function store($id): void
    {
        // 1. Validate uploaded files (Convention: Factory sends under 'images' key)
        if (
            empty($_FILES['images']) ||
            !is_array($_FILES['images']['tmp_name']) ||
            empty($_FILES['images']['tmp_name'][0])
        ) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No images found in request.'
            ]);
            exit;
        }

        try {
            // 2. Resolve Advert
            $rawAdId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $ad = Advert::find($rawAdId);
            if (!$ad) {
                throw new RuntimeException('Advert not found.');
            }

            // 3. Resolve upload directories
            $baseUploadDir = realpath(__DIR__ . '/../../public/images/uploads/');
            if (!$baseUploadDir) {
                throw new RuntimeException('Base upload directory not found.');
            }

            $adUploadDir = $baseUploadDir . '/adverts/';
            if (!is_dir($adUploadDir)) {
                mkdir($adUploadDir, 0777, true);
            }

            // 4. Check current picture count (Hard limit: retrieved from getMediaLimit())
            $maxLimit = getMediaLimit();
            $currentCount = AdvertPic::where('advert_id', $rawAdId)->count();
            if ($currentCount >= $maxLimit) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Limit of ' . $maxLimit . ' photos reached for this advert.']);
                exit;
            }

            // 5. Initialize Service & Paths
            $relativePublicPathPrefix = 'images/uploads/adverts/';
            $service = new ImageUploadService($adUploadDir, 2000, 90);

            // 6. Upload images via Service callback
            $uploaded = $service->upload($_FILES['images'], function (array $files) use ($relativePublicPathPrefix) {
                foreach ($files as $key => $fileInfo) {
                    // resultUrl: Strictly filename for DB
                    $files[$key]['resultUrl'] = $fileInfo['fileName'];
                    // fileUrl: Full path for frontend response
                    $files[$key]['fileUrl'] = $relativePublicPathPrefix . $fileInfo['fileName'];
                }
                return $files;
            });

            if (empty($uploaded) || (isset($uploaded['success']) && $uploaded['success'] === false)) {
                throw new RuntimeException($uploaded['message'] ?? 'Upload failed.');
            }

            // 7. Persist to DB
            $savedFiles = [];
            foreach ($uploaded as $index => $file) {
                // Double check we don't burst the limit during bulk upload
                if (($currentCount + $index) >= $maxLimit) break;

                $newFileName = basename($file['resultUrl']);

                AdvertPic::create([
                    'advert_id' => $rawAdId,
                    'pic_name'         => $newFileName,
                    'pos_index'        => $currentCount + $index + 1
                ]);

                $savedFiles[] = ['url' => $file['fileUrl']];
            }

            echo json_encode([
                'success' => true,
                'message' => 'Images uploaded successfully.',
                'files'   => $savedFiles
            ]);
        } catch (\Throwable $e) {
            error_log('Ad Picture Upload failed: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
