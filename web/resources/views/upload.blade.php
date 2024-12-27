@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h1 class="text-center">Proof of Concept (POC) - Docling Integration</h1>
  <h2 class="text-center">Upload de Arquivo</h2>
  <form id="uploadForm" action="{{ route('upload.handle') }}" method="POST" enctype="multipart/form-data" class="mt-4">
    @csrf
    <div class="mb-3">
      <label for="file" class="form-label">Escolha um arquivo</label>
      <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
      @error('file')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
  </form>

  <div id="loadingContainer" class="d-none text-center mt-4">
    <p id="loadingText">Loading... <span id="timer">0</span> seconds</p>
  </div>
</div>
@endsection