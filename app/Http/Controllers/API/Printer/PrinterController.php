<?php

namespace App\Http\Controllers\API\Printer;

use App\Http\Controllers\Controller;
use App\Services\PrinterService;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;


class PrinterController extends Controller
{
    public $printerService;

    public function __construct(PrinterService $printerService)
    {
        $this->printerService = $printerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $connector = new FilePrintConnector('/dev/usb/lp0');
            $printer = new Printer($connector);

            $printer->text("Hello World!\n");
            $printer->cut();
            $printer->close();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function print($paymentCode)
    {
        try {
            // dd($paymentCode);
            $this->printerService->print($paymentCode);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
