<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaporanNilaiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $anak;
    public $nilaiData;
    public $pdfContent;
    public $pdfFileName;
    public $periode; 

    public function __construct($anak, $nilaiData, $pdfContent, $pdfFileName, $periode)
    {
        $this->anak = $anak;
        $this->nilaiData = $nilaiData;
        $this->pdfContent = $pdfContent;
        $this->pdfFileName = $pdfFileName;
        $this->periode = $periode; 
    }

    public function build()
    {
        return $this->subject('Laporan Nilai: ' . $this->anak->nama_lengkap)
            ->view('emails.laporan-nilai')
            ->with([
                'anak' => $this->anak,
                'nilaiData' => $this->nilaiData,
                'periode' => $this->periode
            ])
            ->attachData($this->pdfContent, $this->pdfFileName, [
                'mime' => 'application/pdf',
            ]);
    }
}
