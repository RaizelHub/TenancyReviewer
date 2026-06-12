<?php

namespace App\Notifications;

use App\Models\TenantApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantApplicationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(TenantApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Simple text-based email for maximum compatibility
        return (new MailMessage)
            ->subject('Update on Your Tenant Application')
            ->greeting("Hello {$this->application->full_name},")
            ->line("We've reviewed your application for {$this->application->company_name}.")
            ->line("After careful consideration, we regret to inform you that we are unable to approve your application at this time.")
            ->line("Reason: {$this->application->notes}")
            ->line("If you would like to discuss this further or provide additional information, please feel free to contact our support team.")
            ->line("Thank you for your interest in our platform.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'company_name' => $this->application->company_name,
            'reason' => $this->application->notes,
        ];
    }
}
