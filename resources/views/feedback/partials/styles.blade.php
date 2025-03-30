<style>
    /* Custom Radio Button Styling */
    .custom-radio {
        position: relative;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .custom-radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Default radio style */
    .custom-radio-checkmark {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 3px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    /* Minimalistic radio buttons for satisfaction ratings */
    table .custom-radio-checkmark {
        width: 16px;
        height: 16px;
        border: 1.5px solid #e2e8f0;
        background-color: #fff;
        border-radius: 2px;
    }

    /* Subtle hover states */
    .custom-radio:hover .custom-radio-checkmark {
        border-color: #cbd5e1;
        background-color: #f8fafc;
        transform: none;
        box-shadow: none;
    }

    /* Minimal checked states */
    .custom-radio input:checked ~ .custom-radio-checkmark {
        background-color: #64748b;
        border-color: #64748b;
        transform: scale(1);
        box-shadow: none;
    }

    /* Checkmark symbol */
    .custom-radio-checkmark::after {
        content: "";
        position: absolute;
        top: 45%;
        left: 50%;
        width: 30%;
        height: 60%;
        border: solid white;
        border-width: 0 2.5px 2.5px 0;
        transform: translate(-50%, -50%) rotate(45deg);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    /* Minimal checkmark for satisfaction ratings */
    table .custom-radio-checkmark::after {
        border-width: 0 2px 2px 0;
        width: 25%;
        height: 50%;
    }

    .custom-radio input:checked ~ .custom-radio-checkmark::after {
        opacity: 1;
    }

    /* Adjust table cell padding for smaller checkboxes */
    table td .custom-radio {
        padding: 0.25rem;
    }

    /* Form Input Styling */
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    textarea,
    select {
        width: 100%;
        padding: 0.625rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        background-color: #fff;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    input[type="text"]:hover,
    input[type="email"]:hover,
    input[type="tel"]:hover,
    textarea:hover,
    select:hover {
        border-color: #93c5fd;
        box-shadow: 0 2px 4px rgba(59,130,246,0.1);
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        transform: translateY(-1px);
    }

    /* Label Styling */
    label {
        color: #475569;
        font-size: 0.875rem;
    }

    /* Table Styling */
    table {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    table tr:hover {
        background-color: #f8fafc;
    }

    table td {
        transition: background-color 0.2s ease;
    }

    /* Radio cell highlight */
    table td .custom-radio {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        padding: 0.5rem;
    }

    table td .custom-radio:hover {
        background-color: #f0f9ff;
    }

    table td .custom-radio input:checked ~ .custom-radio-checkmark {
        transform: scale(1.1);
    }

    /* Signature Pad Styling */
    #signatureCanvas {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        cursor: crosshair;
        background: #f8fafc;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    #signatureCanvas:hover {
        border-color: #93c5fd;
        box-shadow: 0 2px 4px rgba(59,130,246,0.1);
    }
</style>