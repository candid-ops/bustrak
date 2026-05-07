@extends('layouts.app')
@section('title', 'Add Schedule')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card content-card">
            <div class="card-header">
                <i class="bi bi-calendar-plus me-2"></i>Add New Schedule
            </div>
            <div class="card-body">
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Route</label>
                            <select name="route_id"
                                    class="form-select rounded-3 @error('route_id') is-invalid @enderror">
                                <option value="">-- Select Route --</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}"
                                        {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                        {{ $route->origin }} → {{ $route->destination }}
                                        (KES {{ number_format($route->fare) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('route_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bus</label>
                            <select name="bus_id"
                                    class="form-select rounded-3 @error('bus_id') is-invalid @enderror">
                                <option value="">-- Select Bus --</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}"
                                        {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                        {{ $bus->plate_number }} — {{ $bus->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Driver</label>
                            <select name="driver_id"
                                    class="form-select rounded-3 @error('driver_id') is-invalid @enderror">
                                <option value="">-- Select Driver --</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Departure Time</label>
                            <input type="time" name="departure_time"
                                   class="form-control rounded-3 @error('departure_time') is-invalid @enderror"
                                   value="{{ old('departure_time') }}">
                            @error('departure_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Operating Days</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           name="days[]" value="{{ $day }}"
                                           id="day_{{ $day }}"
                                           {{ in_array($day, old('days', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_{{ $day }}">
                                        {{ substr($day, 0, 3) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('days')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 rounded-pill">
                            Save Schedule
                        </button>
                        <a href="{{ route('admin.schedules.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection