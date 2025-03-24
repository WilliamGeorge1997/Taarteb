<?php

namespace Modules\Session\App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Common\Helpers\WhatsAppService;

class ParentNotificationWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $student;
    /**
     * Create a new job instance.
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $parent_phone = $this->student->parent_phone;
        $whatsAppService = new WhatsAppService();
        $whatsAppService->sendMessage($parent_phone, "نحيطكم علماً ان الطالب قد تغيب اليوم بتاريخ " . now()->toDateString() . " عسى المانع خيراً\n\nWe inform you that the student was absent today on " . now()->toDateString() . ", hoping there is good reason");
    }
}