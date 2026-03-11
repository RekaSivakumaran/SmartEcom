@extends('Layout.app')

@section('content')

<div class="container-fluid" style="padding:30px;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <h3 style="font-weight:700; margin:0;">
            <i class="fas fa-star" style="color:#f5c518; margin-right:8px;"></i>
            Product Reviews
        </h3>
        <div style="display:flex; gap:10px;">
            <span style="background:#f0ad4e; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.82em;">
                Pending: {{ $reviews->where('status','pending')->count() }}
            </span>
            <span style="background:#28a745; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.82em;">
                Approved: {{ $reviews->where('status','approved')->count() }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:10px 18px;
                    border-radius:8px; margin-bottom:20px;">
            <i class="fas fa-check-circle" style="margin-right:6px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div style="background:#fff; border-radius:14px;
                box-shadow:0 2px 12px rgba(0,0,0,0.07); overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
            <thead>
                <tr style="background:#f8f9fa; border-bottom:2px solid #f0f0f0;">
                    <th style="padding:14px 18px; text-align:left; color:#555;">#</th>
                    <th style="padding:14px 18px; text-align:left; color:#555;">Product</th>
                    <th style="padding:14px 18px; text-align:left; color:#555;">Reviewer</th>
                    <th style="padding:14px 18px; text-align:center; color:#555;">Rating</th>
                    <th style="padding:14px 18px; text-align:left; color:#555;">Comment</th>
                    <th style="padding:14px 18px; text-align:center; color:#555;">Status</th>
                    <th style="padding:14px 18px; text-align:center; color:#555;">Date</th>
                    <th style="padding:14px 18px; text-align:center; color:#555;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr style="border-bottom:1px solid #f5f5f5;">
                        <td style="padding:14px 18px; color:#aaa;">{{ $review->id }}</td>

                        {{-- Product --}}
                        <td style="padding:14px 18px;">
                            <strong style="color:#333; font-size:0.92em;">
                                {{ $review->product->name ?? 'N/A' }}
                            </strong>
                        </td>

                        {{-- Reviewer --}}
                        <td style="padding:14px 18px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:32px; height:32px; border-radius:50%;
                                            background:#d33b33; color:#fff; font-weight:700;
                                            display:flex; align-items:center; justify-content:center;
                                            font-size:0.85em; flex-shrink:0;">
                                    {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                </div>
                                <span style="color:#333;">{{ $review->reviewer_name }}</span>
                            </div>
                        </td>

                        {{-- Rating --}}
                        <td style="padding:14px 18px; text-align:center;">
                            <span style="color:#f5c518;">
                                @for($s = 1; $s <= 5; $s++)
                                    @if($s <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star" style="color:#ddd;"></i>
                                    @endif
                                @endfor
                            </span>
                            <br>
                            <small style="color:#888;">{{ $review->rating }}/5</small>
                        </td>

                        {{-- Comment --}}
                        <td style="padding:14px 18px; max-width:250px;">
                            <p style="margin:0; color:#555; font-size:0.88em;
                                      overflow:hidden; text-overflow:ellipsis;
                                      white-space:nowrap; max-width:220px;"
                               title="{{ $review->comment }}">
                                {{ $review->comment }}
                            </p>
                        </td>

                        {{-- Status --}}
                        <td style="padding:14px 18px; text-align:center;">
                            @php
                                $statusStyle = [
                                    'pending'  => 'background:#fff3cd; color:#856404;',
                                    'approved' => 'background:#d4edda; color:#155724;',
                                    'rejected' => 'background:#f8d7da; color:#721c24;',
                                ];
                            @endphp
                            <span style="{{ $statusStyle[$review->status] ?? '' }}
                                          padding:4px 12px; border-radius:20px;
                                          font-size:0.78em; font-weight:600;">
                                {{ ucfirst($review->status) }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td style="padding:14px 18px; text-align:center; color:#888; font-size:0.82em;">
                            {{ $review->created_at->format('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td style="padding:14px 18px; text-align:center;">
                            <div style="display:flex; gap:6px; justify-content:center;">

                                @if($review->status !== 'approved')
                                    <form method="POST"
                                          action="{{ route('admin.reviews.approve', $review->id) }}">
                                        @csrf
                                        <button type="submit"
                                                style="background:#28a745; color:#fff; border:none;
                                                       padding:5px 12px; border-radius:6px;
                                                       font-size:0.8em; cursor:pointer;"
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($review->status !== 'rejected')
                                    <form method="POST"
                                          action="{{ route('admin.reviews.reject', $review->id) }}">
                                        @csrf
                                        <button type="submit"
                                                style="background:#f0ad4e; color:#fff; border:none;
                                                       padding:5px 12px; border-radius:6px;
                                                       font-size:0.8em; cursor:pointer;"
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                <form method="POST"
                                      action="{{ route('admin.reviews.destroy', $review->id) }}"
                                      onsubmit="return confirm('Delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background:#dc3545; color:#fff; border:none;
                                                   padding:5px 12px; border-radius:6px;
                                                   font-size:0.8em; cursor:pointer;"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:#aaa;">
                            <i class="fas fa-star" style="font-size:2em; color:#ddd; display:block; margin-bottom:10px;"></i>
                            No reviews yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($reviews->hasPages())
            <div style="padding:16px 24px; border-top:1px solid #f0f0f0;">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

@endsection