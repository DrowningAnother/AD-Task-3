<?php
// the table of project_users are compose of following columns: project_id, user_id, role, assigned_at
return [
    [
        'project_name' => 'Website Redesign', // Will be converted to project_id during seeding
        'username' => 'john.smith', // Will be converted to user_id during seeding
        'role' => 'lead_developer'
    ],
    [
        'project_name' => 'Mobile App Development',
        'username' => 'john.smith',
        'role' => 'developer'
    ],
    [
        'project_name' => 'Database Migration',
        'username' => 'john.smith',
        'role' => 'database_admin'
    ],
    [
        'project_name' => 'Security Audit',
        'username' => 'john.smith',
        'role' => 'security_analyst'
    ]
];
