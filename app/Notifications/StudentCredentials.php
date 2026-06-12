<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentCredentials extends Notification
{
    use Queueable;

    protected $student;
    protected $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(Student $student, string $password)
    {
        $this->student = $student;
        $this->password = $password;
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
        \Log::info('StudentCredentials notification triggered', [
            'student_email' => $this->student->email,
            'password' => $this->password
        ]);
        $tenantName = tenant('name') ?? config('app.name');
        $url = config('app.url') . '/login';

        return (new MailMessage)
            ->subject("Your {$tenantName} Account Credentials")
            ->greeting("Hello {$this->student->name}!")
            ->line("Welcome to {$tenantName}! Your account has been created.")
            ->line("Here are your login credentials:")
            ->line("Email: {$this->student->email}")
            ->line("Password: {$this->password}")
            ->line("Plan: " . ucfirst($this->student->plan))
            ->line("Please change your password after your first login for security reasons.")
            ->action('Login to Your Account', $url)
            ->line("If you have any questions or need assistance, please contact your instructor.")
            ->line("Thank you for joining our platform!");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
