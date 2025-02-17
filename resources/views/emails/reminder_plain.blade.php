{{ $isSuccess ? 'Payment Successful!' : 'Payment Reminder' }}

Dear {{ $student->name }},

@if($customMessage)
{{ $customMessage }}
@endif

@if($isSuccess)
Your payment has been successfully processed.
@else
Your semester fee is due on {{ $dueDate ? $dueDate->format('Y-m-d') : 'soon' }}.
@endif

Sincerely,
{{ config('mail.from.name') }}

{{-- Tanda tangan dalam format plain text --}}
{!! strip_tags($signature) !!} {{-- Hilangkan tag HTML --}}