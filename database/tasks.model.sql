CREATE TABLE IF NOT EXISTS public."tasks" (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    title varchar(225) NOT NULL,
    description text,
    status varchar(50) DEFAULT 'pending',
    priority varchar(50) DEFAULT 'medium',
    due_date date,
    project_id uuid REFERENCES projects(id) ON DELETE CASCADE,
    assigned_user_id uuid REFERENCES users(id) ON DELETE SET NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP
);
