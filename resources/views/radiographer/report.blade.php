@extends('layouts.radiographer')

@section('main')
<div class="container mt-5">
  <!-- Patient Information Box -->
  <div class="card mb-4">
    <div class="card-header">
      <h3>Patient Information</h3>
    </div>
    <div class="card-body">
      <p><strong>Name:</strong> {{ $patient->name }}</p>
      <p><strong>IC:</strong> {{ $patient->ic }}</p>
      <p><strong>Address:</strong> {{ $patient->address }}</p>
      <p><strong>Contact:</strong> {{ $patient->contact }}</p>
      <p><strong>Date of Birth:</strong> {{ $patient->dob }}</p>
      <p><strong>Sex:</strong> {{ ucfirst($patient->sex) }}</p>
      <!-- Add more patient details as needed -->
    </div>
  </div>
</div>

<div class="container mt-5">
  <!-- Report Upload Form -->
  <h3>Upload Report for Patient: {{ $patient->name }}</h3>
  
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
  
  <form action="{{ route('radiographer.upload.report.store', $patient->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-3">
      <label for="report">Choose Report File (PDF only)</label>
      <input type="file" name="report" id="report" class="form-control-file">
      @error('report')
        <small class="text-danger">{{ $message }}</small>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Upload Report</button>
  </form>
  
  <hr>
</div>

<div class="container mt-5">
<!-- Section to View Patient Scan Images -->
  <h3>Patient Scan Images</h3>
  @if($patient->images && $patient->images->isEmpty())
    <p>No scans uploaded for this patient yet.</p>
  @elseif(!$patient->images)
    <p>No scans uploaded for this patient yet.</p>
  @else
    <div class="row">
      @foreach($patient->images as $image)
        <div class="col-md-4 mb-3">
          <div class="card">
            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
              <img src="{{ asset('storage/' . $image->image_path) }}" alt="Patient Scan" class="card-img-top img-fluid" style="max-height:200px; object-fit:cover;">
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
  
  <hr>
</div>
<div class="container mt-5">
  <!-- Section to View the Patient Report (if uploaded) -->
  <h3>Patient Report</h3>
  @if($patient->report)
    <p>
      <a href="{{ asset('storage/' . $patient->report->report_path) }}" class="btn btn-success" target="_blank">
        View Report
      </a>
    </p>
  @else
    <p>No report uploaded for this patient.</p>
  @endif
  
</div>
@endsection
