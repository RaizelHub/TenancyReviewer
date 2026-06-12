<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubmissionPolicy
{

    /**
     * Allow teachers to view all submissions
     */
    public function before($user, $ability)
    {
        // Teachers (regular users) can do anything with submissions
        if ($user instanceof User) {
            return Response::allow();
        }

        return null;
    }

    public function view($user, Submission $submission)
    {
        // If it's a student, check if they own the submission
        if ($user instanceof Student) {
            return $user->id === $submission->student_id
                ? Response::allow()
                : Response::deny('You do not own this submission.');
        }

        // For regular users (teachers), allow access
        return Response::allow();
    }

    public function update($user, Submission $submission)
    {
        // If it's a student, check if they own the submission
        if ($user instanceof Student) {
            return $user->id === $submission->student_id
                ? Response::allow()
                : Response::deny('You do not own this submission.');
        }

        // For regular users (teachers), allow access
        return Response::allow();
    }

    public function delete($user, Submission $submission)
    {
        // If it's a student, check if they own the submission
        if ($user instanceof Student) {
            return $user->id === $submission->student_id
                ? Response::allow()
                : Response::deny('You do not own this submission.');
        }

        // For regular users (teachers), allow access
        return Response::allow();
    }
}