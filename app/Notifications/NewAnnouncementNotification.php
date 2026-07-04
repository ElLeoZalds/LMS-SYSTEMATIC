<?php

namespace App\Notifications;

use App\Models\Announcement;
use App\Models\Training;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Announcement $announcement,
        protected Training $training,
        protected User $teacher
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $teacherName = trim(implode(' ', array_filter([
            optional($this->teacher->person)->first_names,
            optional($this->teacher->person)->last_names,
        ])));

        return [
            'type' => 'announcement',
            'announcement_id' => $this->announcement->announcement_id,
            'announcement_title' => 'Nuevo anuncio publicado',
            'announcement_message' => $this->announcement->content,
            'announcement_link' => $this->announcement->link,
            'training_id' => $this->training->training_id,
            'course_name' => optional($this->training->course)->title ?? 'Curso',
            'teacher_name' => $teacherName ?: $this->teacher->username,
            'level' => 'info',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
