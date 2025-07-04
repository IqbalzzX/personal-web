<?php
// Folder penyimpanan gambar
$uploadDir = '../images/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$funcNum = $_GET['CKEditorFuncNum'] ?? 1;

if ($_FILES['upload']) {
    $file = $_FILES['upload'];
    $fileName = basename($file['name']);
    $targetFile = $uploadDir . $fileName;
    $uploadOk = 1;

    // Cek apakah file gambar
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        echo json_encode(['uploaded' => 0, 'error' => ['message' => 'File bukan gambar.']]);
        exit;
    }

    // Simpan file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $url = $uploadDir . $fileName;
        echo json_encode([
            'uploaded' => 1,
            'fileName' => $fileName,
            'url' => $url
        ]);
    } else {
        echo json_encode(['uploaded' => 0, 'error' => ['message' => 'Upload gagal.']]);
    }
}
?>
