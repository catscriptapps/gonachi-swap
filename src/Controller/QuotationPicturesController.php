<?php
// /src/Controller/QuotationPicturesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\QuotationPic;
use App\Models\Quotation;
use App\Utils\IdEncoder;
use Src\Service\ImageUploadService;
use RuntimeException;

class QuotationPicturesController
{
    /**
     * Get all pictures for a specific quotation
     * URL Pattern: api/quotation-pictures?id={encoded_id}
     */
    public function index($id): void
    {
        if (!$id) {
            json_response(['success' => false, 'messages' => ['Missing Quotation ID']], 400);
            return;
        }

        try {
            // Decode the ID (supports both raw numeric and encoded strings)
            $rawQuoteId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            $pictures = QuotationPic::where('quotation_id', $rawQuoteId)
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
     * Store multiple images for a quotation (Project Photos)
     * Follows strict Gonachi Convention for directory pathing and DB persistence.
     */
    public function store($id): void
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
                'message' => 'No project images found in request.'
            ]);
            exit;
        }

        try {
            // 2. Resolve Quotation
            $rawQuoteId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $quote = Quotation::find($rawQuoteId);
            if (!$quote) {
                throw new RuntimeException('Quotation not found.');
            }

            // 3. Resolve upload directories
            $baseUploadDir = realpath(__DIR__ . '/../../public/images/uploads/');
            if (!$baseUploadDir) {
                throw new RuntimeException('Base upload directory not found.');
            }

            $quoteUploadDir = $baseUploadDir . '/quotations/';
            if (!is_dir($quoteUploadDir)) {
                mkdir($quoteUploadDir, 0777, true);
            }

            // 4. Check current picture count (Hard limit: retrieved from getMediaLimit())
            $maxLimit = getMediaLimit();
            $currentCount = QuotationPic::where('quotation_id', $rawQuoteId)->count();
            if ($currentCount >= $maxLimit) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Limit of ' . $maxLimit . ' photos reached for this project.']);
                exit;
            }

            // 5. Initialize Service & Paths
            $relativePublicPathPrefix = 'images/uploads/quotations/';
            $service = new ImageUploadService($quoteUploadDir, 2000, 90);

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

                QuotationPic::create([
                    'quotation_id' => $rawQuoteId,
                    'pic_name'     => $newFileName,
                    'pos_index'    => $currentCount + $index + 1
                ]);

                $savedFiles[] = ['url' => $file['fileUrl']];
            }

            echo json_encode([
                'success' => true,
                'message' => 'Project images uploaded successfully.',
                'files'   => $savedFiles
            ]);
        } catch (\Throwable $e) {
            error_log('Quotation Picture Upload failed: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
