@extends('layouts.app')
@section('title','Notifications')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center"><h1>Notifications</h1><form method="POST" action="{{ route('notifications.read-all') }}">@csrf @method('PATCH')<button class="btn btn-secondary">Mark all as read</button></form></div>
<div style="background:white;border-radius:8px;padding:16px">
@forelse($notifications as $notification)
<div style="padding:14px;border-bottom:1px solid #eee;{{ $notification->read_at ? 'opacity:.7' : 'background:#fff8e7' }}"><strong>{{ $notification->data['title'] ?? 'Notification' }}</strong><p>{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>@if(!empty($notification->data['product_id']))<a href="{{ route('products.show',$notification->data['product_id']) }}">View product</a>@endif <small> · {{ $notification->created_at->diffForHumans() }}</small>@unless($notification->read_at)<form method="POST" action="{{ route('notifications.read',$notification->id) }}" style="display:inline;margin-left:12px">@csrf @method('PATCH')<button class="btn btn-secondary">Mark read</button></form>@endunless</div>
@empty<p>No notifications yet. Low-stock alerts will appear here when stock falls to the reorder level.</p>@endforelse
</div><div style="margin-top:16px">{{ $notifications->links() }}</div>
@endsection
