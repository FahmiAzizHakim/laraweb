@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Change Password</h3>
      <div class="card-tools">
        <a href="{{ url('/dashboard') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
          </button>
        </a>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      <div class="row">
        <div class="col-md-6">
          <form action="{{ route('password.change.update') }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label>Current Password <span class="text-danger">*</span></label>
              <input type="password" name="current_password" class="form-control" autocomplete="current-password">
            </div>

            <div class="form-group">
              <label>New Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Minimum 8 characters">
            </div>

            <div class="form-group">
              <label>Confirm New Password <span class="text-danger">*</span></label>
              <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-success">
              <i class="fas fa-key"></i> Update Password
            </button>
            <a href="{{ url('/dashboard') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
