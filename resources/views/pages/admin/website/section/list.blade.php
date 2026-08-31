@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Landing Page Sections</h3>
    </div>

    <form action="{{ url('/website/section/order') }}" method="POST">
      @csrf
      <div class="card-body">
        <p class="text-muted">
          The landing page is built from these blocks, top to bottom. Use the arrows to
          reorder, the checkboxes to show or hide, then press Save.
          <br>
          <strong>On page</strong> renders the block. <strong>In menu</strong> adds its link to
          the navigation &mdash; a block that is not on the page never gets a link, since the
          menu scrolls to it.
        </p>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style="width:90px;">Move</th>
              <th>Section</th>
              <th style="width:140px;">Menu label</th>
              <th style="width:110px;">Anchor</th>
              <th style="width:90px;" class="text-center">On page</th>
              <th style="width:90px;" class="text-center">In menu</th>
              <th style="width:80px;">Action</th>
            </tr>
          </thead>
          <tbody id="section-rows">
            @forelse($data as $row)
            <tr>
              <td class="text-center">
                <input type="hidden" name="order[]" value="{{ $row->id }}">
                <button type="button" class="btn btn-xs btn-default row-up" title="Move up"><i class="fas fa-arrow-up"></i></button>
                <button type="button" class="btn btn-xs btn-default row-down" title="Move down"><i class="fas fa-arrow-down"></i></button>
              </td>
              <td>
                <strong>{{ $row->section_name }}</strong>
                <br><small class="text-muted"><code>{{ $row->section_key }}</code></small>
              </td>
              <td>{{ $row->nav_label ?: '—' }}</td>
              <td>{{ $row->anchor ? '#'.$row->anchor : '—' }}</td>
              <td class="text-center">
                <input type="checkbox" name="show_in_page[]" value="{{ $row->id }}" {{ $row->show_in_page ? 'checked' : '' }}>
              </td>
              <td class="text-center">
                <input type="checkbox" name="show_in_nav[]" value="{{ $row->id }}" {{ $row->show_in_nav ? 'checked' : '' }}>
              </td>
              <td>
                <a href="{{ url('/website/section/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                  <i class="fas fa-edit"></i> Edit
                </a>
              </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted">
              No sections for this website. They are seeded per site &mdash; run
              <code>php artisan db:seed --class=WebSectionsSeeder</code>.
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($data->count())
      <div class="card-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Order &amp; Visibility</button>
      </div>
      @endif
    </form>
  </div>

  <div class="callout callout-info">
    Sections cannot be added or deleted here: each one is rendered by a Blade partial in
    <code>resources/views/pages/website2/sections/</code>, so the list is fixed by the code.
  </div>
@endsection

@section('script')
<script>
  (function () {
    // Reordering just moves the row: the hidden order[] inputs are read in
    // document order when the form posts.
    var body = document.getElementById('section-rows');
    if (!body) return;

    body.addEventListener('click', function (e) {
      var up = e.target.closest('.row-up');
      var down = e.target.closest('.row-down');
      if (!up && !down) return;

      var row = (up || down).closest('tr');

      if (up && row.previousElementSibling) {
        body.insertBefore(row, row.previousElementSibling);
      } else if (down && row.nextElementSibling) {
        body.insertBefore(row.nextElementSibling, row);
      }
    });
  })();
</script>
@endsection
