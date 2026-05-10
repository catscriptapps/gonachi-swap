<?php
// /src/Controller/ListingPicturesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\ListingPic;
use App\Models\Listing;
use App\Utils\IdEncoder;
use Src\Service\ImageUploadService;
use RuntimeException;

class ListingPicturesController
{
    /**
     * Get all pictures for a specific listing
     * URL Pattern: api/listing-pictures?id={encoded_id}
     */
    public function index(string|int $id): void
    {
        if (!$id) {
            json_response(['success' => false, 'messages' => ['Missing Listing ID']], 400);
            return;
        }

        try {
            // Decode the ID (supports both raw numeric and encoded strings)
            $rawListingId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            $pictures = ListingPic::where('listing_id', $rawListingId)
                ->orderBy('pos_index', 'asc')
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
     * Store multiple images for a listing (Marketplace Photos)
     * Follows strict Gonachi Convention for directory pathing and DB persistence.
     */
    public function store(string|int $id): void
    {
        // 1. Validate uploaded files
        if (
            empty($_FILES['images']) ||
            !is_array($_FILES['images']['tmp_name']) ||
            empty($_FILES['images']['tmp_name'][0])
        ) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No listing images found in request.'
            ]);
            exit;
        }

        try {
            // 2. Resolve Listing
            $rawListingId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $listing = Listing::find($rawListingId);
            if (!$listing) {
                throw new RuntimeException('Listing not found.');
            }

            // 3. Resolve upload directories
            $baseUploadDir = realpath(__DIR__ . '/../../public/images/uploads/');
            if (!$baseUploadDir) {
                throw new RuntimeException('Base upload directory not found.');
            }

            $listingUploadDir = $baseUploadDir . '/listings/';
            if (!is_dir($listingUploadDir)) {
                mkdir($listingUploadDir, 0777, true);
            }

            // 4. Check current picture count (Hard limit: retrieved from getMediaLimit())
            $maxLimit = getMediaLimit();
            $currentCount = ListingPic::where('listing_id', $rawListingId)->count();
            if ($currentCount >= $maxLimit) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Limit of ' . $maxLimit . ' photos reached for this listing.']);
                exit;
            }

            // 5. Initialize Service & Paths
            $relativePublicPathPrefix = 'images/uploads/listings/';
            $service = new ImageUploadService($listingUploadDir, 2000, 90);

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
                // Bulk upload safety check
                if (($currentCount + $index) >= $maxLimit) break;

                $newFileName = basename($file['resultUrl']);

                ListingPic::create([
                    'listing_id' => $rawListingId,
                    'pic_name'   => $newFileName,
                    'pos_index'  => $currentCount + $index + 1
                ]);

                $savedFiles[] = ['url' => $file['fileUrl']];
            }

            echo json_encode([
                'success' => true,
                'message' => 'Listing images uploaded successfully.',
                'files'   => $savedFiles
            ]);
        } catch (\Throwable $e) {
            error_log('Listing Picture Upload failed: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
