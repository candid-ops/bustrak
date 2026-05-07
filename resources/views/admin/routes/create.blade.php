@extends('layouts.app')
@section('title', 'Add Route')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card content-card">
            <div class="card-header">
                <i class="bi bi-signpost-2 me-2"></i>Add New Route
            </div>
            <div class="card-body">
                <form action="{{ route('admin.routes.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Origin</label>
                            <input type="text" name="origin"
                                   class="form-control rounded-3 @error('origin') is-invalid @enderror"
                                   value="{{ old('origin') }}" placeholder="e.g. Nairobi">
                            @error('origin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Destination</label>
                            <input type="text" name="destination"
                                   class="form-control rounded-3 @error('destination') is-invalid @enderror"
                                   value="{{ old('destination') }}" placeholder="e.g. Mombasa">
                            @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fare (KES)</label>
                            <input type="number" name="fare"
                                   class="form-control rounded-3 @error('fare') is-invalid @enderror"
                                   value="{{ old('fare') }}" placeholder="e.g. 1200">
                            @error('fare')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Distance (km)</label>
                            <input type="number" name="distance_km"
                                   class="form-control rounded-3"
                                   value="{{ old('distance_km') }}" placeholder="e.g. 480">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type"
                                    class="form-select rounded-3 @error('type') is-invalid @enderror">
                                <option value="">-- Select --</option>
                                <option value="city" {{ old('type')=='city'?'selected':'' }}>City</option>
                                <option value="intercity" {{ old('type')=='intercity'?'selected':'' }}>Intercity</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Stops
                                <small class="text-muted fw-normal">(comma separated)</small>
                            </label>
                            <input type="text" name="stops"
                                   class="form-control rounded-3"
                                   value="{{ old('stops') }}"
                                   placeholder="e.g. Mtito Andei, Voi, Mariakani">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 rounded-pill">
                            Save Route
                        </button>
                        <a href="{{ route('admin.routes.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection