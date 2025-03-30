@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ asset('images/logo.png') }}" alt="BISU Logo" style="max-width: 120px; margin-bottom: 20px;">
    <h1 style="color: #4F46E5; margin: 0; font-size: 24px; font-weight: 700;">Faculty Evaluation Period</h1>
    <div style="background-color: #6B7280; color: white; display: inline-block; padding: 5px 15px; border-radius: 20px; margin-top: 10px; font-size: 14px; font-weight: bold; letter-spacing: 1px;">COMPLETED</div>
</div>

<p style="margin-bottom: 20px; font-size: 16px; color: #374151;">Dear <strong>{{ $recipientType }}</strong>,</p>

<p style="margin-bottom: 20px; font-size: 16px; color: #374151;">We would like to inform you that the Faculty Evaluation Period for <strong style="color: #4F46E5;">{{ $evaluationPeriod->academic_year }} {{ $evaluationPeriod->type }} Semester</strong> has been completed.</p>

<div style="background-color: #F3F4F6; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
    <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 15px; color: #111827;">Period Details:</h2>
    <div style="display: flex; margin-bottom: 10px; align-items: center;">
        <div style="min-width: 25px; margin-right: 10px;">📅</div>
        <div>
            <span style="font-weight: 600; color: #374151;">Start Date:</span> 
            <span style="color: #4B5563;">{{ date('F d, Y', strtotime($evaluationPeriod->start_date)) }}</span>
        </div>
    </div>
    <div style="display: flex; align-items: center;">
        <div style="min-width: 25px; margin-right: 10px;">🏁</div>
        <div>
            <span style="font-weight: 600; color: #374151;">End Date:</span> 
            <span style="color: #4B5563;">{{ date('F d, Y', strtotime($evaluationPeriod->end_date)) }}</span>
        </div>
    </div>
</div>

@if($recipientType == 'Student')
<div style="background-color: rgba(79, 70, 229, 0.1); border-left: 4px solid #4F46E5; padding: 15px; margin-bottom: 25px; color: #4B5563;">
    Thank you for participating in the faculty evaluation process. Your feedback is valuable and helps us improve the quality of education.
</div>

@component('mail::button', ['url' => $url.'/student/dashboard', 'color' => 'primary'])
Visit Student Dashboard
@endcomponent
@elseif($recipientType == 'Faculty')
<div style="background-color: rgba(79, 70, 229, 0.1); border-left: 4px solid #4F46E5; padding: 15px; margin-bottom: 25px; color: #4B5563;">
    The evaluation results will be processed and made available to you soon. Thank you for your cooperation during this evaluation period.
</div>

@component('mail::button', ['url' => $url.'/faculty/dashboard', 'color' => 'primary'])
View Faculty Dashboard
@endcomponent
@else
<div style="background-color: rgba(79, 70, 229, 0.1); border-left: 4px solid #4F46E5; padding: 15px; margin-bottom: 25px; color: #4B5563;">
    The evaluation results for your department will be processed and available for review soon. Thank you for overseeing this evaluation period.
</div>

@component('mail::button', ['url' => $url.'/dashboard', 'color' => 'primary'])
View Evaluation Dashboard
@endcomponent
@endif

<div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #E5E7EB; text-align: center; color: #6B7280; font-size: 14px;">
    <p style="margin: 0;">&copy; {{ date('Y') }} BISU CoreScore System. All rights reserved.</p>
    <p style="margin: 5px 0 0;">Bohol Island State University</p>
</div>
@endcomponent
