<?php
// the table of tasks are compose of following columns: id, title, description, status, priority, due_date, project_id, assigned_user_id, created_at
return [
    [
        'title' => 'Design Homepage Layout',
        'description' => 'Create wireframes and mockups for the new homepage',
        'status' => 'in_progress',
        'priority' => 'high',
        'due_date' => '2025-02-01',
        'project_name' => 'Website Redesign', // Will be converted to project_id during seeding
        'assigned_username' => 'john.smith' // Will be converted to user_id during seeding
    ],
    [
        'title' => 'Setup Development Environment',
        'description' => 'Configure development tools and environment for mobile app',
        'status' => 'completed',
        'priority' => 'high',
        'due_date' => '2025-02-10',
        'project_name' => 'Mobile App Development',
        'assigned_username' => 'john.smith'
    ],
    [
        'title' => 'Database Schema Design',
        'description' => 'Design the new database schema for migration',
        'status' => 'completed',
        'priority' => 'high',
        'due_date' => '2024-12-15',
        'project_name' => 'Database Migration',
        'assigned_username' => 'john.smith'
    ],
    [
        'title' => 'User Authentication Module',
        'description' => 'Implement secure user authentication system',
        'status' => 'pending',
        'priority' => 'medium',
        'due_date' => '2025-03-15',
        'project_name' => 'Mobile App Development',
        'assigned_username' => 'john.smith'
    ],
    [
        'title' => 'Security Policy Review',
        'description' => 'Review and update company security policies',
        'status' => 'pending',
        'priority' => 'medium',
        'due_date' => '2025-03-20',
        'project_name' => 'Security Audit',
        'assigned_username' => 'john.smith'
    ],
    [
        'title' => 'Responsive Design Implementation',
        'description' => 'Implement responsive design for all website pages',
        'status' => 'pending',
        'priority' => 'medium',
        'due_date' => '2025-02-28',
        'project_name' => 'Website Redesign',
        'assigned_username' => 'john.smith'
    ]
];
