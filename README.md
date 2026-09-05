# Training Institute Management System

A modern, web-based **Training Institute Management System** that combines Learning Management System (LMS) and Enterprise Resource Planning (ERP) capabilities into a single centralized platform.

The system is designed to help training institutes, coaching centers, professional academies, and skill-development organizations manage courses, batches, students, trainers, classes, attendance, learning materials, examinations, payments, certificates, and administrative operations from one platform.

---

## Overview

As a training institute grows, managing students, batches, attendance, payments, examinations, and certificates through spreadsheets and disconnected tools becomes increasingly difficult.

This project provides a centralized digital solution where administrators, trainers, and students can manage their respective activities through dedicated interfaces.

The platform covers the complete workflow:

**Course → Batch → Enrollment → Classes → Attendance → Materials → Assignments → Exams → Results → Payments → Certificate**

The goal is to simplify institute operations, reduce manual work, improve visibility, and provide a better experience for both management and students.

---

## Key Features

### Public Website

- Modern responsive landing page
- Featured courses
- Course listing
- Course details
- Trainer directory
- About page
- Contact form
- Student registration and login
- Public certificate verification
- Printable certificate verification page
- Course and batch information

### Student Portal

- Student dashboard
- Enrolled course overview
- Batch switching
- Class schedule
- Attendance tracking
- Attendance percentage
- Course materials
- Assignment tracking
- Assignment deadline and overdue detection
- Examination results
- Payment history
- Outstanding payment tracking
- Certificate access
- Profile management

### Trainer Portal

- Trainer dashboard
- Assigned batch management
- Batch switching
- Class session management
- Attendance management
- Student management
- Examination management
- Student academic monitoring
- Trainer profile management

Trainer access is restricted to the batches assigned to the authenticated trainer.

### Admin Panel

The administration panel is powered by **Filament 3** and provides centralized management of the entire institute.

Administrators can manage:

- Users
- Students
- Trainers
- Courses
- Batches
- Enrollments
- Class Sessions
- Attendance
- Materials
- Assignments
- Exams
- Results
- Payments
- Certificates
- Announcements

---

## Admin Dashboard

The administration dashboard provides real-time operational and financial insights.

### Key Metrics

- Total Students
- Total Courses
- Total Batches
- Total Trainers
- Total Revenue
- Outstanding Dues

### Analytics & Widgets

- Enrollment Chart
- Attendance Chart
- Revenue Chart
- Recent Enrollments
- Recent Payments
- Today's Classes
- Upcoming Classes

These dashboards help management monitor the institute's performance from a single location.

---

## Certificate Management

The system includes a complete digital certificate management and verification workflow.

Administrators can issue certificates containing:

- Certificate Number
- Verification Code
- Student Information
- Course Information
- Completion Information

Certificates can then be publicly verified without requiring users to log into the system.

### Certificate Workflow

```text
Administrator
      |
      v
Issue Certificate
      |
      v
Certificate Number
+
Verification Code
      |
      v
Public Verification
      |
      v
Verified Certificate
      |
      v
Printable Certificate
