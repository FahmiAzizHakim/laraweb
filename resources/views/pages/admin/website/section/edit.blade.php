@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Edit Section &mdash; {{ $data->section_name }}</h3>
      <div class="card-tools">
        <a href="{{ url('/website/section') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
          </button>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
              </ul>
            </div>
          @endif

          <form action="{{ url('/website/section/'.$data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label>Section key</label>
              <input type="text" class="form-control" value="{{ $data->section_key }}" disabled>
              <small class="form-text text-muted">
                Names the Blade partial that renders this block, so it cannot be changed here.
              </small>
            </div>

            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" name="section_name" class="form-control"
                         value="{{ old('section_name', $data->section_name) }}">
                  <small class="form-text text-muted">Shown in this admin list only.</small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Order <span class="text-danger">*</span></label>
                  <input type="number" min="0" name="order" class="form-control"
                         value="{{ old('order', $data->order) }}">
                  <small class="form-text text-muted">Lowest renders first.</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Menu label</label>
                  <input type="text" name="nav_label" class="form-control"
                         placeholder="e.g. Produk"
                         value="{{ old('nav_label', $data->nav_label) }}">
                  <small class="form-text text-muted">Shown in the site navigation. Falls back to the name.</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Anchor</label>
                  <input type="text" name="anchor" class="form-control"
                         placeholder="e.g. products"
                         value="{{ old('anchor', $data->anchor) }}">
                  <small class="form-text text-muted">
                    The <code>id</code> of the section on the page; the menu links to <code>#anchor</code>.
                    Leave empty to keep the block out of the menu entirely.
                  </small>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Visibility</label>
              <div>
                <label class="mr-4">
                  <input type="radio" name="show_in_page" value="1" {{ old('show_in_page', $data->show_in_page) ? 'checked' : '' }}> Show on page
                </label>
                <label>
                  <input type="radio" name="show_in_page" value="0" {{ old('show_in_page', $data->show_in_page) ? '' : 'checked' }}> Hide from page
                </label>
              </div>
              <div class="mt-2">
                <label class="mr-4">
                  <input type="radio" name="show_in_nav" value="1" {{ old('show_in_nav', $data->show_in_nav) ? 'checked' : '' }}> Show in menu
                </label>
                <label>
                  <input type="radio" name="show_in_nav" value="0" {{ old('show_in_nav', $data->show_in_nav) ? '' : 'checked' }}> Hide from menu
                </label>
              </div>
              <small class="form-text text-muted">
                A section hidden from the page never appears in the menu either, whatever this says.
              </small>
            </div>

            <div class="form-group">
              <label>Remark</label>
              <textarea name="remark" class="form-control" rows="2">{{ old('remark', $data->remark) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
            <a href="{{ url('/website/section') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
