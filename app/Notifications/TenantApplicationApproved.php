<?php

namespace App\Notifications;

use App\Models\TenantApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantApplicationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;
    protected $password;
    protected $domain;

    /**
     * Create a new notification instance.
     */
    public function __construct(TenantApplication $application, string $password, string $domain)
    {
        $this->application = $application;
        $this->password = $password;
        $this->domain = $domain;
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
        $url = "https://{$this->domain}";

        return (new MailMessage)
            ->subject('Your Tenant Application Has Been Approved')
            ->greeting("Hello {$this->application->full_name}!")
            ->line("We're pleased to inform you that your application for {$this->application->company_name} has been approved.")
            ->line("Your tenant has been created and is now ready to use.")
            ->line("Here are your login credentials:")
            ->line("Email: {$this->application->email}")
            ->line("Password: {$this->password}")
            ->line("Please change your password after your first login for security reasons.")
            ->action('Access Your Tenant', $url)
            ->line("Your tenant domain is: {$this->domain}")
            ->line("If you have any questions or need assistance, please don't hesitate to contact our support team.")
            ->line("Thank you for choosing our platform!");
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
            'domain' => $this->domain,
        ];
    }
}
