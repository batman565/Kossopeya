@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h3 class="mb-3">NASA OSDR</h3>
  <div class="small text-muted mb-2">Источник {{ $src }}</div>

  <div class="table-responsive">
    <table class="table table-sm table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>dataset_id</th>
          <th>title</th>
          <th>REST_URL</th>
          <th>updated_at</th>
          <th>inserted_at</th>
          <th>raw</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $index => $row)
        <tr>
          <td>{{ ($currentPage - 1) * $limit + $index + 1 }}</td>
          <td>{{ $row['dataset_id'] ?? '—' }}</td>
          <td style="max-width:420px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
            {{ $row['title'] ?? '—' }}
          </td>
          <td>
            @if(!empty($row['rest_url']))
              <a href="{{ $row['rest_url'] }}" target="_blank" rel="noopener">открыть</a>
            @else — @endif
          </td>
          <td>{{ $row['updated_at'] ?? '—' }}</td>
          <td>{{ $row['inserted_at'] ?? '—' }}</td>
          <td>
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#raw-{{ $row['id'] }}-{{ md5($row['dataset_id'] ?? (string)$row['id']) }}">JSON</button>
          </td>
        </tr>
        <tr class="collapse" id="raw-{{ $row['id'] }}-{{ md5($row['dataset_id'] ?? (string)$row['id']) }}">
          <td colspan="7">
            <pre class="mb-0" style="max-height:260px;overflow:auto">{{ json_encode($row['raw'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) }}</pre>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center text-muted">нет данных</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{-- Пагинация --}}
  @if($totalPages > 1)
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        {{-- Предыдущая страница --}}
        <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
          <a class="page-link" href="?page={{ $currentPage - 1 }}&limit={{ $limit }}" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>

        {{-- Номера страниц --}}
        @php
          $startPage = max(1, $currentPage - 2);
          $endPage = min($totalPages, $currentPage + 2);
        @endphp

        @if($startPage > 1)
          <li class="page-item"><a class="page-link" href="?page=1&limit={{ $limit }}">1</a></li>
          @if($startPage > 2)
            <li class="page-item disabled"><span class="page-link">...</span></li>
          @endif
        @endif

        @foreach(range($startPage, $endPage) as $i)
          <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
            <a class="page-link" href="?page={{ $i }}&limit={{ $limit }}">{{ $i }}</a>
          </li>
        @endforeach

        @if($endPage < $totalPages)
          @if($endPage < $totalPages - 1)
            <li class="page-item disabled"><span class="page-link">...</span></li>
          @endif
          <li class="page-item"><a class="page-link" href="?page={{ $totalPages }}&limit={{ $limit }}">{{ $totalPages }}</a></li>
        @endif

        {{-- Следующая страница --}}
        <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
          <a class="page-link" href="?page={{ $currentPage + 1 }}&limit={{ $limit }}" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
    <div class="text-center text-muted small mt-2">
      Страница {{ $currentPage }} из {{ $totalPages }} (всего записей: {{ $totalItems }})
    </div>
  @endif
</div>
@endsection
