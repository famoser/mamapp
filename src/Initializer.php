<?php

namespace Famoser\Mamapp;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Psr\Http\Message\StreamInterface;

class Initializer
{
    public const string MAMMALS_DIR = 'mammals';
    public const string SOURCE_DIR = PathHelper::VAR_PERSISTENT_DIR . DIRECTORY_SEPARATOR . self::MAMMALS_DIR;
    public const string CACHE_DIR = PathHelper::VAR_TRANSIENT_DIR . DIRECTORY_SEPARATOR . self::MAMMALS_DIR;
    public const string TARGET_DIR = PathHelper::PUBLIC_DIR . DIRECTORY_SEPARATOR . self::MAMMALS_DIR;

    public static function init(StreamInterface $output): void
    {
        if (!self::getAnimalsXlsxFilepath($output, $path)) {
            return;
        }

        $output->write("Found file at " . $path . "\n");

        if (!self::xlsxToArray($output, $path, $animals)) {
            return;
        }

        $output->write("Found " . count($animals) . " animals.\n");


        if (!self::writeJsonToTargetDir($output, $animals, "mammals.json")) {
            return;
        }

        $output->write("Created mammals.json.\n");
    }

    /**
     * @phpstan-assert-if-true string $path
     */
    private static function getAnimalsXlsxFilepath(StreamInterface $output, ?string &$path): bool
    {
        $files = glob(self::SOURCE_DIR . DIRECTORY_SEPARATOR . '*.xlsx');

        if (!$files || count($files) !== 1) {
            $output->write("No unique XLSX file found in: self::SOURCE_DIR\n");
            return false;
        }

        $path = $files[0];
        return true;
    }

    /**
     * @param mixed[] $data
     * @param-out mixed[] $data
     *
     * @phpstan-assert-if-true mixed[] $data
     */
    private static function xlsxToArray(StreamInterface $output, string $filePath, ?array &$data = null): bool
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $data = [];
        $isFirstRow = true;
        $expectedHeader = ['Species_ID_prov','Deutscher Name','Wissenschaftlicher Name','mdd_id','iucn_status','Familie','Beschreibung','Ähnliche Arten','Lebensraum','Wie finden','Naturschutz'];
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
