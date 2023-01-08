<style>
    a {
        color: {{auth()->user()->clinic->color}};
    }

    .btn-primary {
        background-color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .btn-primary.disabled, .btn-primary:disabled {
        background-color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .btn-outline-primary {
        color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .btn-outline-primary:hover {
        background-color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .btn-outline-primary.disabled, .btn-outline-primary:disabled {
        color: {{auth()->user()->clinic->color}};
    }

    .btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active,
    .show > .btn-outline-primary.dropdown-toggle {
        background-color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .btn-link {
        color: {{auth()->user()->clinic->color}};
    }

    .dropdown-item.active, .dropdown-item:active {
        background-color: {{auth()->user()->clinic->color}};
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: {{auth()->user()->clinic->color}};
        background-color: {{auth()->user()->clinic->color}};
    }

    .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
        border-color: {{auth()->user()->clinic->color}};
        background-color: {{auth()->user()->clinic->color}};
    }

    .custom-range::-webkit-slider-thumb {
        background-color: {{auth()->user()->clinic->color}};
    }

    .custom-range::-moz-range-thumb {
        background-color: {{auth()->user()->clinic->color}};
    }

    .custom-range::-ms-thumb {
        background-color: {{auth()->user()->clinic->color}};
    }

    .nav-pills .nav-link.active,
    .nav-pills .show > .nav-link {
        background-color: {{auth()->user()->clinic->color}};
    }

    .page-link {
        color: {{auth()->user()->clinic->color}};
    }

    .page-item.active .page-link {
        background-color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .badge-primary {
        background-color: {{auth()->user()->clinic->color}};
    }

    .progress-bar {
        background-color: {{auth()->user()->clinic->color}};
    }

    .list-group-item.active {
        background-color: {{auth()->user()->clinic->color}};
        border-color: {{auth()->user()->clinic->color}};
    }

    .bg-primary {
        background-color: {{auth()->user()->clinic->color}} !important;
    }

    .border-primary {
        border-color: {{auth()->user()->clinic->color}} !important;
    }

    .text-primary {
        color: {{auth()->user()->clinic->color}} !important;
    }

    .bg-gradient-primary {
        background-color: {{auth()->user()->clinic->color}};
        background-image: linear-gradient(180deg, {{auth()->user()->clinic->color}} 10%, {{auth()->user()->clinic->color}} 100%);
    }

    .border-left-primary {
        border-left: 0.25rem solid {{auth()->user()->clinic->color}} !important;
    }

    .border-bottom-primary {
        border-bottom: 0.25rem solid {{auth()->user()->clinic->color}} !important;
    }

    .topbar .dropdown-list .dropdown-header {
        background-color: {{auth()->user()->clinic->color}};
        border: 1px solid {{auth()->user()->clinic->color}};
    }

    .sidebar .nav-item .collapse .collapse-inner .collapse-item.active,
    .sidebar .nav-item .collapsing .collapse-inner .collapse-item.active {
        color: {{auth()->user()->clinic->color}};
    }

    .error:before {
        text-shadow: 1px 0 {{auth()->user()->clinic->color}};
    }

</style>
