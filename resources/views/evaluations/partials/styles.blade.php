<style>
    /* Custom Radio Button Styling */
    .custom-radio {
        position: relative;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .custom-radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .custom-radio-checkmark {
        position: relative;
        display: inline-block;
        width: 18px;
        height: 18px;
        background-color: #fff;
        border: 2px solid #ccc;
        border-radius: 4px;
        transition: all 0.3s;
    }

    .custom-radio input:checked ~ .custom-radio-checkmark {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .custom-radio-checkmark::after {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .custom-radio input:checked ~ .custom-radio-checkmark::after {
        opacity: 1;
    }

    /* Specific styling for CC section radio buttons */
    .space-y-2 .custom-radio,
    .space-y-3 .custom-radio {
        justify-content: flex-start;
        width: auto;
    }

    .space-y-2 .custom-radio-checkmark,
    .space-y-3 .custom-radio-checkmark {
        margin-right: 8px;
    }

    /* Signature Pad Styling */
    #signatureCanvas {
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        cursor: crosshair;
    }
</style>