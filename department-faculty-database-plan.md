# Academic Evaluation System Database Schema Design

## Core Tables

### departments
```sql
CREATE TABLE departments (
    id bigint PRIMARY KEY,
    name varchar(255) NOT NULL,
    code varchar(50) NOT NULL UNIQUE,
    created_at timestamp,
    updated_at timestamp
);
```

### faculty_members
```sql
CREATE TABLE faculty_members (
    id bigint PRIMARY KEY,
    department_id bigint NOT NULL,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);
```

### students
```sql
CREATE TABLE students (
    id bigint PRIMARY KEY,
    year_level tinyint NOT NULL CHECK (year_level BETWEEN 1 AND 4),
    department_id bigint NOT NULL,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);
```

### subjects
```sql
CREATE TABLE subjects (
    id bigint PRIMARY KEY,
    department_id bigint NOT NULL,
    code varchar(50) NOT NULL,
    name varchar(255) NOT NULL,
    description text,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (department_id) REFERENCES departments(id),
    UNIQUE(department_id, code)
);
```

### faculty_subject_assignments
```sql
CREATE TABLE faculty_subject_assignments (
    id bigint PRIMARY KEY,
    faculty_id bigint NOT NULL,
    subject_id bigint NOT NULL,
    year_level tinyint NOT NULL CHECK (year_level BETWEEN 1 AND 4),
    academic_year varchar(20) NOT NULL,
    semester tinyint NOT NULL CHECK (semester BETWEEN 1 AND 2),
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (faculty_id) REFERENCES faculty_members(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    UNIQUE(faculty_id, subject_id, year_level, academic_year, semester)
);
```

### evaluation_periods
```sql
CREATE TABLE evaluation_periods (
    id bigint PRIMARY KEY,
    academic_year varchar(20) NOT NULL,
    semester tinyint NOT NULL CHECK (semester BETWEEN 1 AND 2),
    start_date timestamp NOT NULL,
    end_date timestamp NOT NULL,
    is_active boolean DEFAULT false,
    created_at timestamp,
    updated_at timestamp,
    UNIQUE(academic_year, semester)
);
```

### evaluation_criteria
```sql
CREATE TABLE evaluation_criteria (
    id bigint PRIMARY KEY,
    category varchar(255) NOT NULL,
    criterion text NOT NULL,
    weight decimal(3,2) NOT NULL CHECK (weight BETWEEN 0 AND 1),
    created_at timestamp,
    updated_at timestamp
);
```

### faculty_evaluations
```sql
CREATE TABLE faculty_evaluations (
    id bigint PRIMARY KEY,
    student_id bigint NOT NULL,
    faculty_id bigint NOT NULL,
    subject_id bigint NOT NULL,
    evaluation_period_id bigint NOT NULL,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty_members(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (evaluation_period_id) REFERENCES evaluation_periods(id),
    UNIQUE(student_id, faculty_id, subject_id, evaluation_period_id)
);
```

### evaluation_responses
```sql
CREATE TABLE evaluation_responses (
    id bigint PRIMARY KEY,
    faculty_evaluation_id bigint NOT NULL,
    criterion_id bigint NOT NULL,
    score tinyint NOT NULL CHECK (score BETWEEN 1 AND 5),
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (faculty_evaluation_id) REFERENCES faculty_evaluations(id),
    FOREIGN KEY (criterion_id) REFERENCES evaluation_criteria(id),
    UNIQUE(faculty_evaluation_id, criterion_id)
);
```

## Key Features

1. **Department Management**
   - Departments can manage both faculty accounts and year levels
   - Each faculty member and student belongs to a department

2. **Faculty Teaching Assignments**
   - Faculty members can be assigned to multiple subjects
   - Each assignment specifies the year level, academic year, and semester
   - Maintains historical teaching assignments

3. **Student Year Level Management**
   - Students are assigned to a specific year level
   - Year level constrains which faculty members they can evaluate

4. **Evaluation Access Control**
   - Students can only evaluate faculty members teaching in their year level
   - Controlled through the faculty_subject_assignments table

## Efficient Queries

### Get Eligible Faculty for Student Evaluation
```sql
SELECT DISTINCT 
    f.id,
    f.first_name,
    f.last_name,
    s.code as subject_code,
    s.name as subject_name
FROM faculty_members f
JOIN faculty_subject_assignments fsa ON f.id = fsa.faculty_id
JOIN subjects s ON fsa.subject_id = s.subject_id
JOIN evaluation_periods ep ON fsa.academic_year = ep.academic_year 
    AND fsa.semester = ep.semester
WHERE fsa.year_level = :student_year_level
    AND f.department_id = :department_id
    AND ep.is_active = true
    AND NOT EXISTS (
        SELECT 1 
        FROM faculty_evaluations fe
        WHERE fe.student_id = :student_id
            AND fe.faculty_id = f.id
            AND fe.evaluation_period_id = ep.id
    );
```

### Get Faculty Evaluation Summary
```sql
SELECT 
    f.id,
    f.first_name,
    f.last_name,
    s.name as subject_name,
    fsa.year_level,
    AVG(er.score) as average_score,
    COUNT(DISTINCT fe.student_id) as total_evaluators
FROM faculty_members f
JOIN faculty_evaluations fe ON f.id = fe.faculty_id
JOIN evaluation_responses er ON fe.id = er.faculty_evaluation_id
JOIN faculty_subject_assignments fsa ON f.id = fsa.faculty_id
JOIN subjects s ON fsa.subject_id = s.subject_id
WHERE fe.evaluation_period_id = :evaluation_period_id
GROUP BY f.id, f.first_name, f.last_name, s.name, fsa.year_level;
```

## Index Recommendations

1. Foreign key columns
2. Compound indexes for frequently filtered columns:
```sql
CREATE INDEX idx_faculty_assignments ON faculty_subject_assignments(faculty_id, year_level, academic_year, semester);
CREATE INDEX idx_evaluation_period_status ON evaluation_periods(academic_year, semester, is_active);
CREATE INDEX idx_student_evaluations ON faculty_evaluations(student_id, evaluation_period_id);
```

## Data Integrity Features

1. Check constraints on year_level and semester values
2. Unique constraints to prevent duplicate assignments and evaluations
3. Foreign key constraints to maintain referential integrity
4. Required fields marked as NOT NULL
5. Evaluation period control through is_active flag

## Description

1. The schema is designed with clear separation of concerns:
   - Core entities (departments, faculty, students)
   - Teaching assignments and subjects
   - Evaluation system (periods, criteria, responses)

2. Key features implemented:
   - Year level restrictions through faculty_subject_assignments
   - Evaluation access control through relationship tables
   - Efficient querying with proper indexes
   - Data integrity through constraints

3. Next recommended steps:
   - Review the schema design and relationships
   - Implement the migrations
   - Set up the models and relationships
   - Create the necessary controllers and forms