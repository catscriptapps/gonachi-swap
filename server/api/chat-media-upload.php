<?php
// /server/api/chat-media-upload.php

declare(strict_types=1);

use Src\Service\ImageUploadService;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'POST';

if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $filename = $input['filename'] ?? null;

    if ($filename) {
        // Security: Ensure they aren't trying to delete files outside the chat folder
        $filename = basename($filename);
        $filePath = realpath(__DIR__ . '/../../public/images/uploads/chats/') . '/' . $filename;

        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
            echo json_encode(['success' => true, 'message' => 'File cleaned up.']);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'File not found.']);
    exit;
}

$userId = $_SESSION['user_id'] ?? 1;

if (empty($_FILES['images']) || empty($_FILES['images']['tmp_name'][0])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No media found.']);
    exit;
}

$baseUploadDir = realpath(__DIR__ . '/../../public/images/uploads/');
$chatUploadDir = $baseUploadDir . '/chats/';

if (!is_dir($chatUploadDir)) {
    mkdir($chatUploadDir, 0777, true);
}

$service = new ImageUploadService($chatUploadDir, 1600, 85); // Slightly smaller for chat
$relativePublicPathPrefix = 'images/uploads/chats/';

$singleFile = [
    'name'     => [$_FILES['images']['name'][0]],
    'type'     => [$_FILES['images']['type'][0]],
    'tmp_name' => [$_FILES['images']['tmp_name'][0]],
    'error'    => [$_FILES['images']['error'][0]],
    'size'     => [$_FILES['images']['size'][0]],
];

$uploaded = $service->upload($singleFile, function (array $files) use ($relativePublicPathPrefix) {
    foreach ($files as $key => $fileInfo) {
        $files[$key]['fileUrl'] = getAssetBase() . $relativePublicPathPrefix . $fileInfo['fileName'];
        $files[$key]['resultUrl'] = $fileInfo['fileName'];
    }
    return $files;
});

if (empty($uploaded)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Upload failed.']);
    exit;
}

echo json_encode([
    'success'  => true,
    'filename' => $uploaded[0]['resultUrl'],
    'url'      => $uploaded[0]['fileUrl'],
    'files'    => [
        ['url' => $uploaded[0]['fileUrl']] // For the Uploader Factory's internal map
    ]
]);
