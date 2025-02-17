<h1>{{ $isSuccess ? 'Payment Successful!' : 'Payment Reminder' }}</h1>

<p>Dear {{ $student->name }},</p>

@if($customMessage)
    <p>{{ $customMessage }}</p>
@endif

@if($isSuccess)
    <p>Your payment has been successfully processed.</p>
@else
    <p>Your semester fee is due on {{ $dueDate ? $dueDate->format('Y-m-d') : 'soon' }}.</p>
@endif

<p>Sincerely,<br>{{ config('mail.from.name') }}</p>

@include('emails.signature')