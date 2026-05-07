@extends('layouts.app')
@section('title', 'Edit Schedule')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card content-card">
            <div class="card-header">
                <i class="bi bi-calendar3 me-2"></i>Edit Schedule
            </div>
            <div class="card-body">
                <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Route</label>
                            <select name="route_id" class="form-select rounded-3">
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}"
                                        {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
                                        {{ $route->origin }} → {{ $route->destination }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bus</label>
                            <select name="bus_id" class="form-select rounded-3">
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}"
                                        {{ old('bus_id', $schedule->bus_id) == $bus->id ? 'selected' : '' }}>
                                        {{ $bus->plate_number }} — {{ $bus->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Driver</label>
                            <select name="driver_id" class="form-select rounded-3">
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ old('driver_id', $schedule->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Departure Time</label>
                            <input type="time" name="departure_time"
                                   class="form-control rounded-3"
                                   value="{{ old('departure_time', $schedule->departure_time) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select rounded-3">
                                <option value="active"
                                    {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="cancelled"
                                    {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Operating Days</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           name="days[]" value="{{ $day }}"
                                           id="edit_day_{{ $day }}"
                                           {{ in_array($day, old('days', $schedule->days ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_day_{{ $day }}">
                                        {{ substr($day, 0, 3) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 rounded-pill">
                            Update Schedule
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