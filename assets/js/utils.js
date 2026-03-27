export function confirmAction(selector, options = {}) {
    $(document).on('click', selector, function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        
        Swal.fire({
            title: options.title || "Are you sure?",
            text: options.text || "",
            icon: options.icon || "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: options.confirmText || "Yes",
            cancelButtonText: options.cancelText || "No"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
}
