<?php

namespace Famoser\Mamapp;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Psr\Http\Message\StreamInterface;

class Initializer
{
    public const string MAMMALS_DIR = 'mammals';
    public const string SOURCE_DIR = PathHelper::VAR_PERSISTENT_DIR . DIRECTORY_SEPARATOR . self::MAMMALS_DIR;
    public const string IMAGES_SOURCE_DIR = self::SOURCE_DIR . DIRECTORY_SEPARATOR . 'Bilder';
    public const string CACHE_DIR = PathHelper::VAR_TRANSIENT_DIR . DIRECTORY_SEPARATOR . self::MAMMALS_DIR;
    public const string TARGET_DIR = PathHelper::PUBLIC_DIR . DIRECTORY_SEPARATOR . self::MAMMALS_DIR;

    public static function init(StreamInterface $output): void
    {
        $path = self::SOURCE_DIR . DIRECTORY_SEPARATOR . "Arten.xlsx";
        if (!self::readAnimalsXlsx($output, $path, $animals)) {
            return;
        }

        $output->write("Found " . count($animals) . " animals.\n");

        $path = self::IMAGES_SOURCE_DIR . DIRECTORY_SEPARATOR . "App_Bilddatenbank.xlsx";
        if (!self::readImagesXlsx($output, $path, $images)) {
            return;
        }

        exec("rm -rf " . self::TARGET_DIR);
        if (!self::addImages($output, $images, $animals)) {
            return;
        }
        $output->write("Added images.\n");

        if (!self::writeJsonToTargetDir($output, $animals, "mammals.json")) {
            return;
        }

        $output->write("Created mammals.json.\n");
    }

    /**
     * @param mixed[] $data
     * @param-out mixed[] $data
     *
     * @phpstan-assert-if-true mixed[] $data
     */
    private static function readAnimalsXlsx(StreamInterface $output, string $filePath, ?array &$data = null): bool
    {
        $expectedHeader = ['Species_ID_prov', 'Deutscher Name', 'Wissenschaftlicher Name', 'mdd_id', 'iucn_status', 'Familie', 'Beschreibung', 'Ähnliche Arten', 'Lebensraum', 'Wie finden', 'Naturschutz'];
        $cleanHeader = ['id', 'name', 'latinName', 'mddId', 'iucnStatus', 'family', 'description', 'similarSpecies', 'habitat', 'observe', 'conservation'];
        return self::xlsxToArray($output, $filePath, $expectedHeader, $cleanHeader, $data);
    }

    /**
     * @param mixed[] $data
     * @param-out mixed[] $data
     *
     * @phpstan-assert-if-true mixed[] $data
     */
    private static function readImagesXlsx(StreamInterface $output, string $filePath, ?array &$data = null): bool
    {
        $expectedHeader = ['image_name','caption','role','eigen/fremd','mdd_id', 'Species_ID_prov', 'scientific_name', 'common_name_de', 'common_name_en', 'image_date', 'country', 'source', 'observation_url', 'image_url', 'creator', 'creator_username', 'copyright_holder', 'attribution_text', 'license', 'license_url', 'licence_screenshot_id', 'commercial_use', 'attribution_required', 'modification_allowed', 'modified', 'modification_description', 'download_date', 'creator_asked', 'creator_answer', 'file_path', 'file_format', 'notes'];
        $cleanHeader = ['image_name','caption','role','eigen/fremd','mdd_id', 'species_id', 'scientific_name', 'name', 'common_name_en', 'image_date', 'country', 'source', 'observation_url', 'image_url', 'creator', 'creator_username', 'copyright_holder', 'attribution_text', 'license', 'license_url', 'licence_screenshot_id', 'commercial_use', 'attribution_required', 'modification_allowed', 'modified', 'modification_description', 'download_date', 'creator_asked', 'creator_answer', 'file_path', 'file_format', 'notes'];
        return self::xlsxToArray($output, $filePath, $expectedHeader, $cleanHeader, $data);
    }

    /**
     * @param mixed[] $imagesDatabase
     * @param mixed[] $animals
     */
    private static function addImages(StreamInterface $output, array $imagesDatabase, array &$animals): bool
    {
        foreach ($animals as &$animal) {
            $animal['images'] = [];
        }

        // First loop: find and add images for each animal
        foreach ($animals as &$animal) {
            // Find folder in SOURCE_DIR/Bilder with $animal["id"] as prefix
            $animalId = $animal["id"];
            $pattern = self::IMAGES_SOURCE_DIR . DIRECTORY_SEPARATOR . $animalId . '*';
            $folders = glob($pattern, GLOB_ONLYDIR);

            if (!$folders || count($folders) !== 1) {
                $output->write("Warning: Image folder not found or not unique for animal with id: " . $animalId . "\n");
                continue;
            }

            $folder = $folders[0];

            $jpgFiles = glob($folder . DIRECTORY_SEPARATOR . '*.jpg') ?: [];
            $jpegFiles = glob($folder . DIRECTORY_SEPARATOR . '*.jpeg') ?: [];
            $imageFiles = array_merge($jpgFiles, $jpegFiles);

            foreach ($imageFiles as $imagePath) {
                // Remove file extension to get image name
                $filename = basename($imagePath);
                $imageNameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

                // Find entry in $imagesDatabase
                $imageEntry = null;
                foreach ($imagesDatabase as $dbImage) {
                    if (isset($dbImage['image_name']) && $dbImage['image_name'] === $imageNameWithoutExt) {
                        $imageEntry = $dbImage;
                        break;
                    }
                }

                // Skip if no entry found
                if ($imageEntry === null) {
                    continue;
                }

                // Compose the fullCaption
                $fullCaption = $imageEntry['caption'] . " " . $imageEntry['attribution_text'];
                if (!empty($imageEntry['license'])) {
                    $fullCaption .= " " . $imageEntry['license'];
                }

                // copy to out directory
                $targetDir = self::TARGET_DIR . "/images/" . $animalId;
                mkdir($targetDir, recursive: true);
                copy($imagePath, $targetDir . "/" . $filename);

                // Add image to animal's images array
                $animal['images'][] = [
                    'path' => self::MAMMALS_DIR . '/images/' . $animalId . '/' . $filename,
                    'caption' => $fullCaption
                ];
            }
        }

        // Second loop: verify all images in database have corresponding animals and image files
        unset($animal);
        foreach ($imagesDatabase as $image) {
            $animal = array_find($animals, fn ($animal) => $animal['id'] === $image['species_id']);
            if (!$animal) {
                $output->write("Error: Image database entry references species " . $image['species_id'] . " which cannot be found.\n");
                return false;
            }

            $animalImage = array_find($animal['images'], fn ($animalImage) => str_starts_with($animalImage['path'], $image['file_path']));
            if (!$animalImage) {
                $output->write("Warning: Image database references image " . $image['file_path'] . " which cannot be found.\n");
                return false;
            }
        }

        return true;
    }

    /**
     * @param string[] $expectedHeader
     * @param string[] $cleanHeader
     *
     * @param mixed[] $data
     * @param-out mixed[] $data
     *
     * @phpstan-assert-if-true mixed[] $data
     */
    private static function xlsxToArray(StreamInterface $output, string $filePath, array $expectedHeader, array $cleanHeader, ?array &$data = null): bool
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $data = [];
        $isFirstRow = true;
        $expectedHeader = ['Species_ID_prov', 'Deutscher Name', 'Wissenschaftlicher Name', 'mdd_id', 'iucn_status', 'Familie', 'Beschreibung', 'Ähnliche Arten', 'Lebensraum', 'Wie finden', 'Naturschutz'];
        $cleanHeader = ['id', 'name', 'latinName', 'mddId', 'iucnStatus', 'family', 'description', 'similarSpecies', 'habitat', 'observe', 'conservation'];

        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];

            foreach ($row->getCellIterator() as $cell) {
                $value = $cell->getValueString();
                if (!$value) {
                    break;
                }

                $rowData[] = $value;
            }

            if ($isFirstRow) {
                $diff = array_diff($rowData, $expectedHeader);
                if ($diff != []) {
                    $output->write("Fail: Columns not in expected format / order. Expected: " . join(", ", $expectedHeader) . " Actual: " . join(", ", $rowData) . " Diff: " . join(", ", $diff) . "\n");
                    return false;
                }

                $isFirstRow = false;
                continue;
            }

            if (count($rowData) !== count($cleanHeader)) {
                continue;
            }

            $data[] = array_combine($cleanHeader, $rowData);
        }

        return true;
    }

    /**
     * @param string[] $data
     */
    private static function writeJsonToTargetDir(StreamInterface $output, array $data, string $filename): bool
    {
        if (!is_dir(self::TARGET_DIR)) {
            if (!mkdir(self::TARGET_DIR, 0755, true)) {
                $output->write("Error: Failed to create target directory: " . (self::TARGET_DIR) . "\n");
                return false;
            }
        }

        $jsonFile = self::TARGET_DIR . DIRECTORY_SEPARATOR . $filename;
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (file_put_contents($jsonFile, $json) === false) {
            $output->write("Error: Failed to write JSON file: $jsonFile\n");
            return false;
        }

        return true;
    }
}
