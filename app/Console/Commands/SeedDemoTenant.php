<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Submission;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;

class SeedDemoTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:seed-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a portfolio-ready demo tenant with full workspace sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting recruiter demo tenant seeding...');

        $tenantId = 'demo';
        $domainName = 'demo.' . preg_replace('/:\d+$/', '', config('app.domain', 'localhost'));

        // Delete existing demo tenant to reset data cleanly
        $existing = Tenant::find($tenantId);
        if ($existing) {
            $this->warn('Existing demo tenant found. Deleting to reset...');
            $existing->delete();
        }

        // Create the Tenant
        $tenant = Tenant::create([
            'id' => $tenantId,
            'name' => 'Demo Academy',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
            'active' => true,
            'data' => [
                'company_name' => 'Demo Academy',
                'full_name' => 'Demo Recruiter',
                'subscription_plan' => 'Pro',
                'domain_name' => 'demo',
            ],
        ]);

        $tenant->domains()->create([
            'domain' => $domainName
        ]);

        $this->info("Tenant '{$tenantId}' created with domain '{$domainName}'.");

        // Seed data inside the tenant database context
        $tenant->run(function () {
            $this->info('Seeding tenant-level databases...');

            // 1. Create Teacher/User
            $teacher = User::create([
                'name' => 'Jane Teacher',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            // 2. Create Subjects
            $subj1 = Subject::create([
                'name' => 'Introduction to Software Engineering',
                'code' => 'SE-101',
                'description' => 'Fundamental concepts of software development life cycles, agile methodologies, and OOP design patterns.',
                'user_id' => $teacher->id,
                'color' => '#10b981',
            ]);

            $subj2 = Subject::create([
                'name' => 'Database Management Systems',
                'code' => 'DBMS-202',
                'description' => 'Relational database designs, normalization, advanced SQL querying, indexing, and transaction safety.',
                'user_id' => $teacher->id,
                'color' => '#3b82f6',
            ]);

            $subj3 = Subject::create([
                'name' => 'Algorithms & Data Structures',
                'code' => 'DSA-303',
                'description' => 'Analysis of algorithms, time complexities, sorting, binary trees, graphs, and dynamic programming.',
                'user_id' => $teacher->id,
                'color' => '#8b5cf6',
            ]);

            // 3. Create Mock Students
            $studentsData = [
                ['name' => 'John Doe', 'email' => 'student1@example.com', 'student_id' => 'STU-001'],
                ['name' => 'Jane Smith', 'email' => 'student2@example.com', 'student_id' => 'STU-002'],
                ['name' => 'Bob Johnson', 'email' => 'student3@example.com', 'student_id' => 'STU-003'],
                ['name' => 'Alice Williams', 'email' => 'student4@example.com', 'student_id' => 'STU-004'],
                ['name' => 'Charlie Brown', 'email' => 'student5@example.com', 'student_id' => 'STU-005'],
            ];

            $students = [];
            foreach ($studentsData as $data) {
                $students[] = Student::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'student_id' => $data['student_id'],
                    'password' => bcrypt('password'),
                    'plan' => 'Premium',
                ]);
            }

            // Enroll all students in all 3 subjects
            foreach ($students as $student) {
                $student->subjects()->attach([
                    $subj1->id => ['status' => 'active'],
                    $subj2->id => ['status' => 'active'],
                    $subj3->id => ['status' => 'active'],
                ]);
            }

            // 4. Create Activities inside Subject 1 (Software Engineering)
            $act1 = Activity::create([
                'title' => 'Software Design Patterns Assignment',
                'description' => 'Submit a detailed report analyzing MVC, Singleton, and Factory design patterns in modern frameworks.',
                'due_date' => now()->addDays(5),
                'subject_id' => $subj1->id,
                'type' => 'assignment',
                'points' => 100,
                'is_published' => true,
            ]);

            $act2 = Activity::create([
                'title' => 'Git branching & collaboration lab',
                'description' => 'Practical exercise demonstrating git merge conflicts resolution, feature branching, and pull requests.',
                'due_date' => now()->addDays(10),
                'subject_id' => $subj1->id,
                'type' => 'assignment',
                'points' => 50,
                'is_published' => true,
            ]);

            // 5. Create Submissions
            // Student 1 submitted and is graded
            Submission::create([
                'student_id' => $students[0]->id,
                'activity_id' => $act1->id,
                'file_path' => 'submissions/student1_design_patterns.pdf',
                'status' => 'graded',
                'grade' => 95.00,
                'feedback' => 'Excellent analysis! High quality explanation of singleton thread safety.',
                'graded_at' => now(),
            ]);

            // Student 2 submitted but remains ungraded
            Submission::create([
                'student_id' => $students[1]->id,
                'activity_id' => $act1->id,
                'file_path' => 'submissions/student2_patterns_v2.pdf',
                'status' => 'submitted',
                'grade' => null,
                'feedback' => null,
            ]);

            // Student 3 submitted git lab
            Submission::create([
                'student_id' => $students[2]->id,
                'activity_id' => $act2->id,
                'file_path' => 'submissions/git_lab_output.txt',
                'status' => 'graded',
                'grade' => 48.00,
                'feedback' => 'Good job resolving merge conflict. Slight error in the rebase section.',
                'graded_at' => now(),
            ]);

            // 6. Create Quizzes & Questions
            $quizAct = Activity::create([
                'title' => 'Agile Methodologies & Scrum Basics',
                'description' => 'Test your understanding of sprint backlogs, velocity charts, daily standups, and retrospective meetings.',
                'due_date' => now()->addDays(3),
                'subject_id' => $subj1->id,
                'type' => 'material',
                'points' => 30,
                'is_published' => true,
            ]);

            $quiz = Quiz::create([
                'activity_id' => $quizAct->id,
                'title' => 'Agile Methodologies & Scrum Basics',
                'description' => 'A short check on Scrum and Agile Scrum framework pillars.',
                'time_limit' => 15,
                'passing_score' => 20,
                'is_published' => true,
                'show_results' => true,
                'randomize_questions' => false,
            ]);

            $q1 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'Which of the following is NOT one of the three pillars of Scrum?',
                'type' => 'multiple_choice',
                'options' => ['Transparency', 'Inspection', 'Adaptation', 'Velocity'],
                'correct_answer' => ['Velocity'],
                'points' => 10,
                'explanation' => 'Scrum is founded on empirical process control theory, which has three pillars: Transparency, Inspection, and Adaptation.',
                'order' => 1,
            ]);

            $q2 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'The Daily Scrum is a 15-minute time-boxed event for the Development Team.',
                'type' => 'true_false',
                'options' => ['True', 'False'],
                'correct_answer' => ['True'],
                'points' => 10,
                'explanation' => 'The Daily Scrum is key to keeping sprint iterations on target and is capped at 15 minutes.',
                'order' => 2,
            ]);

            $q3 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'What is the title of the person responsible for maximizing the value of the product?',
                'type' => 'short_answer',
                'options' => [],
                'correct_answer' => ['Product Owner'],
                'points' => 10,
                'explanation' => 'The Product Owner is sole owner and manager of the Product Backlog value.',
                'order' => 3,
            ]);

            // Create Quiz Attempt for Student 1
            $attempt = QuizAttempt::create([
                'student_id' => $students[0]->id,
                'quiz_id' => $quiz->id,
                'score' => 30.00,
                'started_at' => now()->subMinutes(12),
                'completed_at' => now(),
                'status' => 'completed',
                'answers' => [
                    $q1->id => 'Velocity',
                    $q2->id => 'True',
                    $q3->id => 'Product Owner'
                ]
            ]);

            // Create Quiz Attempt for Student 3 (who got 10 points)
            QuizAttempt::create([
                'student_id' => $students[2]->id,
                'quiz_id' => $quiz->id,
                'score' => 10.00,
                'started_at' => now()->subMinutes(14),
                'completed_at' => now(),
                'status' => 'completed',
                'answers' => [
                    $q1->id => 'Transparency', // wrong
                    $q2->id => 'True',         // correct
                    $q3->id => 'Scrum Master'  // wrong
                ]
            ]);
        });

        // Seed Super Admin if none exists, just to be sure they have central access
        $adminCount = User::count();
        if ($adminCount === 0) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $this->info("Created default central admin: admin@example.com / password");
        }

        // Log action in audit log
        try {
            \App\Models\ActivityLog::log('Demo Tenant Seeded', "System dynamically created and fully seeded the portfolio 'demo' tenant.");
        } catch (\Exception $e) {
            // Ignore if run outside web context log fail
        }

        $this->info('Recruiter demo tenant has been successfully seeded!');
        $this->info('You can access the workspace at: http://' . $domainName . ':8000');
        $this->info('Teacher Login: teacher@example.com / password');
        $this->info('Student Login: student1@example.com / password');
    }
}
