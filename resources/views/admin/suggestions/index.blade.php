@extends('layouts.app')

@section('title', 'Usulan Masuk')

@section('content')
    <h1 class="h3 mb-3">Usulan Masuk</h1>

    @include('admin._nav')

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">Alternatif</th>
                    <th scope="col">Menggantikan</th>
                    <th scope="col">Situs</th>
                    <th scope="col">Catatan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Dikirim</th>
                    <th scope="col" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suggestions as $suggestion)
                    @php
                        $status = [
                            'pending' => ['Menunggu', 'secondary'],
                            'approved' => ['Disetujui', 'success'],
                            'rejected' => ['Ditolak', 'danger'],
                        ][$suggestion->status] ?? [ucfirst($suggestion->status), 'secondary'];
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $suggestion->name }}</td>
                        <td>{{ $suggestion->proprietary_name }}</td>
                        <td>
                            @if ($suggestion->website)
                                <a href="{{ $suggestion->website }}" target="_blank" rel="noopener">Kunjungi</a>
                            @else
                                <span class="text-muted">&mdash;</span>
                            @endif
                        </td>
                        <td class="small">
                            {{ $suggestion->note ? \Illuminate\Support\Str::limit($suggestion->note, 80) : '—' }}
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $status[1] }}">{{ $status[0] }}</span>
                        </td>
                        <td class="small text-nowrap">{{ $suggestion->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="text-end text-nowrap">
                            @if ($suggestion->status !== 'approved')
                                <form
                                    method="POST"
                                    action="{{ route('admin.suggestions.update', $suggestion->id) }}"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                            @endif

                            @if ($suggestion->status !== 'rejected')
                                <form
                                    method="POST"
                                    action="{{ route('admin.suggestions.update', $suggestion->id) }}"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Tolak</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada usulan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $suggestions->links('pagination::bootstrap-5') }}
@endsection
