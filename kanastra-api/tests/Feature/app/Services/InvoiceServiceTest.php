<?php

namespace Tests\Feature\app\Services;

use App\Repositories\InvoiceRepository;
use App\Services\FileService;
use App\Services\SqsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    protected FileService $FileService;
    protected string         $pathMockCsvFile;

    protected function setUp(): void
    {
        parent::setUp();

        $invoiceRepositoryMock = $this->createMock(InvoiceRepository::class);
        $sqsServiceMock        = $this->createMock(SqsService::class);

        $this->FileService = new FileService($invoiceRepositoryMock, $sqsServiceMock);
    }

    public function testExecuteCSVInvalid()
    {
        $csvData = "";
        $file    = UploadedFile::fake()
            ->createWithContent('invalid.csv', $csvData);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File is not a valid csv file');

        $this->FileService->processCSV($file);
    }

    public function testExecuteProcessCSVValid()
    {
        $csvData = [
            0 =>
                [
                    'name',
                    'governmentId',
                    'email',
                    'debtAmount',
                    'debtDueDate',
                    'debtId',
                ],
            1 =>
                [
                    'John Doe',
                    '1',
                    'johndoe@kanastra.com.br',
                    '100.00',
                    '2022-10-12',
                    '3xsf4cdf-zz16-325f-bae8-4g89d585379d',
                ],
        ];

        $file = UploadedFile::fake()
            ->createWithContent('example.csv', $this->toCsvString($csvData));

        $result = $this->FileService->processCSV($file);
        $this->assertEquals('CSV_FILE_PROCESSED', $result);
    }

    private function toCsvStr(array $data): string
    {
        $output = fopen('php://temp', 'w');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvString = stream_get_contents($output);
        fclose($output);
        return $csvString;
    }
}
