# Implementation Plan: Office-Specific QR Code Generation

## Overview
Add functionality for MIS Office to generate QR codes and links that pre-select their office in the evaluation form, ensuring visitors evaluate the correct office.

## Technical Changes

### 1. Backend Changes

#### Routes
- Add new route: `/evaluation/{office}` that accepts an office ID
- This route will serve the evaluation form with a pre-selected office

#### EvaluationController
- Modify `index()` method to accept optional office parameter
- Add validation to ensure office exists
- Pass office-locked status to view

#### Filament Integration
- Add QR code generation action to OfficeResource
- Generate shareable link with office ID
- Convert link to QR code
- Allow downloading/copying of both link and QR code

### 2. Frontend Changes

#### Evaluation Form
- Modify visit-info.blade.php:
  - Add support for pre-selected office
  - Disable office selection when pre-selected
  - Keep current functionality for general evaluation form

#### Office Admin Dashboard
- Add QR code generation button
- Display generated QR code
- Provide copy/download options for:
  - Direct link
  - QR code image

## Implementation Steps

1. Backend Development
   - Create new route with office parameter
   - Update EvaluationController logic
   - Implement QR code generation service
   - Integrate with Filament admin panel

2. Frontend Development
   - Modify evaluation form to handle locked office selection
   - Create QR code display interface
   - Add download/copy functionality

3. Testing
   - Test QR code generation
   - Verify office selection locking
   - Test form submission with locked office
   - Validate QR code scanning workflow

## Security Considerations
- Validate office ID in URL
- Ensure only authorized office admins can generate QR codes
- Protect against tampering with pre-selected office

## User Experience
- Clear indication that office is pre-selected
- Easy QR code generation process
- Simple sharing/downloading of QR codes and links