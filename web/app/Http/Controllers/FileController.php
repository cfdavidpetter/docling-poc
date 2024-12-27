<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Services\RabbitMQService;

class FileController extends Controller
{
  public function showForm()
  {
    return view('upload');
  }

  public function handleUpload(Request $request, RabbitMQService $rabbitMQService)
  {
    $request->validate([
      'file' => 'required|file',
    ]);

    $file = $request->file('file');
    $base64 = base64_encode(file_get_contents($file->getRealPath()));

    $uuid = Uuid::uuid4()->toString();
    $rabbitMQService->sendMessage($uuid, 'pdf_queue', $base64);
    $response = $rabbitMQService->waitForResponse("json_queue__$uuid");

    $rabbitMQService->deleteQueue("json_queue__$uuid");
    return view('response', ['response' => $response]);
  }
}
