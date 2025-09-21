<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ExcelExportService
{
    public function exportBukuInduk($bukuInduks, $sekolah)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator($sekolah->nama)
            ->setTitle('Buku Induk Siswa')
            ->setSubject('Data Buku Induk')
            ->setDescription('Export data buku induk siswa');

        // Header sekolah
        $sheet->setCellValue('A1', 'BUKU INDUK SISWA');
        $sheet->setCellValue('A2', strtoupper($sekolah->nama));
        $sheet->setCellValue('A3', $sekolah->alamat);
        $sheet->setCellValue('A4', 'Telp: ' . $sekolah->telepon . ' - Email: ' . $sekolah->email);

        // Merge cells untuk header
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->mergeCells('A3:L3');
        $sheet->mergeCells('A4:L4');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:A4')->applyFromArray($headerStyle);

        // Table headers
        $headers = [
            'A6' => 'No',
            'B6' => 'No. Induk',
            'C6' => 'Nama Siswa',
            'D6' => 'NISN',
            'E6' => 'JK',
            'F6' => 'Tempat Lahir',
            'G6' => 'Tanggal Lahir',
            'H6' => 'Tanggal Masuk',
            'I6' => 'Asal Sekolah',
            'J6' => 'Status',
            'K6' => 'Tanggal Keluar',
            'L6' => 'Alasan Keluar'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }

        // Style table headers
        $sheet->getStyle('A6:L6')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFD9D9D9']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Data rows
        $row = 7;
        foreach ($bukuInduks as $index => $bukuInduk) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $bukuInduk->no_induk ?? '-');
            $sheet->setCellValue('C' . $row, strtoupper($bukuInduk->siswa->nama));
            $sheet->setCellValue('D' . $row, $bukuInduk->siswa->nisn);
            $sheet->setCellValue('E' . $row, $bukuInduk->siswa->jk === 'Laki-laki' ? 'L' : 'P');
            $sheet->setCellValue('F' . $row, $bukuInduk->siswa->tempat_lahir);
            $sheet->setCellValue('G' . $row, Carbon::parse($bukuInduk->siswa->tanggal_lahir)->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, Carbon::parse($bukuInduk->tanggal_masuk)->format('d/m/Y'));
            $sheet->setCellValue('I' . $row, $bukuInduk->asal_sekolah ?? '-');
            $sheet->setCellValue('J' . $row, strtoupper($bukuInduk->status));
            $sheet->setCellValue('K' . $row, $bukuInduk->tanggal_keluar ? Carbon::parse($bukuInduk->tanggal_keluar)->format('d/m/Y') : '-');
            $sheet->setCellValue('L' . $row, $bukuInduk->alasan_keluar ?? '-');
            $row++;
        }

        // Style data rows
        $dataRange = 'A7:L' . ($row - 1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        // Auto-size columns
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Generate filename
        $filename = 'buku_induk_' . str_replace(' ', '_', strtolower($sekolah->nama)) . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Save to storage
        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/exports/' . $filename);

        // Create directory if not exists
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $writer->save($path);

        return [
            'filename' => $filename,
            'path' => $path
        ];
    }
}
